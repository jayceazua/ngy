<?php if ($fullpage != "y"){?>
        <table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
            </tr>
        </table>
        </td>
        <td width="20"><img border="0" src="images/sp.gif" alt="" /></td>
        <td width="5" valign="top" align="center" class="admincolor_dark"><img border="0" src="images/sp.gif" alt="" /></td>
        </tr>
    </table>

    <table width="1200" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td width="100%" height="5"><img border="0" src="images/sp.gif" alt="" /></td>
        </tr>

        <tr>
            <td width="100%" class="admincolor_dark"><div class="footercopyright">Copyright &copy; <?php echo date("Y"); ?> <?php echo $cm->sitename; ?></div></td>
        </tr>
    </table>
    <div class="loggedoutdiv fc_overlay">
        <div class="fc_overlay_container">
            <div id="logmessage" class="logmessage">
                <p>You have been logged out. Kindly log-in again.</p>
                <p><a href="<?php echo $cm->folder_for_seo; ?>admin/" class="buttabig"><span class="loginIcon butta-space">Log-In</span></a></p>
            </div>
        </div>
    </div>
    
    <div class="waitdiv fc_overlay">
        <div class="fc_overlay_container">
            <div id="waitmessage" class="waitmessage">
                
            </div>
        </div>
    </div>
    
    <script>
	//check inactivity
	setTimeout("checklogin()", 1000);
	</script>
<?php } ?>
    </body>
    </html>
<?php $db->close(); ?>