<?php
$db = new SQLite3('enemyvalues.sq3');
$sql_Diff = "SELECT Difficulty FROM UserSettings WHERE UserID = 'bubness'";
$sql_Mode = "SELECT Gamemode FROM UserSettings WHERE UserID = 'bubness'";
$sql = "SELECT * FROM ChineseWords";
$sqlTotalLevels = "SELECT COUNT(DISTINCT ChineseWords.Level) FROM ChineseWords";
$sqlLevelSeperator = "SELECT count(*) from ChineseWords GROUP BY ChineseWords.'Level'";
$sqlGroupSeperator = "SELECT DISTINCT ChineseWords.ID FROM ChineseWords GROUP BY ChineseWords.'Group'";
$sqlDescDates = "SELECT ID, Hanzi, Pinyin, English FROM ChineseWords 
JOIN UserChineseWords ON UserChineseWords.WordID = ChineseWords.ID
WHERE UserChineseWords.UserID = 'bubness' AND UserChineseWords.LastEntered IS NOT NULL ORDER BY LastEntered";


$result_Diff = $db->query($sql_Diff);
$result_Mode = $db->query($sql_Mode);
$result = $db->query($sql);
$resultTotalWords = $db->query($sqlTotalLevels);
$resultLevelSeperator = $db->query($sqlLevelSeperator);
$resultGroupSeperator = $db->query($sqlGroupSeperator);
$resultDescDates = $db->query($sqlDescDates);


// Create an empty array
$wordID = array();
$hanziChars = array();
$pinyinChars = array();
$englishChars = array();
$totalLevelsForGame = $resultTotalWords->fetchArray();
$levelSeperatorPoints = array();
$groupSeperatorPoints = array();
$descWordsByDate = array();

// Fill arrays/variables
// Difficulty
$currentDifficulty = $result_Diff->fetchArray();
switch ($currentDifficulty[0]) {
  case 'Easy':
    $currentDifficultyValue = 1;
    break;
  case 'Normal':
    $currentDifficultyValue = 2;
    break;
  case 'Hard':
    $currentDifficultyValue = 3;
    break;
  case 'Nightmare':
    $currentDifficultyValue = 4;
    break;
  default:
    $currentDifficultyValue = 1;
}

// Gamemode
$currentGamemode = $result_Mode->fetchArray();

// Word information, ID/Foreign/Romanized/English
while ($singlerow = $result->fetchArray()) {
  array_push($wordID, $singlerow[0]);
  array_push($hanziChars, $singlerow[1]);
  array_push($pinyinChars, $singlerow[2]);
  array_push($englishChars, $singlerow[3]);
}

// Words between each level
while ($singleLevelSeperator = $resultLevelSeperator->fetchArray()) {
  array_push($levelSeperatorPoints, $singleLevelSeperator[0]);
}
// Level seperator points cumil counting level seperator points
$levelCaps = array();
array_push($levelCaps, 0);
for ($i = 1; $i <= count($levelSeperatorPoints); $i++) {
  array_push($levelCaps, $levelCaps[$i - 1] + $levelSeperatorPoints[$i - 1]);
}
// Group seperator for game levels
while ($singleGroupSeperator = $resultGroupSeperator->fetchArray()) {
  array_push($groupSeperatorPoints, $singleGroupSeperator[0]);
}
// Words done correctly, descending order from date last entered correctly
$descWordsByDateID = array();
$descWordsByDateHanzi = array();
$descWordsByDatePinyin = array();
$descWordsByDateEnglish = array();
while ($singlerowDescDates = $resultDescDates->fetchArray()) {
  array_push($descWordsByDateID, $singlerowDescDates[0]);
  array_push($descWordsByDateHanzi, $singlerowDescDates[1]);
  array_push($descWordsByDatePinyin, $singlerowDescDates[2]);
  array_push($descWordsByDateEnglish, $singlerowDescDates[3]);
}
// pushing words done correctly by date into a single array
array_push($descWordsByDate, $descWordsByDateID);
array_push($descWordsByDate, $descWordsByDateHanzi);
array_push($descWordsByDate, $descWordsByDatePinyin);
array_push($descWordsByDate, $descWordsByDateEnglish);

// 1 day review words

// 1 week review words

// 1 month review words

// 6 month review words

// All variables into a JSON:
$arrOfArrays = array(
  'currentDifficulty' => $currentDifficultyValue, 'gamemode' => $currentGamemode[0],
  'wordID' => $wordID, 'hanziChars' => $hanziChars, 'pinyinChars' => $pinyinChars,
  'englishChars' => $englishChars, 'levelCaps' => $levelCaps,
  'groupSeperatorPoints' => $groupSeperatorPoints, 'descWordByDate' => $descWordsByDate
);
echo json_encode($arrOfArrays);
unset($db);
