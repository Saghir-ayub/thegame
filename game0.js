window.onload = function () {
  const reloading = sessionStorage.getItem('reloading')
  if (reloading) {
    sessionStorage.removeItem('reloading')
    restartLevel()
  }
}

function restartLevel () {
  const levelMinimum = localStorage.getItem('levelMinimum')
  const levelMaximum = localStorage.getItem('levelMaximum')
  game(levelMinimum, levelMaximum)
}

function game (minimumWord, maximumWord) {
  // removing any stored minimumWord
  localStorage.removeItem('levelMinimum')
  localStorage.removeItem('levelMaximum')

  // storing minimumWord in localstorage for restarts
  localStorage.setItem('levelMinimum', minimumWord)
  localStorage.setItem('levelMaximum', maximumWord)

  // displaying game canvas and input bar
  const levelChoice = (minimumWord / 25) + 1
  const background = '/thegame/backgrounds/background' + levelChoice + '.gif'
  document.body.style.background = 'url(' + background + ') no-repeat'
  document.body.style.backgroundSize = 'cover'
  document.getElementById('gameMenu').style.display = 'none'
  document.getElementById('pane').style.display = 'block'

  // initialising canvas and game
  const c = document.getElementById('pane').getContext('2d')
  c.canvas.width = window.innerWidth
  c.canvas.height = window.innerHeight
  let w = c.canvas.width
  let h = c.canvas.height
  let gameState = 'play'
  let introTimer = 5 // seconds
  let cmTID
  const timeStep = 50 // In milliseconds

  function windowResize () {
    c.canvas.width = window.innerWidth
    c.canvas.height = window.innerHeight
    w = c.canvas.width
    h = c.canvas.height
  };
  window.addEventListener('resize', windowResize)

  // timer for minion spawns, enemies, lives, combo, gameclock
  let respawnTimer = 0
  let lives = 10 // starting lives
  let liveslost = 0 // lives lost, based on words wrong
  let currentLives = 0 // lives-liveslost
  let correctWords = 0 // based on words right
  let enemiesAlive = 0 // current alive enemies
  const practiceTimerLength = 10 * (1000 / timeStep) // seconds on LHS * (ms->sec convert)
  let practiceTimeRemaining = practiceTimerLength
  let score = 0
  const difficultyLevel = difficulty
  let arrNumber = -1
  let roundCount = (maximumWord - minimumWord) > 30 ? 2600 : 0// === fix this ===
  let initialSpeed = 3 // === try convert to WPM ===
  const initialEnemySpawnRate = 100 //= ==need to convert to seconds ===
  let gameClock = 180 * (1000 / timeStep) // seconds on LHS * (ms->sec convert)
  const minutesConvertRate = 60 * (1000 / timeStep)
  const secondsConvertRate = minutesConvertRate / 60
  let minutes = gameClock / minutesConvertRate
  let seconds = (gameClock % minutesConvertRate) / secondsConvertRate

  // combos and boosters
  let wordStreak = 0
  let boostTimer = 0 // no boost active at start
  const boostLength = 10 // seconds
  const firstBoostCombo = 10
  const secondBoostCombo = 20
  const thirdBoostCombo = 30
  const boosters = [] // booster array
  let numBoosters = 0 // starting # of boosters

  // states for boost effects; none, slowMotionState, freezeMotionState, frenzyState
  let boosterState = 'none'

  // booster buttons, inactive state on start
  let slowMoBooster = false
  let freezerBooster = false
  let frenzyBooster = false

  // game mode specific variables
  switch (gameMode) {
    case 'Endurance':
      lives = 5
      break
    case 'Freestyle':
      lives = 99999
      break
    case 'Practice':
      lives = 3
      break
  }

  // sub arrays for different levels
  const wordID = passWordID.slice(minimumWord, maximumWord)
  const hsk1 = passHanzi.slice(minimumWord, maximumWord)
  const hsk1pin = passPinyin.slice(minimumWord, maximumWord)
  const hsk1eng = passEnglish.slice(minimumWord, maximumWord)

  // sub arrays for booster words (grant effects)
  const wordIDBooster = passWordID.slice(0, maximumWord)
  const hsk1Booster = passHanzi.slice(0, maximumWord)
  const hsk1pinBooster = passPinyin.slice(0, maximumWord)
  const hsk1engBooster = passPinyin.slice(0, maximumWord)

  // intro animation
  function Introduction () {
    clearTimeout(cmTID)

    c.save()
    c.fillStyle = 'rgb(255,255,255)'
    c.fillRect(0, 0, w, h)
    c.textAlign = 'center'
    c.fillStyle = 'rgba(0,0,0,' + (1 - 1 / Math.max(1, introTimer)) + ')'
    c.font = '50pt sans-serif'
    c.fillText(gameMode, w * 0.5, h * 0.3)
    c.fillText('Level ' + levelChoice, w * 0.5, h * 0.5)
    c.restore()
    if (introTimer > 0) {
      introTimer -= 0.2
      cmTID = setTimeout(Introduction, timeStep)
    } else {
      // display game and start running
      document.getElementById('inputtext').style.display = 'block'
      if (gameMode === 'Endurance' || gameMode === 'Freestyle') {
        document.getElementById('boosterImages').style.display = 'block'
      }
      updateAll()
    }
  }
  Introduction()

  // text box related variables
  let userInputText = ''
  const textinput = document.getElementById('inputtext')
  textinput.addEventListener('keypress', function (event) {
    if (event.key === 'Enter') {
      event.preventDefault()
      // document.getElementById("myBtn").click();
      userInputText = document.getElementById('inputtext').value
      document.getElementById('inputtext').value = ''
    }
  })

  const contBtn = document.getElementById('continuebtn')
  contBtn.addEventListener('click', function (event) {
    event.preventDefault()
    document.getElementById('continuebtn').click()
    gameState = 'play'
    document.getElementById('pauseinterface').style.display = 'none'
    updateAll()
  })

  const restBtn = document.getElementById('restartbtn')
  restBtn.addEventListener('click', function (event) {
    event.preventDefault()
    document.getElementById('restartbtn').click()
    document.getElementById('pauseinterface').style.display = 'none'
    sessionStorage.setItem('reloading', 'true')
    window.location.href = '/thegame/homepage.php'
  })

  // let pauseGame = document.getElementById("myBtn");
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      event.preventDefault()
      if (gameState === 'play') {
        gameState = 'paused'
        Pause()
      } else if (gameState === 'finish') {
        return
      } else {
        gameState = 'play'
        document.getElementById('pauseinterface').style.display = 'none'
        updateAll()
      }
    }
  })

  // slowMoButton activation
  document.addEventListener('keydown', function (event) {
    if (event.altKey && event.key === '1' && slowMoBooster) {
      event.preventDefault()
      boosterState = 'slowMotionState'
      document.getElementById('slowMoImage').style.opacity = '0.3'
      boostTimer = boostLength * 20
      slowMoBooster = false
    }
  })

  // freezerButton activation
  document.addEventListener('keydown', function (event) {
    if (event.altKey && event.key === '2' && freezerBooster) {
      event.preventDefault()
      boosterState = 'freezeMotionState'
      document.getElementById('freezeImage').style.opacity = '0.3'
      boostTimer = boostLength * 20
      freezerBooster = false
    }
  })

  // frenzyButton activation
  document.addEventListener('keydown', function (event) {
    if (event.altKey && event.key === '3' && frenzyBooster) {
      event.preventDefault()
      boosterState = 'frenzyState'
      document.getElementById('frenzyImage').style.opacity = '0.3'
      boostTimer = boostLength * 20
      lives += 5
      frenzyBooster = false
    }
  })

  function Pause () {
    setTimeout(() => {
      document.getElementById('pauseinterface').style.display = 'block'
      c.save()
      c.fillStyle = 'rgba(0,0,0,0.5)'
      c.fillRect(0, 0, w, h)
      c.restore()
    }, timeStep)
  }

  const homeBtn = document.getElementById('homebtn')
  homeBtn.addEventListener('click', function (event) {
    event.preventDefault()
    document.getElementById('homebtn').click()
    document.getElementById('pauseinterface').style.display = 'none'
    window.location.href = '/thegame/homepage.php'
  })

  function databaseScoreUpdate (dataName) {
    const data = dataName
    const hr = new XMLHttpRequest()
    const url = 'updateScore.php?q='
    hr.open('POST', url + data, true)
    hr.send()
  }

  function comboBoosterCheck () {
    if (wordStreak % firstBoostCombo === 0) {
      BoosterSpawn('slowmo')
    }
    if (wordStreak % secondBoostCombo === 0) {
      BoosterSpawn('freezer')
    }
    if (wordStreak % thirdBoostCombo === 0) {
      BoosterSpawn('freepts')
    }
  }

  function Enemy () {
    this.isize = 30
    this.maxDy = initialSpeed

    this.init = function () {
      this.size = this.isize
      const s = this.size
      this.x = Math.random() * (w * 0.8) + (w * 0.1)
      this.y = s / 2
      const maxDy = this.maxDy
      this.dy = initialSpeed
      // this.r = 52 + 183 * Math.abs(Math.sin(numEnemies*5*Math.PI/180));
      // this.g = 235 - 183 * Math.abs(Math.sin(numEnemies*5*Math.PI/180));
      // this.b = 52;
      this.r = 255
      this.g = 255
      this.b = 255
      this.opac = 1
      // this.maxDy = 3;
      this.passfail = ''
      if (roundCount < (hsk1.length)) {
        arrNumber += 1
        roundCount += 1
      } else { arrNumber = Math.floor(Math.random() * hsk1.length) }
      this.wordID = wordID[arrNumber]
      this.textb = hsk1[arrNumber]
      this.textpin = hsk1pin[arrNumber]
      this.texteng = hsk1eng[arrNumber]
      // practice mode overrides
      if (gameMode === 'Practice') {
        this.x = w / 2
        this.y = h / 2
        initialSpeed = 0
        this.size = s * 3
        practiceTimeRemaining = practiceTimerLength
      }
    }
    this.init()

    this.move = function () {
      const s = this.size
      // if (this.y > h){
      //     this.init();
      // }
      this.y += this.dy
      if (this.y + s / 2 > h && this.passfail === '') {
        this.passfail = 'fail'
        this.size = 0
        wordStreak = 0
      }
      if (gameMode === 'Practice' && practiceTimeRemaining <= 0 && this.passfail === '') {
        this.passfail = 'fail'
        wordStreak = 0
        practiceTimeRemaining = practiceTimerLength / 2
      }
    }

    this.draw = function () {
      if (userInputText === this.textb && this.passfail === '') {
        // this.size = 0;
        score += 1
        wordStreak += 1
        this.passfail = 'pass'
        if (gameMode !== 'Practice') {
          databaseScoreUpdate(this.wordID)
        }
        switch (gameMode) {
          case 'Freestyle':
          case 'Endurance':
            comboBoosterCheck()
            break
          case 'Practice':
            practiceTimeRemaining = 0
            break
        }
      }
      // ============ Death Animation ====================
      if (this.passfail === 'pass' && this.opac >= 0) {
        this.size -= 1
        this.opac = Math.max(0, this.opac - 0.1)
        this.y -= this.dy
      }
      const s = this.size
      const x = this.x
      const y = this.y
      const r = this.r
      const g = this.g
      const b = this.b
      const opac = this.opac
      let te = this.textb
      let teng

      if (this.passfail === 'fail' && gameMode === 'Practice' && this.opac > 0) {
        te = this.textpin
        teng = this.texteng
        if (practiceTimeRemaining <= 0) {
          this.opac = 0
        }
      }

      if (opac <= 0) { return }

      c.save()
      c.translate(x, y)
      c.fillStyle = 'rgba(' + r + ',' + g + ',' + b + ',' + opac + ')'
      c.font = s + 'pt sans-serif'
      c.textAlign = 'center'
      if (this.passfail === 'pass') { c.lineWidth = 0 } else { c.lineWidth = 3 };
      c.strokeText(te, -s / 2, s / 10)
      c.fillText(te, -s / 2, s / 10)
      if (teng) {
        c.save()
        c.font = s / 3 + 'pt sans-serif'
        c.strokeText(teng, -s / 2, s * 1.3)
        c.fillText(teng, -s / 2, s * 1.3)
        c.restore()
      }
      c.restore()
    }
  }

  function Booster (boosterType) {
    this.isize = 30
    this.maxDy = 1
    this.type = boosterType

    this.init = function () {
      this.size = this.isize
      const s = this.size
      this.x = Math.random() * (w * 0.8) + (w * 0.1)
      this.y = s / 2
      const maxDy = this.maxDy
      this.dy = maxDy
      this.opac = 1
      switch (this.type) {
        case 'freezer':
          this.r = 0
          this.g = 0
          this.b = 255
          break
        case 'slowmo':
          this.r = 0
          this.g = 255
          this.b = 0
          break
        case 'freepts':
          this.r = 255
          this.g = 0
          this.b = 0
          break
        default:
          this.r = 255
          this.g = 255
          this.b = 255
      }
      this.maxDy = 1
      this.passfail = ''
      BoostarrNumber = Math.floor(Math.random() * hsk1Booster.length)
      this.wordID = wordIDBooster[BoostarrNumber]
      this.textb = hsk1Booster[BoostarrNumber]
      this.textpin = hsk1pinBooster[BoostarrNumber]
      this.texteng = hsk1engBooster[BoostarrNumber]
    }
    this.init()

    this.move = function () {
      const s = this.size
      this.y += this.dy
      if (this.y + s / 2 > h && this.passfail === '') {
        this.passfail = 'fail'
        this.size = 0
      }
    }

    this.draw = function () {
      if (userInputText === this.textb && this.passfail === '') {
        score += 1
        wordStreak += 1
        this.passfail = 'pass'
        databaseScoreUpdate(this.wordID)
        comboBoosterCheck()

        switch (this.type) {
          case 'slowmo':
            document.getElementById('slowMoImage').style.opacity = '1'
            slowMoBooster = true
            break
          case 'freezer':
            document.getElementById('freezeImage').style.opacity = '1'
            freezerBooster = true
            break
          case 'freepts':
            document.getElementById('frenzyImage').style.opacity = '1'
            frenzyBooster = true
            break
        }
      }
      // ============ Death Animation ====================
      if (this.passfail === 'pass' && this.opac >= 0) {
        this.size -= 1
        this.opac = Math.max(0, this.opac - 0.1)
        this.y -= this.dy
      }
      const s = this.size
      const x = this.x
      const y = this.y
      const r = this.r
      const g = this.g
      const b = this.b
      const opac = this.opac
      const te = this.textb

      if (opac <= 0) { return }

      c.save()
      c.translate(x, y)
      c.fillStyle = 'rgba(' + r + ',' + g + ',' + b + ',' + opac + ')'
      c.font = s + 'pt sans-serif'
      c.textAlign = 'center'
      if (this.passfail === 'pass') { c.lineWidth = 0 } else { c.lineWidth = 3 };
      c.strokeText(te, -s / 2, s / 10)
      c.fillText(te, -s / 2, s / 10)
      c.restore()
    }
  }

  function EnemySpawn () {
    const f = new Enemy()
    numEnemies += 1
    enemies.push(f)
  }

  function BoosterSpawn (typeOfBooster) {
    const b = new Booster(typeOfBooster)
    numBoosters += 1
    boosters.push(b)
  }

  function IsGameOver () {
    switch (gameMode) {
      case 'Regular':
        if (currentLives <= 0) {
          gameState = 'finish'
          gameResult = 'fail'
        } else if (enemiesAlive === 0 && numEnemies >= 40) {
          gameState = 'finish'
          gameResult = 'pass'
        }
        break
      case 'Beat The Clock':
        if (currentLives <= 0) {
          gameState = 'finish'
          gameResult = 'fail'
        } else if (gameClock <= 0) {
          gameState = 'finish'
          gameResult = 'pass'
        }
        break
      case 'Endurance':
        if (currentLives <= 0) {
          gameState = 'finish'
          gameResult = 'fail'
        }
        break
      case 'Practice':
      case 'Race To Finish':
        if (currentLives <= 0) {
          gameState = 'finish'
          gameResult = 'fail'
        } else if (score >= 25) {
          gameState = 'finish'
          gameResult = 'pass'
        }
        break
      case 'Freestyle':
        break
    }
  }

  function SpawnEnemyCheck () {
    switch (gameMode) {
      case 'Regular':
        if (respawnTimer > initialEnemySpawnRate && numEnemies < 40) {
          respawnTimer = 0
          EnemySpawn()
        }
        break
      case 'Practice':
        if (enemiesAlive === 0 && practiceTimeRemaining === 0) {
          EnemySpawn()
        } else {
          practiceTimeRemaining -= 1
        }
        break
      case 'Race To Finish':
        if (enemiesAlive === 0) {
          EnemySpawn()
        }
        break
      default:
        if (respawnTimer > initialEnemySpawnRate) {
          respawnTimer = 0
          EnemySpawn()
        }
        break
    }
  }

  // starting number of enemies and starting array to be added to later
  const enemies = []
  let numEnemies = 1
  for (let i = 0; i < numEnemies; i++) {
    const e = new Enemy()
    enemies.push(e)
  }

  function updateAll () {
    // Move enemies
    for (let i = 0; i < numEnemies; i++) {
      const enemy = enemies[i]
      // implementing movement effects
      if (boostTimer <= 0) {
        boosterState = 'none'
        enemy.dy = initialSpeed
      } else if (boosterState === 'slowMotionState') {
        enemy.dy = initialSpeed * 0.5
      } else if (boosterState === 'freezeMotionState') {
        enemy.dy = 0
      }
      enemy.move()
    }

    // Move boosters
    for (let i = 0; i < numBoosters; i++) {
      const booster = boosters[i]
      booster.move()
    }

    // boost timer decrement
    if (boostTimer >= 0) { boostTimer -= 1 }

    // Erase everything, add redness depending on lives left
    c.clearRect(0, 0, w, h)
    const screenRedOpacity = liveslost / (liveslost + lives)
    c.save()
    c.fillStyle = 'rgba(255,0,0,' + screenRedOpacity * 0.3 + ')'
    c.fillRect(0, 0, w, h)
    c.restore()

    // resetting counted variables in for loop
    liveslost = 0
    const oldScore = score
    correctWords = 0
    enemiesAlive = 0

    // Redraw everything
    for (let i = 0; i < numEnemies; i++) {
      enemies[i].draw()
      if (enemies[i].passfail === 'fail') {
        liveslost += 1
      } else if (enemies[i].passfail === 'pass') {
        correctWords += 1
      } else if (enemies[i].passfail === '') {
        enemiesAlive += 1
      }
    }

    // Redraw boosters
    for (let i = 0; i < numBoosters; i++) {
      boosters[i].draw()
    }

    // reset InputText after all checks
    if (userInputText !== '' && oldScore === score) {
      wordStreak = 0
    }
    userInputText = ''

    // enemy spawn rate
    switch (boosterState) {
      case 'slowMotionState':
        respawnTimer += 0.5 * (1 + difficultyLevel / Math.max(1, 10 - numEnemies))// half speed
        break
      case 'freezeMotionState':
        respawnTimer += 0 // 0 speed
        break
      case 'frenzyState':
        respawnTimer += 1 + difficultyLevel / Math.max(1, 10 - numEnemies) // no change
        break
      default:
        respawnTimer += 1 + difficultyLevel / Math.max(1, 10 - numEnemies) // default speed
    }

    // check if conditions met to spawn enemy
    SpawnEnemyCheck()

    // check if the game is over
    currentLives = lives - liveslost
    IsGameOver()

    // Show the score/clock/lives/gamemode/level on the screen
    c.save()
    c.fillStyle = 'rgb(192,192,192)'
    c.fillRect(0, 0, w, 40)
    c.fillStyle = 'white'
    c.font = '20pt sans-serif'
    c.fillText('Lives remaining: ' + currentLives, w * 0.03, 30)
    if (gameMode === 'Beat The Clock') {
      gameClock -= 1
      minutes = Math.floor(gameClock / minutesConvertRate)
      seconds = ('0' + Math.floor((gameClock % minutesConvertRate) / secondsConvertRate)).slice(-2)
      c.fillText('Timer: ' + minutes + ':' + seconds, w * 0.25, 30)
    }
    c.fillText('Streak: ' + wordStreak, w * 0.7, 30)
    c.textAlign = 'center'
    c.fillText('Level ' + levelChoice, w * 0.5, 30)
    c.textAlign = 'end'
    if (gameMode === 'Regular' || gameMode === 'Practice') {
      c.fillText('Enemies: ' + (40 - numEnemies), w * 0.99, 30)
    } else {
      c.fillText('Score: ' + score, w * 0.97, 30)
    }
    // c.fillText('Combo Timer: '+boostTimer, w*0.2,30);
    c.restore()
    // Do it all again in a little while
    clearTimeout(cmTID)

    // Only animate if the game isn't over
    if (gameState === 'play') {
      cmTID = setTimeout(updateAll, timeStep)
    } else if (gameState === 'finish') {
      // window.location.href = "homepage.php";
      endGameresults()
    }
  }

  function endGameresults () {
    // screen clear
    c.clearRect(0, 0, w, h)

    // updating level score
    if (gameResult === 'pass') {
      alert('Congratulations you won!')
      const hr = new XMLHttpRequest()
      let url = 'updateLevelScore.php?level=' + levelChoice + '&mode=' + gameMode
      if (liveslost === 0) {
        url = 'updateLevelScore.php?diff=' + difficulty + '&level=' + levelChoice + '&mode=' + gameMode
      }
      hr.open('POST', url, true)
      hr.send()
    }

    // failure array
    const failedHanzi = []
    const failedPinyin = []
    const failedEnglish = []
    for (let i = 0; i < numEnemies; i++) {
      if (enemies[i].passfail === 'fail') {
        const failedHanziCell = enemies[i].textb
        const failedPinyinCell = enemies[i].textpin
        const failedEnglishCell = enemies[i].texteng
        failedHanzi.push(failedHanziCell)
        failedPinyin.push(failedPinyinCell)
        failedEnglish.push(failedEnglishCell)
      }
    }
    const table = document.createElement('table')
    // adding header to table
    const rowHeader = table.insertRow(-1)
    const firstCellHeader = rowHeader.insertCell(-1)
    firstCellHeader.appendChild(document.createTextNode('\xa0\xa0 Hanzi \xa0\xa0'))
    const secondCellHeader = rowHeader.insertCell(-1)
    secondCellHeader.appendChild(document.createTextNode('\xa0\xa0 Pinyin \xa0\xa0'))
    const thirdCellHeader = rowHeader.insertCell(-1)
    thirdCellHeader.appendChild(document.createTextNode('\xa0\xa0 English \xa0\xa0'))

    // populating the table with data from array
    for (let i = 0; i < failedHanzi.length; i++) {
      const row = table.insertRow(-1)
      const firstNameCell = row.insertCell(-1)
      firstNameCell.appendChild(document.createTextNode('\xa0\xa0' + failedHanzi[i] + '\xa0\xa0'))
      const secondNameCell = row.insertCell(-1)
      secondNameCell.appendChild(document.createTextNode('\xa0\xa0' + failedPinyin[i] + '\xa0\xa0'))
      const lastNameCell = row.insertCell(-1)
      lastNameCell.appendChild(document.createTextNode('\xa0\xa0' + failedEnglish[i] + '\xa0\xa0'))
    }

    // success array
    const successHanzi = []
    const successPinyin = []
    const successEnglish = []
    for (let i = 0; i < numEnemies; i++) {
      if (enemies[i].passfail === 'pass') {
        const successHanziCell = enemies[i].textb
        const successPinyinCell = enemies[i].textpin
        const successEnglishCell = enemies[i].texteng
        successHanzi.push(successHanziCell)
        successPinyin.push(successPinyinCell)
        successEnglish.push(successEnglishCell)
      }
    }
    const successTable = document.createElement('table')
    // adding header to table
    const successRowHeader = successTable.insertRow(-1)
    const successFirstCellHeader = successRowHeader.insertCell(-1)
    successFirstCellHeader.appendChild(document.createTextNode('\xa0\xa0 Hanzi \xa0\xa0'))
    const successSecondCellHeader = successRowHeader.insertCell(-1)
    successSecondCellHeader.appendChild(document.createTextNode('\xa0\xa0 Pinyin \xa0\xa0'))
    const successThirdCellHeader = successRowHeader.insertCell(-1)
    successThirdCellHeader.appendChild(document.createTextNode('\xa0\xa0 English \xa0\xa0'))

    // populating table with array
    for (let i = 0; i < successHanzi.length; i++) {
      const row = successTable.insertRow(-1)
      const firstNameCell = row.insertCell(-1)
      firstNameCell.appendChild(document.createTextNode('\xa0\xa0' + successHanzi[i] + '\xa0\xa0'))
      const secondNameCell = row.insertCell(-1)
      secondNameCell.appendChild(document.createTextNode('\xa0\xa0' + successPinyin[i] + '\xa0\xa0'))
      const lastNameCell = row.insertCell(-1)
      lastNameCell.appendChild(document.createTextNode('\xa0\xa0' + successEnglish[i] + '\xa0\xa0'))
    }
    document.getElementById('pane').style.display = 'none'
    const divs = document.getElementsByClassName('results')
    for (let i = 0; i < divs.length; i++) {
      divs[i].style.display = 'block'
    }
    document.getElementById('failureTable').appendChild(table)
    document.getElementById('successTable').appendChild(successTable)
    endgameDisplayLayout()
  }
}

// export {game, restartLevel }