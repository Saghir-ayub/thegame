<?php
$csvData = $_POST['csvData'];

$qDecode = json_decode($csvData, true);

$db = new SQLite3('enemyvalues.sq3');


// does it has 1 sql command
// for($k = 0; $k < count($qDecode); $k++) {
//     if ($k != 0) {$rows .= ", ";}
//     $rows .= "('" . $qDecode[$k]['Hanzi'] . "','" . $qDecode[$k]['Pinyin'] . "','" . $qDecode[$k]['English'] . "',
// '" . $qDecode[$k]['Group'] . "','" . $qDecode[$k]['Level'] . "')";
// }
// $sql = "INSERT INTO CustomWords (Hanzi, Pinyin, English, [Group], Level)
// VALUES ".$rows;

// does it individual sql command at a time
for ($k = 0; $k < count($qDecode); $k++) {
    $sql = 'INSERT INTO CustomWords (Hanzi, Pinyin, English, [Group], Level)
    VALUES ("' . $qDecode[$k]['Hanzi'] . '","' . $qDecode[$k]['Pinyin'] . '","' . $qDecode[$k]['English'] . '",
    "' . $qDecode[$k]['Group'] . '","' . $qDecode[$k]['Level'] . '")';

    $result = $db->query($sql);
}
unset($db);
