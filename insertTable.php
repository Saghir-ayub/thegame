<?php
$q = $_REQUEST["q"];

$qDecode = json_decode($q, true);

$db = new SQLite3('enemyvalues.sq3');

foreach ($qDecode[0] as $key => $value) {
    $keyNames[] =  $key;
}

$sql = "INSERT INTO CustomWords (Hanzi, Pinyin, English, [Group], Level)
VALUES ('" . $keyNames[4] . "','" . $qDecode[0]['Pinyin'] . "','" . $qDecode[0]['English'] . "',
'" . $qDecode[0]['Group'] . "','" . $qDecode[0]['Level'] . "')";

$result = $db->query($sql);

unset($db);
