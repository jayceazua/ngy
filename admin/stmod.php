<?php
include("common.php");
$adm->admin_login();
$q = round($_POST["q"], 0);
$t = round($_POST["t"], 0);

if ($t == 1){
    $vsql = "select id, name from tbl_user_account_status where";

    if ($q == 2){
        $vsql .= " id != 1 and";
    }

    if ($q == 3 OR $q == 4){
        $vsql .= " id != 5 and";
    }

    $vsql .= " status = 'y' and main_ac_status = 'y' order by rank";
    $vresult = $db->fetch_all_array($vsql);

    foreach( $vresult as $vrow ){
        $scat_id = $vrow['id'];
        $snme = $vrow['name'];
        $restext = $restext.$snme."/!".$scat_id."#";
    }
    echo $restext;
}
?>