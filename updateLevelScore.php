<?php
$level = $_REQUEST["level"];

$mode = $_REQUEST["mode"];

$trimMode = str_replace(' ', '', $mode);

$db = new SQLite3('enemyvalues.sq3');

$sql = "UPDATE UserChineseLevels Set " . $trimMode . "Score = " . $trimMode . "Score+1 WHERE Level = " . $level . " AND UserID = 'bubness'";

$result = $db->query($sql);

if (isset($_REQUEST["diff"])) {
    $diff = $_REQUEST["diff"];
    
    $sql_2 = "SELECT " . $trimMode . "Perfect FROM UserChineseLevels WHERE Level = " . $level . " AND UserID = 'bubness'";

    $resultCurrentPerfect = $db->query($sql_2);

    $currentPerfectScore = $resultCurrentPerfect->fetchArray();

    if ($diff > $currentPerfectScore[0]) {
        $sql_3 = "UPDATE UserChineseLevels SET " . $trimMode . "Perfect = " . $diff . " WHERE Level = " . $level . " AND UserID = 'bubness'";
        $resultUpdatePerfect = $db->query($sql_3);
    }
}

unset($db);
