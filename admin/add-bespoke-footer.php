<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$ms = round($_GET["id"], 0);
$include_reglink = 0;
$status_id = 1;

if ($ms > 0){
	$sql="select * from tbl_bespoke_footer where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		$id = $row['id'];	
		$name = htmlspecialchars($row['name']);
		$include_reglink = $row['include_reglink'];
	    $status_id = $row['status_id'];
		$rank = $row['rank'];
		
		$link_name = "Modify Custom Footer";
	}else{
		$ms = 0;
	}
}
$icclass = "leftpageicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#bespoke_ff").submit(function(){
     if(!validate_text(document.ff.name,1,"Please enter Section Name")){
		return false;
	 }

	 var found = "<?php echo $found;?>";
	 found = eval(found);
	 if (found > 0){
    	 if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
    	     return false;
    	 }
	 }
	 
	 //bespoke link check
	 var total_bespoke_footer = $('#total_bespoke_footer').val();
	 for (i = 1; i <= total_bespoke_footer; i++){
		 if ($("#name" + i).length > 0){
			 if(!validate_text(document.getElementById("name" + i),1,"Please enter Link Caption")){
				 return false;
			 }
			 
			 var s_link_type = $('#link_type' + i).val();
			 if (s_link_type == 1){
				 if(!validate_text(document.getElementById("page_url" + i),1,"Please Specify URL")){
					 return false;
				 }
			 }
			 
			 if (s_link_type == 1){
				 if(!validate_text(document.getElementById("int_page_sel" + i),1,"Please select Page")){
					 return false;
				 }
			 }
		 }
	 }
	  
     return true; 
 });
 
   //add new
   $(".besaddrow").click(function(){
		var total_bespoke_footer = $('#total_bespoke_footer').val();
		total_bespoke_footer = eval(total_bespoke_footer);
		total_bespoke_footer = total_bespoke_footer + 1;
		
		b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			total_bespoke_footer:total_bespoke_footer,
			az:1
		},
		function(content){
			$("#besrowholder").append(content);
			$('#total_bespoke_footer').val(total_bespoke_footer);
		});
	});
	
	//delete
	$(".singleblock_box").on("click", ".bes_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
		
			var del_pointer = $(this).attr('yval');		
			var isdb = $(this).attr('isdb');
			var besid = $(this).attr('besid');
			$("tr").remove('.besrowind' + del_pointer);
			
			isdb = eval(isdb);
			if (isdb == 1){
				//record delete from db also using ajax			
				b_sURL = "onlyadminajax.php";
				$.post(b_sURL,
				{
					besid:besid,
					del_pointer:del_pointer,
					az:2
				});
			}
		
		}
	});	
	
	//sortable
	$( "#besrowholder tbody" ).sortable({
		update: function (event, ui) {			
			var section = $("#ms").val();
			var sortdata = $(this).sortable('serialize');
			
			b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{				
				section:section,
				data:sortdata,
				az:3
			});			
		}
	});
	
	//link type change
	$(".singleblock_box").on("click", ".link_type", function(){
		 cnum = $(this).attr('cnum');
		 set_link_type = $(this).val();
		 if (set_link_type == 1){
		   Show_MQ("cmsoption3_a" + cnum);
		   Hide_MQ("cmsoption3_b" + cnum);
		 }else if (set_link_type == 2){
		   Show_MQ("cmsoption3_b" + cnum);
		   Hide_MQ("cmsoption3_a" + cnum);
		 }else{
		   Hide_MQ("cmsoption3_a" + cnum);
		   Hide_MQ("cmsoption3_b" + cnum);  
		 }
	
	});

});
</script>

<form method="post" action="bespoke-footer-sub.php" id="bespoke_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
      <td width="35%" align="left"><span class="fontcolor3">* </span>Section Name:</td>
      <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size1" /></td>
    </tr>

    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext">
                <option value="">Select</option>
                <?php
                $adm->get_commonstatus_combo($status_id);
                ?>
            </select></td>
    </tr>

    <?php if ($ms > 0){ ?>
       <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
       </tr>
    <?php } ?>
    <tr>
        <td colspan="2">
        <div class="singleblock">
            <div class="singleblock_heading"><span>Links</span></div>
            <div class="singleblock_box">
                <?php
                    echo $adm->bespoke_footer_display_list($ms);			
                ?>            
            </div>
        </div>
        </td>
    </tr>
    
    <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Include Account Link at bottom?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input type="checkbox" id="include_reglink" name="include_reglink" value="1" <?php if ($include_reglink == 1){?> checked="checked"<?php } ?> class="checkbox" /></td>
       </tr>

    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">
            <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
            <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
        </td>
    </tr>
    </table>
</form>
<?php
include("foot.php");
?>