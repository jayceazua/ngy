<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Home Page Box";
$ms = round($_GET["id"], 0);
$status_id = 1;
$new_window = "n";

$link_type = 2;
$cms_style3_b = "visibility: visible;";
$cms_style3_a = "display: none; visibility: hidden;";

$sql="select * from tbl_homepage_box where id = '". $cm->filtertext($ms) ."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 	

$row = $result[0];
$id = $row['id'];	
$name = $cm->filtertextdisplay($row['name']);
$description = $cm->filtertextdisplay($row['description']);
$imagepath = $cm->filtertextdisplay($row['imagepath']);

$link_type = $row['link_type'];
$page_url = $cm->filtertextdisplay($row['page_url']);
$int_page_id = $row['int_page_id'];
$make_id = $row['make_id'];
$int_page_tp = $row['int_page_tp'];
$new_window = $row['new_window'];

$status_id = $row['status_id'];
$rank = $row['rank'];

if ($make_id > 0){
	$int_page_sel = $int_page_id . "_" . $make_id . "/!e";
}else{
	$int_page_sel = $int_page_id . "/!" . $int_page_tp;
}


if ($link_type == 1){
	$cms_style3_a = "visibility: visible;";
	$cms_style3_b = "display: none; visibility: hidden;";
}elseif ($link_type == 2){
	$cms_style3_b = "visibility: visible;";
	$cms_style3_a = "display: none; visibility: hidden;";
}else{
	$cms_style3_a = $cms_style3_b = "display: none; visibility: hidden;";
}		
$link_name .= " - " . $name;

$imw = $cm->box1_width;
$imh = $cm->box1_height;
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#home_box_ff").submit(function(){		
     if(!validate_text(document.ff.name,1,"Please enter Box Title")){
		return false;
	 }	 

	 if ($("#imgpath").length > 0) {
		if (!image_validation(document.ff.imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
	 }
	 
	 
	 if (document.getElementById("link_type").value == 1){
	 	  if(!validate_text(document.ff.page_url,1,"Please enter Link URL")){
	         return false;
	      }
	 }else if (document.getElementById("link_type").value == 2){
	 	  if(!validate_text(document.ff.int_page_sel,1,"Please select Page")){
	         return false;
	      }
	 }
	  
     return true; 
 });
 
 //link_type
 $("#link_type").change(function(){
 	 set_link_type = $(this).val();
	 if (set_link_type == 1){
	   Show_MQ("cmsoption3_a");
	   Hide_MQ("cmsoption3_b");
	 }else if (set_link_type == 2){
	   Show_MQ("cmsoption3_b");
	   Hide_MQ("cmsoption3_a");
	 }else{
	   Hide_MQ("cmsoption3_a");
       Hide_MQ("cmsoption3_b");  
	 }

 });     
});
</script>

<form method="post" action="homepage-box-sub.php" id="home_box_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
          <td width="35%" align="left"><span class="fontcolor3">* </span>Box Title:</td>
          <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <tr>
          <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Box Message:</td>
          <td width="" align="left" valign="top"><textarea name="description" id="description" rows="1" cols="1" class="textbox textbox_size6"><?php echo $description;?></textarea></td>
        </tr>
    
		<?php if ($imagepath != ""){ ?>
            <tr>
             <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
             <td width="" align="left" valign="top" class="tdpadding1">
             <img src="../homeboximage/<?php echo $imagepath; ?>" border="0" width="100" /><br />
             <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imagepath','tbl_homepage_box','id','homeboximage')">Delete Image</a>
             </td>
        	</tr>
        <?php }else{ ?>
            <tr>
             <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Image [w: <?php echo $imw; ?>px, h: <?php echo $imh ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
             <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox" size="65" /></td>
            </tr>
        <?php } ?>

    <tr>
    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link:</td>
    <td width="" align="left" valign="top" class="tdpadding1">
    <select name="link_type" id="link_type" class="htext">
     <option value="0" <?php if ($link_type == 0){ echo "selected";} ?>>None</option>
     <option value="1" <?php if ($link_type == 1){ echo "selected";} ?>>External Link</option>
     <option value="2" <?php if ($link_type == 2){ echo "selected";} ?>>Internal Link</option>
    </select>&nbsp;&nbsp;&nbsp;
    <span id="cmsoption3_a" style=" <?php echo $cms_style3_a; ?>"><span class="fontcolor3">* </span>Specify URL:&nbsp;&nbsp;<input type="text" id="page_url" name="page_url" class="inputbox inputbox_size4_b" value="<?php echo $page_url; ?>" /></span>
    <span id="cmsoption3_b" style=" <?php echo $cms_style3_b; ?>"><span class="fontcolor3">* </span>Select Page:&nbsp;&nbsp;    
    <select name="int_page_sel" id="int_page_sel" class="htext">
    	<optgroup label="Pages">
       	<?php
        //dynamic page = b
        $adm->get_page_combo($int_page_sel);
		?>
        </optgroup>
        
        <optgroup label="Local Inventory">
       	<?php
        //dynamic page = f		
        echo $adm->get_local_boat_combo($int_page_sel);
		?>
        </optgroup>
        
        <optgroup label="YachtCloser Model">
       	<?php
        //dynamic page = e
        echo $adm->get_yc_model_combo($int_page_sel);
		?>
        </optgroup>        
    </select>
    </span>
    </td>
   </tr>

    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">
            <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
        </td>
    </tr>
    </table>
</form>
<?php
include("foot.php");
?>