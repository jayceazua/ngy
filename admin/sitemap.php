<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");
$link_name = "Sitemap";
$icclass = "leftsettingsicon";
include("head.php");
?>
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>   
        <td width="" align="center">
        Sitemap Link: <a class="htext" href="<?php echo $sitemapclass->get_site_map_url(); ?>"><?php echo $sitemapclass->get_site_map_url(); ?></a>
        </td>
    </tr>
    
    <tr>   
        <td width="" align="center">
        <button type="submit" class="butta ajaxsitemap"><span class="saveIcon butta-space">Generate Sitemap</span></button>
        </td>
    </tr>
</table>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $(".ajaxsitemap").click(function(){
	 
	 //overlay
	$(".waitdiv").show();
	$(".waitmessage").html('<p>Please wait....</p>');
	var b_sURL = "onlyadminajax.php";
	$.post(b_sURL,
	{					  
		az:25
	},
	function(data){
		data = $.parseJSON(data);
		displaytext = data.displaytext;
		$(".waitmessage").html("<p>" + displaytext + "</p>");
		messagedivhide();					
	});
 });

});
</script>
<?php
include("foot.php");
?>