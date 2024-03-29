<!DOCTYPE html>

<html>

<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- <link rel="stylesheet" type="text/css" href="mystyle.css"> -->
	<title>Statistics</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav">
				<li class="nav-item">
					<h2 id="overallStats" class="statisticItem nav-link active" onclick="showStats(this.id)">Overall</h2>
				</li>
				<li class="nav-item">
					<h2 id="groupStats" class="statisticItem nav-link" onclick="showStats(this.id)">Groups</h2>
				</li>
				<li class="nav-item">
					<h2 id="levelStats" class="statisticItem nav-link" onclick="showStats(this.id)">Levels</h2>
				</li>
				<li class="nav-item">
					<h2 id="achievmentStats" class="statisticItem nav-link" onclick="showStats(this.id)">Achievments</h2>
				</li>
			</ul>
		</div>
	</nav>
	<script>
		function showStats(buttonID) {
			const navBarItems = document.getElementsByClassName("statisticItem")
			const navBarItemClicked = document.getElementById(buttonID)
			for (const navBarItem of navBarItems) {
				navBarItem.classList.remove("active")
			}
			navBarItemClicked.classList.add("active")

			const navBarDatas = document.getElementsByClassName("statisticData")
			const navBarDataClicked = document.getElementById(buttonID + "Data")
			for (const navBarData of navBarDatas) {
				navBarData.style.display = "none"
			}
			navBarDataClicked.style.display = "block"
		}
	</script>
	<?php
	$db = new SQLite3('enemyvalues.sq3');
	//total words known
	$sql_1 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore > 0 AND UserID = 'bubness'";
	$sql_2 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore >= 20 AND UserID = 'bubness'";
	$sql_3 = "SELECT count(*) as wordsKnown FROM UserChineseWords WHERE WordScore >= 40 AND UserID = 'bubness'";
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

	// Start date and time played
	$sql_timePlayed = "SELECT Playtime FROM UserInfo WHERE Username = 'bubness'";
	$timePlayed_dbquery = $db->query($sql_timePlayed);
	$timePlayedArray = $timePlayed_dbquery->fetchArray();
	$secondsInADay = 60 * 60 * 24;
	$secondsInAnHour = 60 * 60;
	$secondsInAMinute = 60;

	$daysPlayed = floor($timePlayedArray[0] / $secondsInADay);
	$hoursPlayed = floor(($timePlayedArray[0] - ($daysPlayed * $secondsInADay)) / $secondsInAnHour);
	$minutesPlayed = floor(($timePlayedArray[0] - ($daysPlayed * $secondsInADay) -
		($hoursPlayed * $secondsInAnHour)) / $secondsInAMinute);
	$secondsPlayed = floor($timePlayedArray[0] - ($daysPlayed * $secondsInADay) -
		($hoursPlayed * $secondsInAnHour) - ($minutesPlayed * $secondsInAMinute));

	$daysOrDay = ($daysPlayed == 0 || $daysPlayed > 1) ? " days " : " day ";
	$hoursOrHour = ($hoursPlayed == 0 || $hoursPlayed > 1) ? " hours " : " hour ";
	$minutesOrMinute = ($minutesPlayed == 0 || $minutesPlayed > 1) ? " minutes " : " minute ";
	$secondsOrSecond = ($secondsPlayed == 0 || $secondsPlayed > 1) ? " seconds" : " second";

	if ($daysPlayed > 0) {
		$actualTimePlayed = ($daysPlayed) . $daysOrDay . ($hoursPlayed) . $hoursOrHour . ($minutesPlayed) . $minutesOrMinute . "and " . ($secondsPlayed) . $secondsOrSecond;
	} else if ($hoursPlayed > 0) {
		$actualTimePlayed = ($hoursPlayed) . $hoursOrHour . ($minutesPlayed) . $minutesOrMinute . "and " . ($secondsPlayed) . $secondsOrSecond;
	} else if ($minutesPlayed > 0) {
		$actualTimePlayed = ($minutesPlayed) . $minutesOrMinute . "and " . ($secondsPlayed) .	 $secondsOrSecond;
	} else {
		$actualTimePlayed = ($secondsPlayed) . $secondsOrSecond;
	}

	unset($db);
	?>
	<div id="overallStatsData" class="statisticData">
		<?php
		echo "<p>Total Words known: " . $totalWordsKnown[0] . "/" . $totalWords[0] . "</p>
	<p>Words known well: " . $totalWordsKnownWell[0] . " (20+ score)</p>
	<p>Words Mastered: " . $totalWordsMastered[0] . " (40+ score)</p>
	<p>Total Score: " . $totalScoreAll[0] . "</p>
	<p><b>Total time played: " . $actualTimePlayed . "</b></p>";
		?>
	</div>

	<div id="groupStatsData" class="statisticData" style="display:none">
		<?php
		for ($i = 1; $i <= $levelGroups[0]; $i++) {
			echo "<p><b>HSK " . $i . ":</b></p>
 			<p> Total words known: " . $groupWordsKnownArray[$i - 1] . "/" . $groupWordsTotalArray[$i - 1] . "</p>
 			<p> Total score: " . $groupWordsTotalScoreArray[$i - 1] . "</p>
 			<p> Average score per word: " . $groupWordsAverageScoreArray[$i - 1] . "</p>";
		}
		?>
	</div>
</body>

</html>