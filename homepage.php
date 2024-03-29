<!DOCTYPE html>

<html>

<head>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="main.js"> </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <title>Home</title>
</head>

<body>
  <!-- querying the database to pull data-->
  <?php
  $db = new SQLite3('enemyvalues.sq3');
  // user setting queries
  $sql_Lang = "SELECT Language FROM UserSettings WHERE UserID = 'bubness'";
  $sql_Diff = "SELECT Difficulty FROM UserSettings WHERE UserID = 'bubness'";
  $sql_Mode = "SELECT Gamemode FROM UserSettings WHERE UserID = 'bubness'";
  $result_Lang = $db->query($sql_Lang);
  $result_Diff = $db->query($sql_Diff);
  $result_Mode = $db->query($sql_Mode);
  $currentLanguage = $result_Lang->fetchArray();
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
  $currentGamemode = $result_Mode->fetchArray();
  $currentGamemodeEmpty = str_replace(' ', '', $currentGamemode[0]);

  // words, levels, group queries
  $sql = "SELECT * FROM ChineseWords";
  $sqlLevels = "SELECT " . $currentGamemodeEmpty . "Score FROM UserChineseLevels WHERE UserID = 'bubness'";
  $sqlPerfect = "SELECT " . $currentGamemodeEmpty . "Perfect FROM UserChineseLevels WHERE UserID = 'bubness'";
  $sqlTotalLevels = "SELECT COUNT(DISTINCT ChineseWords.Level) FROM ChineseWords";
  $sqlGroupSeperatorCount = "SELECT count(DISTINCT ChineseWords.'level') FROM ChineseWords GROUP BY ChineseWords.'Group'";

  $result = $db->query($sql);
  $resultLevels = $db->query($sqlLevels);
  $resultPerfect = $db->query($sqlPerfect);
  $resultTotalWords = $db->query($sqlTotalLevels);
  $resultGroupSeperatorCount = $db->query($sqlGroupSeperatorCount);

  // Create an empty array
  $wordID = array();
  $hanziChars = array();
  $pinyinChars = array();
  $englishChars = array();
  $levelScores = array();
  $perfectStates = array();
  $groupSeperatorCount = array();
  $groupSeperatorPoints = array();
  $totalLevelsForGame = $resultTotalWords->fetchArray();

  while ($singlerow = $result->fetchArray()) {
    array_push($wordID, $singlerow[0]);
    array_push($hanziChars, $singlerow[1]);
    array_push($pinyinChars, $singlerow[2]);
    array_push($englishChars, $singlerow[3]);
  }
  while ($singleScore = $resultLevels->fetchArray()) {
    array_push($levelScores, $singleScore[0]);
  }
  while ($singlePerfect = $resultPerfect->fetchArray()) {
    array_push($perfectStates, $singlePerfect[0]);
  }
  // # of words per group
  while ($singleGroupSeperator = $resultGroupSeperatorCount->fetchArray()) {
    array_push($groupSeperatorCount, $singleGroupSeperator[0]);
  }
  // cumil # of words per group, for Game()
  $totalGroupsForGame = count($groupSeperatorCount);
  array_push($groupSeperatorPoints, 0);
  for ($p = 1; $p <= $totalGroupsForGame; $p++) {
    array_push($groupSeperatorPoints, $groupSeperatorPoints[$p - 1] + $groupSeperatorCount[$p - 1]);
  }
  $currentDate = date("Y-m-d H:i:s");
  $oneDayAgo = date("Y-m-d H:i:s", (strtotime('-1 day', strtotime($currentDate))));
  $oneWeekAgo = date("Y-m-d H:i:s", (strtotime('-7 day', strtotime($currentDate))));
  $twoWeeksAgo = date("Y-m-d H:i:s", (strtotime('-14 day', strtotime($currentDate))));
  $oneMonthAgo = date("Y-m-d H:i:s", (strtotime('-30 day', strtotime($currentDate))));
  $threeMonthsAgo = date("Y-m-d H:i:s", (strtotime('-90 day', strtotime($currentDate))));
  // One day review
  $sqlOneDayReview = "SELECT count(*) FROM UserChineseWords WHERE UserID = 'bubness'
  AND LastEntered <= '" . $oneDayAgo . "'
  AND WordScore BETWEEN 1 and 3";
  // One week review
  $sqlOneWeekReview = "SELECT count(*) FROM UserChineseWords WHERE UserID = 'bubness'
  AND LastEntered <= '" . $oneWeekAgo . "'
  AND (WordScore BETWEEN 4 and 6 OR (FirstLearnt > '" . $twoWeeksAgo . "' AND FirstLearnt <= '" . $oneWeekAgo . "'))";
  // Two week review
  $sqlTwoWeekReview = "SELECT count(*) FROM UserChineseWords WHERE UserID = 'bubness'
  AND LastEntered <= '" . $twoWeeksAgo . "'
  AND (WordScore BETWEEN 7 and 14 OR (FirstLearnt > '" . $oneMonthAgo . "' AND FirstLearnt <= '" . $twoWeeksAgo . "'))";
  // One month review
  $sqlOneMonthReview = "SELECT count(*) FROM UserChineseWords WHERE UserID = 'bubness'
  AND LastEntered <= '" . $oneMonthAgo . "'
  AND (WordScore BETWEEN 15 and 40 OR (FirstLearnt > '" . $threeMonthsAgo . "' AND FirstLearnt <= '" . $oneMonthAgo . "'))";
  // Three month review
  $sqlThreeMonthsReview = "SELECT count(*) FROM UserChineseWords WHERE UserID = 'bubness'
  AND LastEntered <= '" . $threeMonthsAgo . "'";

  $resultOneDayReview = $db->query($sqlOneDayReview);
  $resultOneWeekReview = $db->query($sqlOneWeekReview);
  $resultTwoWeeksReview = $db->query($sqlTwoWeekReview);
  $resultOneMonthReview = $db->query($sqlOneMonthReview);
  $resultThreeMonthsReview = $db->query($sqlThreeMonthsReview);

  $totalOneDayReviews = $resultOneDayReview->fetchArray();
  $totalOneWeekReviews = $resultOneWeekReview->fetchArray();
  $totalTwoWeeksReviews = $resultTwoWeeksReview->fetchArray();
  $totalOneMonthReviews = $resultOneMonthReview->fetchArray();
  $totalThreeMonthsReviews = $resultThreeMonthsReview->fetchArray();
  unset($db);
  ?>
  <!-- game canvas -->
  <canvas id=pane style="display:none"></canvas>
  <input id="inputtext" type="text" style="z-index:101; position:absolute; top:90%; left:30%;width:40%;display:none;" class="form-control" />

  <!-- module version new -->
  <script type="module">
    import {
      Game,
      restartLevel,
      endgameDisplayLayout,
      Booster,
      Enemy
    } from "./gameNew.js"
    const allLevels = document.getElementsByClassName("regular-levels")
    for (let i = 1; i <= allLevels.length; i++) {
      let mini = i
      let maxi = i
      allLevels[i - 1].addEventListener('click', function() {
        new Game(mini, maxi)
      })
    }
    const allGroups = document.getElementsByClassName("group-levels")
    for (let k = 0; k < allGroups.length; k++) {
      allGroups[k].addEventListener('click', function() {
        new Game(groupSeperatorPoints[k] + 1, groupSeperatorPoints[k + 1])
      })
    }

    // custom oldest entered
    const playLastTwentyFive = document.getElementById("playLastTwentyFive")
    const playLastFifty = document.getElementById("playLastFifty")
    const playLastHundred = document.getElementById("playLastHundred")

    playLastTwentyFive.addEventListener('click', function() {
      new Game(1, 1, "refreshMode")
    })
    playLastFifty.addEventListener('click', function() {
      new Game(1, 2, "refreshMode")
    })
    playLastHundred.addEventListener('click', function() {
      new Game(1, 3, "refreshMode")
    })

    // custom review mode
    let totalOneDay = <?php echo json_encode($totalOneDayReviews[0]); ?>;
    let totalOneWeek = <?php echo json_encode($totalOneWeekReviews[0]); ?>;
    let totalTwoWeeks = <?php echo json_encode($totalTwoWeeksReviews[0]); ?>;
    let totalOneMonth = <?php echo json_encode($totalOneMonthReviews[0]); ?>;
    let totalThreeMonths = <?php echo json_encode($totalThreeMonthsReviews[0]); ?>;
    const reviewBtns = document.getElementsByClassName("reviewBtn")
    const reviewMode = ["oneDay", "oneWeek", "twoWeeks", "oneMonth", "threeMonths"]
    const reviewAmnt = [totalOneDay, totalOneWeek, totalTwoWeeks, totalOneMonth, totalThreeMonths]
    for (let r = 0; r < reviewBtns.length; r++) {
      reviewBtns[r].addEventListener('click', function() {
        new Game(1, 1, "reviewMode", reviewMode[r])
      })
      if (reviewAmnt[r] === 0) {
        reviewBtns[r].disabled = true
      }
    }
  </script>

  <!-- CSV Parsing -->
  <div id="submitDataForm" style="display:none">
  <form id="myForm">
    <input type="file" id="csvFile" accept=".csv" />
    <br />
    <input type="submit" value="Submit" />
  </form>
  </div>
  <script>
    const myForm = document.getElementById("myForm");
    const csvFile = document.getElementById("csvFile");

    myForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const input = csvFile.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        const text = e.target.result
        const data = csvToArray(text)
        console.log(JSON.stringify(data))
        // const hr = new XMLHttpRequest()
        // const url = 'insertTable.php?q='
        // hr.open('POST', url + JSON.stringify(data), true)
        // hr.send()
        // alert("submitted")

        // for (let d = 0; d < data.length; d++) {
          let formData = new FormData()
          formData.append('csvData', JSON.stringify(data))
          fetch('insertTable.php', {
            method: "POST",
            body: formData,
          }).then(resp => {
            if (!resp.ok) {
              const err = new Error("Response wasn't okay");
              err.resp = resp;
              throw err;
            }
            console.log("Okay!");
          }).catch(err => {
            console.error(err);
          });
        };
      // }
      alert("submitted")
      reader.readAsText(input);
    });

    function csvToArray(str, delimiter = ",") {
      const headers = ["Hanzi", "Pinyin", "English", "Group", "Level"];
      const rows = str.split("\n");

      const arr = rows.map(function(row) {
        const values = row.split(delimiter);
        const el = headers.reduce(function(object, header, index) {
          object[header.replace("\r", "")] = values[index].replace("\r", "");
          return object;
        }, {});
        return el;
      });

      return arr;
    }
  </script>

  <!-- game menu/level select -->
  <div class="container-fluid" id="gameMenu" style="display:block; position:absolute; top:20%; left:0px;">
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='levelselectbtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" onclick="showLevels(); setButtonColours()" src="/thegame/buttons/levelselectbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 center levels">
        <!-- making all the groups -->
        <?php
        for ($k = 1; $k <= $totalGroupsForGame; $k++) {
          echo "<button class='btn-lg group-levels' style='width:14%;horizontal-align:left'> HSK" . $k . "</button>";
        }
        ?>
      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 levels button-wrapper">
        <!-- making all the levels -->
        <?php
        for ($j = 1; $j <= $totalLevelsForGame[0]; $j++) {
          echo "<button id='levelBtn" . $j . "' style='width:7%;horizontal-align:left' class='btn btn-lg reg-border regular-levels'>" . $j . "</button>";
        }
        ?>
      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='customgamebtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" onclick="showCustom()" src="/thegame/buttons/customgamebtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div id="customGame" class="col-lg-8 col-centered" style="display:none; background-Color:white;width:100px">
        <form method="POST" id="customGameForm">
          <label for="startLevel">Start level: </label>
          <select name="minimumLevel">
            <?php for ($i = 1; $i <= $totalLevelsForGame[0]; $i++) : ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
          <label for="startLevel">End level: </label>
          <select name="maximumLevel">
            <?php for ($i = 1; $i <= $totalLevelsForGame[0]; $i++) : ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
          <button class="btn btn-info" id="makeGameStart" type="submit">Start game</button>
        </form>
        <button id="playLastTwentyFive">Oldest 25</button>
        <button id="playLastFifty">Oldest 50</button>
        <button id="playLastHundred">Oldest 100</button>
        <button class="reviewBtn" id="dailyReviewBtn">Daily Reviews (<?php echo $totalOneDayReviews[0]; ?>)</button>
        <button class="reviewBtn" id="weeklyReviewBtn">Weekly Reviews (<?php echo $totalOneWeekReviews[0]; ?>)</button>
        <button class="reviewBtn" id="biweeklyReviewBtn">Fortnightly Reviews (<?php echo $totalTwoWeeksReviews[0]; ?>)</button>
        <button class="reviewBtn" id="monthlyReviewBtn">Monthly Reviews (<?php echo $totalOneMonthReviews[0]; ?>)</button>
      </div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='scoreboardbtn' onclick="location.href = '//localhost/thegame/scoreboard.php';" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/scoreboardbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='statisticsbtn' onclick="modalDisplay()" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/statisticsbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div><img width=340px height=85px id='settingsbtn' onclick="settingsDisplay()" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/settingsbtn.png"></div>
      <div class="col"></div>
    </div>
  </div>

  <!-- game settings -->
  <div id="settingsModal" class="text-center-class modal">
    <div class='modal-content' style="width:450px">
      <h1>Game Settings</h1>
      <form action="/thegame/user_settings.php" method="POST">
        <div style="height:10px"></div>
        <div class="form-group">
          <label for="difficultySet"><b>Difficulty</b></label>
          <select id="difficultySetting" class="form-select" name="difficultySetting">
            <option value="Easy">Easy</option>
            <option value="Normal">Normal</option>
            <option value="Hard">Hard</option>
            <option value="Nightmare">Nightmare</option>
          </select> (Current difficulty: <?php echo $currentDifficulty[0]; ?>)
        </div>
        <div class="form-group">
          <label for="languageSet"><b>Language</b></label>
          <select id="languageSetting" class="form-select" name="languageSetting">
            <option value="Chinese">Chinese</option>
            <option value="Arabic">Arabic</option>
            <option value="Japanese">Japanese</option>
          </select> (Current Language: <?php echo $currentLanguage[0]; ?>)
        </div>
        <div class="form-group">
          <label for="gamemodeSet"><b>Game mode</b></label>
          <select id="gamemodeSetting" class="form-select" name="gamemodeSetting">
            <option value="Regular">Regular</option>
            <option value="Endurance">Endurance</option>
            <option value="Beat The Clock">Beat The Clock</option>
            <option value="Race To Finish">Race To Finish</option>
            <option value="Practice">Practice</option>
          </select> (Current Game mode: <?php echo $currentGamemode[0]; ?>)
        </div>
        <div class="form-group">
          <label for="practiceModal"><b>Difficulty: </b></label>
          <img src="/thegame/buttons/leftarrow.png" style="width:5%; height:width">Easy<img src="/thegame/buttons/rightarrow.png" style="width:5%; height:width">
        </div>
        <button class="btn btn-info" type="submit" id="userSettingBtn">Submit changes</button>
      </form>
    </div>
  </div>
  <!-- scoreboard modal -->
  <div id='statisticsModal' class='modal'>
    <div class='modal-content' style="height:100%; width:85%; top:-10%; border:solid">
      <span id='statisticsClose' onclick=''>&times;</span>
      <iframe src=http://localhost/thegame/statistics.php style="height:90%; width:100%"></iframe>
    </div>
  </div>
  <!-- variables used by homepage.php and main.js -->
  <script type="text/javascript">
    let perfectStates = <?php echo json_encode($perfectStates); ?>;
    let totalLevelsForGame = <?php echo json_encode($totalLevelsForGame[0]); ?>;
    let levelScores = <?php echo json_encode($levelScores); ?>;
    let groupSeperatorPoints = <?php echo json_encode($groupSeperatorPoints); ?>;
  </script>

  <script>
    // colouring buttons
    for (let i = 0; i < totalLevelsForGame; i++)
      if (perfectStates[i] === 1) {
        // document.getElementById('levelBtn'+(i+1)).classList.add('easy-shadow');
        // document.getElementById('levelBtn'+(i+1)).style.borderColor = 'rgba(0,0,0,0)';
      } else if (perfectStates[i] === 2) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('normal-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    } else if (perfectStates[i] === 3) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('hard-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    } else if (perfectStates[i] === 4) {
      document.getElementById('levelBtn' + (i + 1)).classList.add('nightmare-shadow');
      document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
    }
  </script>
  <script type="module">
    import {
      Game
    } from './gameNew.js'
    // custom game start
    let formGame = document.getElementById("customGameForm")
    formGame.addEventListener("submit", function(event) {
      event.preventDefault()
      const startingLevelElement = formGame.elements["minimumLevel"]
      const endingLevelElement = formGame.elements["maximumLevel"]

      let startingLevelForGame = startingLevelElement.value
      let endingLevelForGame = endingLevelElement.value

      if (startingLevelForGame > endingLevelForGame) {
        new Game(startingLevelForGame, startingLevelForGame)
      } else {
        new Game(startingLevelForGame, endingLevelForGame)
      }
    })
  </script>
  <!-- canvas/game and input boxes -->
  <div id="boosterImages" style="display:none">
    <img id="slowMoImage" src="/thegame/booster/slowmo.png" style="z-index:101; position:absolute; top:30%; left:1%; display:block; width:6%;height:width; opacity:0.3">
    <img id="freezeImage" src="/thegame/booster/freeze.png" style="z-index:101; position:absolute; top:45%; left:1%; display:block; width:6%;height:width; opacity: 0.3">
    <img id="frenzyImage" src="/thegame/booster/frenzy.png" style="z-index:101; position:absolute; top:60%; left:1%; display:block; width:6%;height:width; opacity: 0.3">
  </div>


  <!-- level introduction -->
  <img id="introductionImage" src="/thegame/buttons/levelselector.png" style="display:none; position:absolute; top:250px; left:600px;">

  <!-- end of game "page"-->
  <div class="container-fluid results" style="display:none; color:white">
    <div class="row">
      <div class="col-lg-10 col-centered"><button id="resultsbtn" onclick="showhideresults()">Toggle</button></div>
    </div>
  </div>
  <div class="container-fluid results" style="display:none; color:white;margin-left:auto;margin-right:auto">
    <div id=failureTableDiv style="display:block">
      <table id=failureTable class="col-centered table-bordered table-striped" style="background-color:rgba(28,78,128,0.8);display:table; color:white; font-size:20pt;text-align:center;"></table>
    </div>
    <div id=successTableDiv style="display:none">
      <table id=successTable class="col-centered table-bordered table-striped" style="background-color:rgba(28,78,128,0.8); display:table; color:white; font-size:20pt; text-align:center;"></table>
    </div>
  </div>

  <!-- pause game interface -->
  <div id="pauseinterface" class="centerpause" style="display:none; position:absolute; top:100px; left:250px;">
    <div><img id="continuebtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/continuebtn.png"> </div>
    <div><img id="restartbtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/restartbtn.png"> </div>
    <div><img id="homebtn" onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/homebtn.png"> </div>
  </div>

</body>

</html>