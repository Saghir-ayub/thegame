<?php
$db = new SQLite3('enemyvalues.sq3');
$sql_Diff = "SELECT Difficulty FROM UserSettings WHERE UserID = 'bubness'"; // NEED FOR PHP
$sql_Mode = "SELECT Gamemode FROM UserSettings WHERE UserID = 'bubness'"; // NEED FOR PHP
$sql = "SELECT * FROM ChineseWords";
$sqlTotalLevels = "SELECT COUNT(DISTINCT ChineseWords.Level) FROM ChineseWords"; // NEED FOR PHP
$sqlLevelSeperator = "SELECT count(*) from ChineseWords GROUP BY ChineseWords.'Level'";
$sqlGroupSeperator = "SELECT DISTINCT ChineseWords.ID FROM ChineseWords GROUP BY ChineseWords.'Group'";


$result_Diff = $db->query($sql_Diff); // NEED FOR PHP
$result_Mode = $db->query($sql_Mode); // NEED FOR PHP
$result = $db->query($sql);
$resultTotalWords = $db->query($sqlTotalLevels); // NEED FOR PHP
$resultLevelSeperator = $db->query($sqlLevelSeperator);
$resultGroupSeperator = $db->query($sqlGroupSeperator);


// Create an empty array
$wordID = array();
$hanziChars = array();
$pinyinChars = array();
$englishChars = array();
$totalLevelsForGame = $resultTotalWords->fetchArray(); // NEED FOR PHP
$levelSeperatorPoints = array();
$groupSeperatorPoints = array();

// Fill arrays/variables
// Difficulty
$currentDifficulty = $result_Diff->fetchArray(); // NEED FOR PHP
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
$currentGamemode = $result_Mode->fetchArray(); // NEED FOR PHP

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
array_push($levelCaps,0);
for($i = 1; $i <= count($levelSeperatorPoints); $i++){
    array_push($levelCaps, $levelCaps[$i-1] + $levelSeperatorPoints[$i-1]);
}
// Group seperator for game levels
while ($singleGroupSeperator = $resultGroupSeperator->fetchArray()) {
  array_push($groupSeperatorPoints, $singleGroupSeperator[0]);
}

// All variables into a JSON:
$arrOfArrays = array(
  'currentDifficulty' => $currentDifficultyValue, 'gamemode' => $currentGamemode[0],
  'wordID' => $wordID, 'hanziChars' => $hanziChars, 'pinyinChars' => $pinyinChars,
  'englishChars' => $englishChars, 'levelCaps' => $levelCaps,
  'groupSeperatorPoints' => $groupSeperatorPoints
);
echo json_encode($arrOfArrays);
unset($db);
