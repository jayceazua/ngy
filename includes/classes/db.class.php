<?php
class Database {
	protected $_server;
	protected $_user;
	protected $_pass;
	protected $_database;
	
	private $link_id = 0;
	
	public $record = array();
	public $error = "";
	public $errno = 0;
	public $field_table= "";
	public $affected_rows = 0;
	public $query_id = 0;
	
	private $pdo = "";	

	public function __construct($dbuser, $dbpassword, $dbname, $dbhost) {
		$this->link_id = @mysqli_connect($dbhost, $dbuser, $dbpassword);
	
		if (!$this->link_id) {
			$this->error_display("Could not connect to server: <b>$dbhost</b>.");
		}
	
		if(!@mysqli_select_db($this->link_id, $dbname)) {
			$this->error_display("Could not open database: <b>$dbname</b>.");
		}
	
		$this->_server = $dbhost;
		$this->_user = $dbuser;
		$this->_pass = $dbpassword;
		$this->_database = $dbname;
		
		//PDO connection
		$dsn = "mysql:host=".$this->_server.";dbname=".$this->_database."";
		try {
			 $this->pdo = new PDO($dsn, $this->_user, $this->_pass);
			 $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			 $this->error_display($e->getMessage());
		}
	}	
		
	function query_insert($table, $data) {
		$q = "INSERT INTO ".$table." ";
		$v = ''; $n = '';
	
		foreach($data as $key=>$val) {
			$n.= $key.", ";
			if(strtolower($val) == 'null'){ $v.="NULL, "; }
			elseif(strtolower($val) == 'now()'){ $v.="NOW(), "; }
			else{ $v.= "'". $this->escape($val) ."', "; }
		}
	
		$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
	
		if($this->query($q)){
			return mysqli_insert_id();
		}else{ 
			return false;
		}	
   }
   
	function query_update($table, $data, $where = '1') {
		$q="UPDATE ".$table." SET ";
		
		foreach($data as $key=>$val) {
			if(strtolower($val)=='null'){ $q.= "`$key` = NULL, ";}
			elseif(strtolower($val)=='now()'){ $q.= "`$key` = NOW(), ";}
			else{ $q.= $key . "='".$this->escape($val)."', "; }
		}
		
		$q = rtrim($q, ', ') . ' WHERE '.$where.';';		
		return $this->query($q);
	}
		
	function fetch_all_array($sql) {
		$query_id = $this->query($sql);
		$out = array();

		while ($row = $this->fetch_array($query_id)){
			$out[] = $row;
		}
	
		$this->free_result($query_id);
		return $out;
	}
	

	public function close() {
		if(!mysqli_close($this->link_id)){
			$this->error_display("Connection close failed.");
		}
	}
	
	public function escape($string) {
		if(get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		return mysqli_real_escape_string($this->link_id, $string);
	}
	
	public function query($sql){
		$this->query_id = @mysqli_query($this->link_id, $sql);

		if (!$this->query_id) {
			$this->error_display("<b>MySQL Query fail:</b> $sql");
		}
	
		$this->affected_rows = @mysqli_affected_rows($this->link_id);
		return $this->query_id;
	}
	
		
	public function fetch_array($query_id=-1) {
		if ($query_id!=-1) {
			$this->query_id=$query_id;
		}

		if (isset($this->query_id)) {
			$this->record = @mysqli_fetch_assoc($this->query_id);
		}else{
			$this->error_display("Invalid query. Records could not be fetched.");
		}
	
		if($this->record){
			$this->record = array_map("stripslashes", $this->record);
		
		}
		return $this->record;
	}	
	
	public function fetch_result($query_id){
		mysqli_data_seek($query_id, 0);
		$row = mysqli_fetch_array($query_id);
		return $row[0];
	}	
	
	public function fetch_array_object($sql) {
		$query_id = $this->query($sql);		
		$out = $this->fetch_result($query_id);	
		$this->free_result($query_id);
		return $out;
	}

	public function free_result($query_id=-1) {
		if ($query_id!=-1) {
			$this->query_id = $query_id;
		}
		mysqli_free_result($this->query_id);
	}	
		

    function num_rows($sql){
		return(mysqli_num_rows(mysqli_query($this->link_id, $sql)));
    }
	
	function total_record_count($sqltext){
		$out = $this->fetch_all_array($sqltext);
		return $out[0]["ttl"];
	}
	
	function mysqlquery($sql){
		mysqli_query($this->link_id, $sql);
	}
	
	function mysqlquery_ret($sql){
		mysqli_query($this->link_id, $sql);
		return mysqli_insert_id($this->link_id);
	}
	
	function error_display($msg='') {
	if($this->link_id>0){
		$this->error = mysqli_errno($this->link_id);
		$this->errno = mysqli_errno($this->link_id);
	}

	$this->error = mysqli_error($this->link_id);
	$this->errno = mysqli_errno($this->link_id);
	?>
		<table width="95%" align="center" border="1" cellspacing="0" cellpadding="4" class="htext">
		<tr>
          <th colspan=2>Database Error</th>
        </tr>
		<tr>
          <td align="left" valign="top">Message:</td>
          <td><?php echo $msg; ?></td>
        </tr>		
        <?php if(strlen($this->error)>0){?>
		<tr>
          <td align="left" valign="top">MySQL Error:</td>
          <td><?php echo $this->error; ?></td>
        </tr>
		<?php }?>
		<tr>
         <td align="left">Date:</td>
         <td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td>
        </tr> 
		<tr>
          <td align="left">Script:</td>
          <td><a href="<?php echo @$_SERVER['REQUEST_URI']; ?>"><?php echo @$_SERVER['REQUEST_URI']; ?></a></td>
        </tr>
        <?php if(strlen(@$_SERVER['HTTP_REFERER'])>0){?>
		<tr>
          <td align="left" valign="top">Referer:</td>
          <td><a href="<?php echo @$_SERVER['HTTP_REFERER']?>"><?php echo @$_SERVER['HTTP_REFERER']?></a></td>
        </tr>
		<?php }?>
		</table> 
	<?php
   }
	//PDO functions
	public function pdo_query($sql, $pdo_param = array(), $whreturnid = 0){	

		$pdo_param = json_decode(json_encode($pdo_param));
		$stmt = $this->pdo->prepare($sql);
		
		foreach($pdo_param AS $pdo_row) {
			$stmt->bindParam(':'.$pdo_row->id, $pdo_row->value, constant("PDO::$pdo_row->c"));
		}
		$stmt->execute();
		//print_r($this->pdo->errorInfo());
			
		if ($whreturnid == 1){
			return $this->pdo->lastInsertId();
		}
	}
   
	public function pdo_get_single_value($sql, $pdo_param = array()){
		$pdo_param = json_decode(json_encode($pdo_param));
		$stmt = $this->pdo->prepare($sql);
		foreach($pdo_param AS $pdo_row) {
			$stmt->bindParam(':'.$pdo_row->id, $pdo_row->value, constant("PDO::$pdo_row->c"));
		}		
		$stmt->execute();		
		return $stmt->fetchColumn();	
	}
   
	public function pdo_select($sql, $pdo_param = array()){
		$pdo_param = json_decode(json_encode($pdo_param));
		$stmt = $this->pdo->prepare($sql);
		foreach($pdo_param AS $pdo_row) {
			$stmt->bindParam(':'.$pdo_row->id, $pdo_row->value, constant("PDO::$pdo_row->c"));
		}		
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
		$stmt->closeCursor();
	}
   
	public function pdo_close() {
		$this->pdo = null;
	}
}
?>