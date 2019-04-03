<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "y";
include("pageset.php");

$s = round($_GET ['s'], 0);
if ($s <= 0){ $s = 1; }

$sql = "select * from tbl_brokerage_services_email where id='".$s."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 
$row = $result[0];              
$id = $row['id'];
$agent_name = $row['agent_name'];
$agent_email = $row['agent_email'];
$agent_phone = $row['agent_phone'];
$agent_fax = $row['agent_fax'];
$company_name = $row['company_name'];
$company_email = $row['company_email'];
$siteadmin = $row['siteadmin'];
$othersend = $row['othersend'];
$cc_email = $row['cc_email'];
$file_data = $row['pdes'];
$link_name = $row['name'];
$email_subject = htmlspecialchars($row['email_subject']);

$otheremailclass = ' com_none';
if ($othersend == 1){ $otheremailclass = ''; }

$link_name = 'Email Content - ' . $link_name;
$icclass = "leftemailicon";
include("head.php");
?>	
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#email_ff").submit(function(){
		var id = $("#ms").val();
		id = parseInt(id);
		if (id == 3){
			if(!validate_text(document.ff.company_name,1,"Please enter Finance Company Name")){
				return false;
			}
			
			if(!validate_email(document.ff.company_email,1,"Please enter Finance Company Email Address")){
				return false;
			}
			
			if(!validate_email(document.ff.agent_email,0,"Please enter Agent Email Address")){
				return false;
			}
		}else{
			if(!validate_text(document.ff.agent_name,1,"Please enter Agent Name")){
				return false;
			}
			
			if(!validate_email(document.ff.agent_email,1,"Please enter Agent Email Address")){
				return false;
			}
		}	
		
		if(!validate_text(document.ff.email_subject,1,"Please enter Email Subject")){
			return false;
		}
		
		if (!editor_validation('file_data', 'Email Content')){ return false; }
    
		return true; 
	});
	
	//otheremlactive
	$(".otheremlactive").click(function(){
		if($(this).is(':checked')){
			$(".othereml").show();
		}else{
			$(".othereml").hide();
		}
	});

});
</script>


<?php if ($_SESSION["stt"] == "y"){ ?>
<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
    <tr>
    	<td width="100%" align="center"><span class="fontcolor3">Record Saved</span></td>
    </tr>
</table>    
<?php $_SESSION["stt"] = ""; } ?>

<form method="post" action="brokerage-services-email-sub.php" id="email_ff" name="ff" enctype="multipart/form-data">
<input type="hidden" value="<?php echo $id; ?>" id="ms" name="ms" />
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
<?php if ($id == 3){?>
	<tr>
        <td width="35%" align="left"><span class="mandt_color">* </span>Finance Company Name:</td>
        <td width="65%" align="left"><input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">* </span>Finance Company Email Address:</td>
        <td width="" align="left"><input type="text" id="company_email" name="company_email" value="<?php echo $company_email; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="35%" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Agent Name:</td>
        <td width="65%" align="left"><input type="text" id="agent_name" name="agent_name" value="<?php echo $agent_name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Agent Email Address:</td>
        <td width="" align="left"><input type="text" id="agent_email" name="agent_email" value="<?php echo $agent_email; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Agent Phone:</td>
        <td width="" align="left"><input type="text" id="agent_phone" name="agent_phone" value="<?php echo $agent_phone; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Agent Fax:</td>
        <td width="" align="left"><input type="text" id="agent_fax" name="agent_fax" value="<?php echo $agent_fax; ?>" class="inputbox inputbox_size4" /></td>
    </tr>    
    
    <tr>
        <td width="" align="left" colspan="2"><span class="subhead">&nbsp;&nbsp;Notifications</span></td>
    </tr>
    
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Site Admin</td>
        <td width="" align="left" valign="top" class="tdpadding1"><input type="checkbox" id="siteadmin" name="siteadmin" value="1" class="checkbox" <?php if ($siteadmin == 1){?>checked="checked"<?php } ?> /></td>    
    </tr>
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Other</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        	<input type="checkbox" id="othersend" name="othersend" value="1" class="checkbox otheremlactive" <?php if ($othersend == 1){?>checked="checked"<?php } ?> />
            <div class="othereml<?php echo $otheremailclass; ?>">
            	<p><strong>Other Email Address:</strong></p>
                <input type="text" id="cc_email" name="cc_email" value="<?php echo $cc_email; ?>" class="inputbox inputbox_size4" /><br />(seperated by comma and a space)
            </div>
        </td>    
    </tr>
    
    <tr>
        <td width="" align="left" colspan="2"><span class="subhead">&nbsp;&nbsp;Email Details</span></td>
    </tr>
    
<?php }else{ ?>
	<tr>
        <td width="35%" align="left"><span class="mandt_color">* </span>Agent Name:</td>
        <td width="65%" align="left"><input type="text" id="agent_name" name="agent_name" value="<?php echo $agent_name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">* </span>Agent Email Address:</td>
        <td width="" align="left"><input type="text" id="agent_email" name="agent_email" value="<?php echo $agent_email; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>CC Email Address:</td>
        <td width="" align="left"><input type="text" id="cc_email" name="cc_email" value="<?php echo $cc_email; ?>" class="inputbox inputbox_size4" /><br />(seperated by comma and a space)</td>
    </tr>
<?php } ?>
    
    <tr>
        <td width="" align="left"><span class="mandt_color">* </span>Email Subject:</td>
        <td width="" align="left"><input type="text" id="email_subject" name="email_subject" value="<?php echo $email_subject; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
    	<td width="" align="left" colspan="2"><span class="mandt_color">* </span>Email Content:</td>
    </tr>
    
    <tr>
        <td width="" align="center" colspan="2">
        <?php
        $editorstylepath = "";
        $editorextrastyle = "adminbodyclass_white text_area_white";
        $cm->display_editor("file_data", $sBasePath, "100%", 400, $file_data, $editorstylepath, $editorextrastyle);
        ?>
        </td>
    </tr>
    
    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="right">
        <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
        </td>
    </tr>
</table>
</form>
	
         
<?php
include("foot.php");
?>