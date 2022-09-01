<?php
$q = $_REQUEST["q"];

$currentDate = date("d/m/Y H:i:s");

$db = new SQLite3('enemyvalues.sq3');

$sql = "UPDATE UserChineseWords Set WordScore = WordScore+1 WHERE WordID = '" . $q . "' AND UserID = 'bubness'";

$sqlDate = "UPDATE UserChineseWords Set Date = '" . $currentDate . "' WHERE WordID = '" . $q . "' AND UserID = 'bubness'";

$result = $db->query($sql);

$resultDate = $db->query($sqlDate);

unset($db);
