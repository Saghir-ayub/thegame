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
$oneDayAgo = date("Y-m-d H:i:s", (strtotime('-1 day', strtotime($currentDate))));
$oneWeekAgo = date("Y-m-d H:i:s", (strtotime('-7 day', strtotime($currentDate))));
$twoWeeksAgo = date("Y-m-d H:i:s", (strtotime('-14 day', strtotime($currentDate))));
$oneMonthAgo = date("Y-m-d H:i:s", (strtotime('-30 day', strtotime($currentDate))));
$threeMonthsAgo = date("Y-m-d H:i:s", (strtotime('-90 day', strtotime($currentDate))));

// One day review
$sqlOneDayReview = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English FROM UserChineseWords
JOIN ChineseWords ON ChineseWords.ID = WordID
WHERE UserID = 'bubness'
AND LastEntered <= '" . $oneDayAgo . "'
AND WordScore BETWEEN 1 and 3
ORDER BY WordScore";
// One week review
$sqlOneWeekReview = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English FROM UserChineseWords
JOIN ChineseWords ON ChineseWords.ID = WordID
WHERE UserID = 'bubness'
AND LastEntered <= '" . $oneWeekAgo . "'
AND (WordScore BETWEEN 4 and 6 OR (FirstLearnt > '" . $twoWeeksAgo . "' AND FirstLearnt <= '" . $oneWeekAgo . "'))
ORDER BY WordScore";
// Two week review
$sqlTwoWeekReview = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English FROM UserChineseWords
JOIN ChineseWords ON ChineseWords.ID = WordID
WHERE UserID = 'bubness'
AND LastEntered <= '" . $twoWeeksAgo . "'
AND (WordScore BETWEEN 7 and 14 OR (FirstLearnt > '" . $oneMonthAgo . "' AND FirstLearnt <= '" . $twoWeeksAgo . "'))
ORDER BY WordScore";
// One month review
$sqlOneMonthReview = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English FROM UserChineseWords
JOIN ChineseWords ON ChineseWords.ID = WordID
WHERE UserID = 'bubness'
AND LastEntered <= '" . $oneMonthAgo . "'
AND (WordScore BETWEEN 15 and 40 OR (FirstLearnt > '" . $threeMonthsAgo . "' AND FirstLearnt <= '" . $oneMonthAgo . "'))
ORDER BY WordScore";
// Three month review
$sqlThreeMonthsReview = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English FROM UserChineseWords
JOIN ChineseWords ON ChineseWords.ID = WordID
WHERE UserID = 'bubness'
AND LastEntered <= '" . $threeMonthsAgo . "'
ORDER BY WordScore";

$resultOneDayReview = $db->query($sqlOneDayReview);
$resultOneWeekReview = $db->query($sqlOneWeekReview);
$resultTwoWeeksReview = $db->query($sqlTwoWeekReview);
$resultOneMonthReview = $db->query($sqlOneMonthReview);
$resultThreeMonthsReview = $db->query($sqlThreeMonthsReview);

while ($singleOneDayReview = $resultOneDayReview->fetchArray()) {
  $wordsForReview["oneDay"]["WordID"][] = $singleOneDayReview[0];
  $wordsForReview["oneDay"]["Hanzi"][] = $singleOneDayReview[1];
  $wordsForReview["oneDay"]["Pinyin"][] = $singleOneDayReview[2];
  $wordsForReview["oneDay"]["English"][] = $singleOneDayReview[3];
}

while ($singleOneWeekReview = $resultOneWeekReview->fetchArray()) {
  $wordsForReview["oneWeek"]["WordID"][] = $singleOneWeekReview[0];
  $wordsForReview["oneWeek"]["Hanzi"][] = $singleOneWeekReview[1];
  $wordsForReview["oneWeek"]["Pinyin"][] = $singleOneWeekReview[2];
  $wordsForReview["oneWeek"]["English"][] = $singleOneWeekReview[3];
}

while ($singleTwoWeeksReview = $resultTwoWeeksReview->fetchArray()) {
  $wordsForReview["twoWeek"]["WordID"][] = $singleTwoWeeksReview[0];
  $wordsForReview["twoWeek"]["Hanzi"][] = $singleTwoWeeksReview[1];
  $wordsForReview["twoWeek"]["Pinyin"][] = $singleTwoWeeksReview[2];
  $wordsForReview["twoWeek"]["English"][] = $singleTwoWeeksReview[3];
}

while ($singleOneMonthReview = $resultOneMonthReview->fetchArray()) {
  $wordsForReview["oneMonth"]["WordID"][] = $singleOneMonthReview[0];
  $wordsForReview["oneMonth"]["Hanzi"][] = $singleOneMonthReview[1];
  $wordsForReview["oneMonth"]["Pinyin"][] = $singleOneMonthReview[2];
  $wordsForReview["oneMonth"]["English"][] = $singleOneMonthReview[3];
}

while ($singleThreeMonthsReview = $resultThreeMonthsReview->fetchArray()) {
  $wordsForReview["threeMonth"]["WordID"][] = $singleThreeMonthsReview[0];
  $wordsForReview["threeMonth"]["Hanzi"][] = $singleThreeMonthsReview[1];
  $wordsForReview["threeMonth"]["Pinyin"][] = $singleThreeMonthsReview[2];
  $wordsForReview["threeMonth"]["English"][] = $singleThreeMonthsReview[3];
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
