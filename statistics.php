<!DOCTYPE html>

<html>

<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- <link rel="stylesheet" type="text/css" href="mystyle.css"> -->
	<title>Scoreboard</title>
</head>

<body>
	<?php
	$db = new SQLite3('enemyvalues.sq3');
	//total words known
	$sql_1 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore > 0 AND UserID = 'bubness'";
	$sql_2 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore >= 10 AND UserID = 'bubness'";
	$sql_3 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore >= 20 AND UserID = 'bubness'";
	$sql_4 = "SELECT count(*) as wordsKnown FROM ChineseWords";
	$sql_5 = "SELECT SUM(UserChineseWords.WordScore) FROM UserChineseWords WHERE UserID = 'bubness'";

	$result_1 = $db->query($sql_1);
	$result_2 = $db->query($sql_2);
	$result_3 = $db->query($sql_3);
	$result_4 = $db->query($sql_4);
	$result_5 = $db->query($sql_5);

	$totalWordsKnown = array();
	$totalWordsKnownWell = array();
	$totalWordsMastered = array();
	$totalWords = array();
	$totalScoreAll = array();

	$result_1_array = $result_1->fetchArray();
	$result_2_array = $result_2->fetchArray();
	$result_3_array = $result_3->fetchArray();
	$result_4_array = $result_4->fetchArray();
	$result_5_array = $result_5->fetchArray();

	array_push($totalWordsKnown, $result_1_array[0]);
	array_push($totalWordsKnownWell, $result_2_array[0]);
	array_push($totalWordsMastered, $result_3_array[0]);
	array_push($totalWords, $result_4_array[0]);
	array_push($totalScoreAll, $result_5_array[0]);

	echo "<p>Total Words known: " . $totalWordsKnown[0] . "/" . $totalWords[0] . "</p>
<p>Words known well: " . $totalWordsKnownWell[0] . " (10+ score)</p>
<p>Words Mastered: " . $totalWordsMastered[0] . " (20+ score)</p>
<p>Total Score: " . $totalScoreAll[0] . "</p>";

	//Group words known
	//Total groups：
	$sql_groups = "SELECT Count(DISTINCT ChineseWords.'Group') FROM ChineseWords";
	$totalGroups = $db->query($sql_groups);
	$levelGroups = array();
	$singlerow = $totalGroups->fetchArray();
	array_push($levelGroups, $singlerow[0]);

	//Group words known/total group words, total score per group, average score per word in group:
	$groupWordsKnownArray = array();
	$groupWordsTotalArray = array();
	$groupWordsTotalScoreArray = array();
	$groupWordsAverageScoreArray = array();

	$sql_h = "SELECT COUNT(UserChineseWords.WordScore) FROM UserChineseWords
	JOIN ChineseWords ON ChineseWords.ID = UserChineseWords.WordID
	WHERE WordScore > 0 AND UserID = 'bubness'
	GROUP BY ChineseWords.'Group'";

	$sql_h2 = "SELECT count(*) FROM ChineseWords Group By ChineseWords.'Group'";

	$sql_h3 = "SELECT SUM(UserChineseWords.WordScore) FROM UserChineseWords
	JOIN ChineseWords ON ChineseWords.ID = UserChineseWords.WordID
	WHERE UserID = 'bubness'
	GROUP BY ChineseWords.'Group'";
	
	$sql_h4 = "SELECT AVG(UserChineseWords.WordScore) FROM UserChineseWords
	JOIN ChineseWords ON ChineseWords.ID = UserChineseWords.WordID
	WHERE UserID = 'bubness'
	GROUP BY ChineseWords.'Group'";

	$wordsKnownGroup = $db->query($sql_h);
	$wordsTotalGroup = $db->query($sql_h2);
	$wordsTotalScore = $db->query($sql_h3);
	$wordsAvgScore = $db->query($sql_h4);

	while ($wordsKnownGroupRow = $wordsKnownGroup->fetchArray()) {
		array_push($groupWordsKnownArray, $wordsKnownGroupRow[0]);
	}

	while ($wordsTotalGroupRow = $wordsTotalGroup->fetchArray()) {
		array_push($groupWordsTotalArray, $wordsTotalGroupRow[0]);
	}

	while ($wordsScoreGroupRow = $wordsTotalScore->fetchArray()) {
		array_push($groupWordsTotalScoreArray, $wordsScoreGroupRow[0]);
	}

	while ($wordsAvgScoreGroupRow = $wordsAvgScore->fetchArray()) {
		array_push($groupWordsAverageScoreArray, $wordsAvgScoreGroupRow[0]);
	}

	for ($i = 1; $i <= $levelGroups[0]; $i++) {
		echo "<p><b>HSK " . $i . ":</b></p>
 <p> Total words known: " . $groupWordsKnownArray[$i - 1] . "/" . $groupWordsTotalArray[$i - 1] . "</p>
 <p> Total score: " . $groupWordsTotalScoreArray[$i - 1] . "</p>
 <p> Average score per word: " . $groupWordsAverageScoreArray[$i - 1] . "</p>";
	}
	unset($db);
	?>
</body>

</html>