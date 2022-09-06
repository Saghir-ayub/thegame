<?php
$db = new SQLite3('enemyvalues.sq3');
$sql_Diff = "SELECT Difficulty FROM UserSettings WHERE UserID = 'bubness'";
$sql_Mode = "SELECT Gamemode FROM UserSettings WHERE UserID = 'bubness'";
$sql = "SELECT * FROM ChineseWords";
$sqlTotalLevels = "SELECT COUNT(DISTINCT ChineseWords.Level) FROM ChineseWords";
$sqlLevelSeperator = "SELECT count(*) from ChineseWords GROUP BY ChineseWords.'Level'";
$sqlGroupSeperator = "SELECT DISTINCT ChineseWords.ID FROM ChineseWords GROUP BY ChineseWords.'Group'";
$sqlDescDates = "SELECT ID, Hanzi, Pinyin, English, UserChineseWords.WordScore, UserChineseWords.FirstLearnt, UserChineseWords.LastEntered FROM ChineseWords
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
while ($singlerowDescDates = $resultDescDates->fetchArray()) {
  $descWordsByDate["WordID"][] = $singlerowDescDates[0];
  $descWordsByDate["Hanzi"][] = $singlerowDescDates[1];
  $descWordsByDate["Pinyin"][] = $singlerowDescDates[2];
  $descWordsByDate["English"][] = $singlerowDescDates[3];
  $descWordsByDate["WordScore"][] = $singlerowDescDates[4];
  $descWordsByDate["FirstLearnt"][] = $singlerowDescDates[5];
  $descWordsByDate["LastEntered"][] = $singlerowDescDates[6];
}

// review words
$wordsForReview = array();
$currentDate = date("Y-m-d H:i:s");

for ($k = 0; $k < count($descWordsByDate["FirstLearnt"]); $k++) {
  $learntDaysAgo = abs(strtotime($descWordsByDate["FirstLearnt"][$k]) - strtotime($currentDate)) / (60 * 60 * 24);
  $enteredDaysAgo = abs(strtotime($descWordsByDate["LastEntered"][$k]) - strtotime($currentDate)) / (60 * 60 * 24);
  $wordScore = $descWordsByDate["WordScore"][$k];

  if ($enteredDaysAgo >= 1) {
    if (($wordScore >= 1 && $wordScore < 4) || $learntDaysAgo < 3) {
      $wordsForReview["oneDay"]["WordID"][] = $descWordsByDate["WordID"][$k];
      $wordsForReview["oneDay"]["Hanzi"][] = $descWordsByDate["Hanzi"][$k];
      $wordsForReview["oneDay"]["Pinyin"][] = $descWordsByDate["Pinyin"][$k];
      $wordsForReview["oneDay"]["English"][] = $descWordsByDate["English"][$k];
    }
  }

  if ($enteredDaysAgo >= 7) {
    if (($wordScore >= 4 && $wordScore < 6) || ($learntDaysAgo >= 3 && $learntDaysAgo < 14)) {
      $wordsForReview["oneWeek"]["WordID"][] = $descWordsByDate["WordID"][$k];
      $wordsForReview["oneWeek"]["Hanzi"][] = $descWordsByDate["Hanzi"][$k];
      $wordsForReview["oneWeek"]["Pinyin"][] = $descWordsByDate["Pinyin"][$k];
      $wordsForReview["oneWeek"]["English"][] = $descWordsByDate["English"][$k];
    }
  }

  if ($enteredDaysAgo >= 14) {
    if (($wordScore >= 6 && $wordScore < 15) || ($learntDaysAgo >= 14 && $learntDaysAgo < 30)) {
      $wordsForReview["twoWeek"]["WordID"][] = $descWordsByDate["WordID"][$k];
      $wordsForReview["twoWeek"]["Hanzi"][] = $descWordsByDate["Hanzi"][$k];
      $wordsForReview["twoWeek"]["Pinyin"][] = $descWordsByDate["Pinyin"][$k];
      $wordsForReview["twoWeek"]["English"][] = $descWordsByDate["English"][$k];
    }
  }

  if ($enteredDaysAgo >= 30) {
    if (($wordScore >= 15 && $wordScore < 40) || ($learntDaysAgo >= 30 && $learntDaysAgo < 90)) {
      $wordsForReview["oneMonth"]["WordID"][] = $descWordsByDate["WordID"][$k];
      $wordsForReview["oneMonth"]["Hanzi"][] = $descWordsByDate["Hanzi"][$k];
      $wordsForReview["oneMonth"]["Pinyin"][] = $descWordsByDate["Pinyin"][$k];
      $wordsForReview["oneMonth"]["English"][] = $descWordsByDate["English"][$k];
    }
  }

  if ($enteredDaysAgo >= 90) {
    $wordsForReview["threeMonth"]["WordID"][] = $descWordsByDate["WordID"][$k];
    $wordsForReview["threeMonth"]["Hanzi"][] = $descWordsByDate["Hanzi"][$k];
    $wordsForReview["threeMonth"]["Pinyin"][] = $descWordsByDate["Pinyin"][$k];
    $wordsForReview["threeMonth"]["English"][] = $descWordsByDate["English"][$k];
  }
}

// All variables into a JSON:
$arrOfArrays = array(
  'currentDifficulty' => $currentDifficultyValue, 'gamemode' => $currentGamemode[0],
  'wordID' => $wordID, 'hanziChars' => $hanziChars, 'pinyinChars' => $pinyinChars,
  'englishChars' => $englishChars, 'levelCaps' => $levelCaps,
  'groupSeperatorPoints' => $groupSeperatorPoints, 'descWordByDate' => $descWordsByDate,
  'wordsForReview' => $wordsForReview
);
echo json_encode($arrOfArrays);
unset($db);
