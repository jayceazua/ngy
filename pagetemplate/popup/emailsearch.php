<?php
$id = $_REQUEST['id'];
$loggedin_member_id = $yachtclass->loggedin_member_id();
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);
$result = $yachtclass->check_save_search($id);
$row = $result[0];
$name = $row['name'];
include("head.php");
?>
<h1>Search: <?php echo $name; ?></h1>
    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="emailsearch_ff" name="ff">
        <input type="hidden" value="<?php echo $id; ?>" id="id" name="id" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="emailsearchsubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
                <?php $_SESSION["fr_postmessage"] = ""; } ?>

            <li>
                <p>Email Address</p>
                <input type="text" id="email" name="email" value="" class="input" />
            </li>
            <li>
                <button type="submit" class="button" value="Send">Send</button>
            </li>

        </ul>
    </form>

<?php
include("foot.php");
?>