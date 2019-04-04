<?php
$frontend->go_to_account();
$cd = $_GET["cd"];
$result = $yachtclass->resetpassword_check($cd);
$row = $result[0];
$ms = $row['id'];
$suidd = htmlspecialchars($row['uid']);
$fname = htmlspecialchars($row['fname']);
$lname = htmlspecialchars($row['lname']);

$link_name = $atm1 = 'Reset Password';
$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;
$submitclass = "update-common";
include($bdr."includes/head.php");
?>
<div class="login-page">
    <div class="left-side">
        <form method="post" action="<?php echo $cm->folder_for_seo ;?>" id="reset_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
        <input type="hidden" value="<?php echo $cd; ?>" name="cd" />
        <input type="hidden" id="fcapi" name="fcapi" value="userresetp" />
          <ul class="form">
              <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
              <li>
                  <div class="errormessage">
                      <?php echo $_SESSION["fr_postmessage"]; ?>
                  </div>
              </li>
              <?php $_SESSION["fr_postmessage"] = ""; } ?>
              <li>
                  <p>Username: <?php echo $suidd; ?></p>
              </li>
              <li>
                  <label>
                  <p>Password</p>
                  <input type="password" id="d_password" name="d_password" class="input" />
                  </label>
              </li>
              <li>
                  <label>
                  <p>Confirm</p>
                  <input type="password" id="cd_password" name="cd_password" class="input" />
                  </label>
              </li>
              <li>
                  <input type="submit" value="Update" class="button" />
              </li>
          </ul>
        </form>
    </div>
</div>
<?php 
include($bdr."includes/foot.php");
?>