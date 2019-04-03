<?php
$bdr = "../";
include("common.php");
$call_function = "b";
include("pageset.php");
$link_name = $atm1 = "Back-end Control Panel - Login";
$fullpage = "y";
include("head.php");
?>
<table border="0" width="700" cellspacing="0" cellpadding="0" align="center">
   <tr>
       <td class="titlebg"><?php echo strtoupper($link_name); ?></td>
   </tr>

   <tr>
        <td class="box_border_main">
            <?php if ($_SESSION["pass"] != ""){ ?>
            <table border="0" width="100%" cellspacing="1" cellpadding="5" align="center">
                <tr>
                    <td align="left" class="leftpadding_1 home_light"><span class="fontcolor3">
                            <?php if ($_SESSION["pass"]=="ww"){ ?>
                                Wrong User Id / Password
                            <?php $_SESSION["pass"] = ""; } ?>

                            <?php if ($_SESSION["pass"]=="www"){ ?>
                                Kindly Login
                            <?php $_SESSION["pass"] = ""; } ?>
                            
                            <?php if ($_SESSION["pass"]=="logtimeout"){ ?>
                                You have been logged out. Kindly Login.
                            <?php $_SESSION["pass"] = ""; } ?>

                            <?php if ($_SESSION["logg"]=="sss"){ ?>
                                Successfully Logged out
                            <?php $_SESSION["logg"] = ""; } ?>
                </span></td>
                </tr>
            </table>
            <?php } ?>

            <form method="post" action="loginvalid.php" id="admin_ff" name="ff">

                <table border="0" width="100%" cellspacing="0" cellpadding="8" align="center" class="bightext">

                    <tr>
                        <td width="130" align="left" class="leftpadding_1 home_light">Username:</td>
                        <td width="" align="left" class="home_light"><input type="text" name="t1" class="inputboxhome" value="<?php echo $saved_uid; ?>" /></td>
                    </tr>

                    <tr>
                        <td width="" align="left" class="leftpadding_1 home_light">Password:</td>
                        <td width="" align="left" class="home_light"><input type="password" name="t2" class="inputboxhome" /></td>
                    </tr>

                    <tr>
                        <td width="" align="left" class="leftpadding_1 home_light">&nbsp;</td>
                        <td align="left" class="home_light"><input type="checkbox" name="remember_me" value="y" <?php echo $remember; ?> />&nbsp;Remember Me</td>
                    </tr>

                    <tr>
                        <td width="" align="left" class="home_light">&nbsp;</td>
                        <td width="" colspan="2" align="left" class="home_light">
                            <button type="submit" class="buttabig"><span class="loginIcon butta-space">Sign-In</span></button>
                        </td>
                    </tr>
                </table>

            </form>
        </td>
   </tr>
   
   <tr>
      <td align="left" class="spacer2_pad"><a class="htext" target="_blank" href="<?php echo $cm->site_url; ?>/">Open Front-end</a></td>
   </tr>
</table>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#admin_ff").submit(function(){
            if(!validate_text(document.ff.t1,1,"Please enter Username")){
                return false;
            }

            if(!validate_text(document.ff.t2,1,"Please enter Password")){
                return false;
            }

            return true;
        });
    });
</script>
<?php
include("foot.php");
?>