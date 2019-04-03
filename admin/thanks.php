<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Thank You";
include("head.php");
?>	
</table>

<table cellspacing="0" cellpadding="0" border="0" width="95%">
	 <tr>
		<td width="100%" height="20"><img src="../images/spacer.gif" border="0"></td>
	</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="95%">
  <tr>
    <td class="whitetd" align="center" width="100%" height="40" valign="top">

	    <div id="main_con">
		  <?php echo $_SESSION["admin_thanks"]; ?>
		</div>
	
	</td>
  </tr>
</table>   	

<?php
$_SESSION["selected_claim_id"] = "";
include("foot.php");
?>