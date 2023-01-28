<?php
$q = $_REQUEST["q"];

$db = new SQLite3('enemyvalues.sq3');

$sql = "UPDATE UserInfo Set Playtime = Playtime+15 WHERE Username = 'bubness'";

$result = $db->query($sql);

unset($db);