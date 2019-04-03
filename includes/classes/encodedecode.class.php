<?php
class Encodedecodeclass{
	private $esecret_key = "abchefghjkminpqrstuvwxyz0123456789min3498678pqrstuvwxyz56";
	private $normal_text = "";
	private $encrypted_text = "";
	
	private $hkey1 = "rjm]<eX[dFbe.e}l/@>JHQH-5.$ eK>D@4,29vAAu3Pm0S`Qpg)U|gDh;%FU~(*/')";
	private $hkey2 = "h2+x`;I2{Fp48a')";
	
	
	public function txt_encode($txt){
		return($this->txt_convert($txt, $this->esecret_key));
	} 
	public function txt_decode($txt){
		return($this->txt_convert($txt, $this->esecret_key));
	} 	
	
	public function txt_convert($str, $sky = ''){
		if( $sky == ''){ return $str; }
		
		$sky = str_replace(chr(32),'',$sky);		
		if(strlen($sky)<8){ exit('Error regarding KEY'); }
		
		$klen = strlen($sky )< 50 ? strlen($sky):50;
				
		$kk = array();
		for($i = 0; $i < $klen; $i++){
			$k[$i] = ord($sky{$i})&0x1F;
		}
		
		$jj = 0;
		for($i = 0; $i < strlen($str); $i++){
			$e = ord($str{$i});
			$str{$i} = $e&0xE0?chr($e^$k[$jj]):chr($e);
			$jj++;
			$jj=$jj==$klen?0:$jj;
		}
		return $str;
	}
	
	/* Another way */
	public function text_pad($data, $size){
		$length = $size - strlen($data) % $size;
		return $data . str_repeat(chr($length), $length);
	}
	
	public function text_unpad($data){
		return substr($data, 0, -ord($data[strlen($data) - 1]));
	}
	
	public function create_keys(){
		$key_size = 32; // 256 bits
		$encryption_key = openssl_random_pseudo_bytes($key_size, $strong);
				
		$iv_size = 16; // 128 bits
		$iv = openssl_random_pseudo_bytes($iv_size, $strong);
		
		$returnval = array(
            'encryption_key' => $encryption_key,
			'iv' => $iv
        );	
		return (object)$returnval;
	}
	
	public function text_encode($txt, $encryption_key = '', $iv = ''){	
		if ($encryption_key == ""){
			$encryption_key = $this->hkey1;
		}
		
		if ($iv == ""){
			$iv = $this->hkey2;
		}
		
		$enc_txt = openssl_encrypt(
			$this->text_pad($txt, 16), // padded data
			'AES-256-CBC',        // cipher and mode
			$encryption_key,      // secret key
			0,                    // options (not used)
			$iv                   // initialisation vector
		);		
		return $enc_txt;
	}
	
	public function text_decode($txt, $encryption_key = '', $iv = ''){
		if ($encryption_key == ""){
			$encryption_key = $this->hkey1;
		}
		
		if ($iv == ""){
			$iv = $this->hkey2;
		}
		$dec_txt = $this->text_unpad(openssl_decrypt(
			$txt,
			'AES-256-CBC',
			$encryption_key,
			0,
			$iv
		));
		return $dec_txt;
	}	
}
?>