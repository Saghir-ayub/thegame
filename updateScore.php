<?php
$q = $_REQUEST["q"];

$db = new SQLite3('enemyvalues.sq3');

$sql = "UPDATE UserChineseWords Set WordScore = WordScore+1 WHERE WordID = '" . $q . "' AND UserID = 'bubness'";

$result = $db->query($sql);

unset($db);
