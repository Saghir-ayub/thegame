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
  $sqlLevelSeperator = "SELECT DISTINCT ChineseWords.ID FROM ChineseWords GROUP BY ChineseWords.'Level'";
  $sqlGroupSeperator = "SELECT DISTINCT ChineseWords.ID FROM ChineseWords GROUP BY ChineseWords.'Group'";

  $result = $db->query($sql);
  $resultLevels = $db->query($sqlLevels);
  $resultPerfect = $db->query($sqlPerfect);
  $resultTotalWords = $db->query($sqlTotalLevels);
  $resultLevelSeperator = $db->query($sqlLevelSeperator);
  $resultGroupSeperator = $db->query($sqlGroupSeperator);

  // Create an empty array
  $wordID = array();
  $hanziChars = array();
  $pinyinChars = array();
  $englishChars = array();
  $levelScores = array();
  $perfectStates = array();
  $totalLevelsForGame = $resultTotalWords->fetchArray();
  $levelSeperatorPoints = array();
  $groupSeperatorPoints = array();

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
  while ($singleLevelSeperator = $resultLevelSeperator->fetchArray()) {
    array_push($levelSeperatorPoints, $singleLevelSeperator[0]);
  }
  while ($singleGroupSeperator = $resultGroupSeperator->fetchArray()) {
    array_push($groupSeperatorPoints, $singleGroupSeperator[0]);
  }
  unset($db);
  ?>
  <!-- game canvas -->
  <canvas id=pane style="display:none"></canvas>
  <input id="inputtext" type="text" style="z-index:101; position:absolute; top:90%; left:30%;width:40%;display:none;" class="form-control" />

  <!-- module version new -->
  <script type="module">
    import {
      game,
      restartLevel
    } from "./game0.js"
    const allLevels = document.getElementsByClassName("regular-levels")
    for (let i = 1; i < allLevels.length; i++) {
      let mini = 25 * (i - 1)
      let maxi = 25 + 25 * (i - 1)
      allLevels[i - 1].addEventListener('click', function() {
        game(mini, maxi)
      })
    }
  </script>

  <!-- old versions
  <script>
    const allLevels = document.getElementsByClassName("regular-levels")
    for (let i = 1; i < allLevels.length; i++) {
      let mini = 25 * (i - 1)
      let maxi = 25 + 25 * (i - 1)
      allLevels[i - 1].addEventListener('click', function() {
        const game = new game(mini, maxi)
      })
    }
  </script>

  <script src="game0.js">

  </script> -->

  <!-- game menu/level select -->
  <div class="container-fluid" id="gameMenu" style="display:block; position:absolute; top:30%; left:0px;">
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='levelselectbtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" onclick="showLevels(); setButtonColours()" src="/thegame/buttons/levelselectbtn.png"></div>
      <div class="col"></div>
    </div>
    <div class="row">
      <div class="col"></div>
      <div class="col-lg-8 center levels">
        <button class="btn-lg" onclick="game(0,149)">HSK 1 (1-6)</button>
        <button class="btn-lg" onclick="game(150,299)">HSK 2 (7-12)</button>
        <button class="btn-lg" onclick="game(300,599)">HSK 3 (13-24)</button>
        <button class="btn-lg" onclick="game(600,1199)">HSK 4 (25-48)</button>
        <button class="btn-lg" onclick="game(1200,2500)">HSK 5 (48-100)</button>
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
      <div class="col-lg-8 col-centered"><img width=340px height=85px id='customgamebtn' onmouseover="imagechange(this.id)" onmouseout="imagechangeback(this.id)" src="/thegame/buttons/customgamebtn.png"></div>
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
    <div class='modal-content'>
      <span id='statisticsClose' onclick=''>&times;</span>
      <iframe src=http://localhost/thegame/statistics.php style="height:400px"></iframe>
    </div>
  </div>
  <!-- echoing database values into variables -->
  <script type="text/javascript">
    let passWordID = <?php echo json_encode($wordID); ?>;
    let passHanzi = <?php echo json_encode($hanziChars); ?>;
    let passPinyin = <?php echo json_encode($pinyinChars); ?>;
    let passEnglish = <?php echo json_encode($englishChars); ?>;
    let levelScores = <?php echo json_encode($levelScores); ?>;
    let perfectStates = <?php echo json_encode($perfectStates); ?>;
    let totalLevelsForGame = <?php echo json_encode($totalLevelsForGame[0]); ?>;
    let difficulty = <?php echo json_encode($currentDifficultyValue); ?>;
    let gameMode = <?php echo json_encode($currentGamemode[0]); ?>;
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