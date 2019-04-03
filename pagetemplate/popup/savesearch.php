<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);
include("head.php");
?>
    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="savesearch_ff" name="ff">
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="savesearchsubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>

            <li>
                <p>Search Name</p>
                <input type="text" id="name" name="name" value="" class="input" />
            </li>
            <li>
                <button type="submit" class="button" value="Save">Save</button>
            </li>

        </ul>
    </form>

<?php
include("foot.php");
?>