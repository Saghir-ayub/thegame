function endgameDisplayLayout() {
    document.getElementById('inputtext').style.display = 'none'
    document.getElementById('slowMoImage').style.opacity = '0.3'
    document.getElementById('freezeImage').style.opacity = '0.3'
    document.getElementById('frenzyImage').style.opacity = '0.3'
    document.getElementById('boosterImages').style.display = 'none'
}

function showhideresults() {
    const failureTable = document.getElementById('failureTableDiv')
    const successTable = document.getElementById('successTableDiv')
    if (failureTable.style.display === 'block') {
        failureTable.style.display = 'none'
        successTable.style.display = 'block'
    } else {
        failureTable.style.display = 'block'
        successTable.style.display = 'none'
    }
}

function imagechange(hoverID) {
    document.getElementById(hoverID).src = '/thegame/buttons/' + hoverID + 'flipped.png'
}

function imagechangeback(hoverID) {
    document.getElementById(hoverID).src = '/thegame/buttons/' + hoverID + '.png'
}

function showLevels() {
    const boxes = document.getElementsByClassName('levels')
    for (const box of boxes) {
        if (box.style.display === 'block') {
            box.style.display = 'none'
        } else box.style.display = 'block'
    }
}

function changeDifficultyImage(clickID) {
    if (document.getElementById(clickID).src === 'http://localhost/thegame/buttons/' + clickID + 'flipped.png') {

    } else {
        flipAllToBase()
        document.getElementById(clickID).src = '/thegame/buttons/' + clickID + 'flipped.png'
    }
}

function flipAllToBase() {
    document.getElementById('easybtn').src = '/thegame/buttons/easybtn.png'
    document.getElementById('normalbtn').src = '/thegame/buttons/normalbtn.png'
    document.getElementById('hardbtn').src = '/thegame/buttons/hardbtn.png'
    document.getElementById('nightmarebtn').src = '/thegame/buttons/nightmarebtn.png'
}

function setButtonColours() {
    for (let j = 1; j <= totalLevelsForGame; j++) {
        if (levelScores[j - 1] >= 1 && levelScores[j - 1] < 5) {
            document.getElementById('levelBtn' + j).style.backgroundColor = 'rgb(209, ' + levelScores[j - 1] * 42 + ', 0)'
            document.getElementById('levelBtn' + j).style.color = 'white'
        } else if (levelScores[j - 1] >= 5 && levelScores[j - 1] < 10) {
            document.getElementById('levelBtn' + j).style.backgroundColor = 'rgb(' + (209 - (levelScores[j - 1] - 5) * 42) + ', 209, 0)'
            document.getElementById('levelBtn' + j).style.color = 'white'
        } else if (levelScores[j - 1] >= 10) {
            document.getElementById('levelBtn' + j).style.backgroundColor = 'black'
            document.getElementById('levelBtn' + j).style.color = 'gold'
        } else {
            document.getElementById('levelBtn' + j).classList.add('btn-secondary')
            document.getElementById('levelBtn' + j).style.color = 'rgb(169,169,169)'
        }
    }
}

function modalDisplay() {
    var modal = document.getElementById('statisticsModal');
    modal.style.display = 'block';
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function settingsDisplay() {
    var modal = document.getElementById('settingsModal');
    modal.style.display = 'block';
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

let totalLevels = totalLevelsForGame
// colouring buttons
for (let i = 0; i < totalLevels; i++)
if (perfectStates[i] == 1) {
  // document.getElementById('levelBtn'+(i+1)).classList.add('easy-shadow');
  // document.getElementById('levelBtn'+(i+1)).style.borderColor = 'rgba(0,0,0,0)';
} else if (perfectStates[i] == 2) {
document.getElementById('levelBtn' + (i + 1)).classList.add('normal-shadow');
document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
} else if (perfectStates[i] == 3) {
document.getElementById('levelBtn' + (i + 1)).classList.add('hard-shadow');
document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
} else if (perfectStates[i] == 4) {
document.getElementById('levelBtn' + (i + 1)).classList.add('nightmare-shadow');
document.getElementById('levelBtn' + (i + 1)).style.borderColor = 'rgba(0,0,0,0)';
}