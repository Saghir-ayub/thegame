<?php
$q = $_REQUEST["q"];

$currentDate = date("Y-m-d H:i:s");

$db = new SQLite3('enemyvalues.sq3');

$sql = "UPDATE UserChineseWords Set WordScore = WordScore+1, LastEntered = '" . $currentDate . "' WHERE WordID = '" . $q . "' AND UserID = 'bubness'";

$result = $db->query($sql);

unset($db);
