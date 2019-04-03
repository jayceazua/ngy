<?php
//-----------
?>
<script language="javascript" type="text/javascript">
function def_mata(){
	var ms = $("#ms").val();
		ms = parseInt(ms); 
	
	var makeid = $("#connected_manufacturer_id").val();
		makeid = parseInt(makeid);
  
  
  
	var mta_ck = true;
	if (document.ff.def_meta.checked){
		if (ms > 0){
			mta_ck = confirm ("You are about to overwrite the existing values for meta information with default values. Are you sure?");
		}
		
		if (mta_ck){ 
			if (makeid > 0){
				var companyname = "<?php echo $cm->sitename; ?>";				
				var makename = $("#connected_manufacturer_id option:selected").text();
				var mkm1 = "<?php echo $mk_m1; ?>";
				mkm1 = mkm1.replace("#make#", makename);
				mkm1 = mkm1.replace("#companyname#", companyname);
				
				var mkm2 = "<?php echo $mk_m2; ?>";
				mkm2 = mkm2.replace("#make#", makename);
				mkm2 = mkm2.replace("#companyname#", companyname);
				
				var mkm3 = "<?php echo $mk_m3; ?>";
				mkm3 = mkm3.replace("#make#", makename);
				mkm3 = mkm3.replace("#companyname#", companyname);
				
				$("#m1").val(mkm1);
				$("#m2").val(mkm2);
				$("#m3").val(mkm3);
			}else{			
				$("#m1").val("<?php echo $d_m1; ?>");
				$("#m2").val("<?php echo $d_m2; ?>");
				$("#m3").val("<?php echo $d_m3; ?>");
			}
		}else{
			document.ff.def_meta.checked = false;
		}
	
	}else{
		document.getElementById("m1").value = "<?php echo $m1; ?>";
		document.getElementById("m2").value = "<?php echo $m2; ?>";
		document.getElementById("m3").value = "<?php echo $m3; ?>";
	}
}
</script>