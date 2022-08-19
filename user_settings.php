<?php

if (isset($_POST['difficultySetting']) && isset($_POST['languageSetting']) && isset($_POST['gamemodeSetting'])) {

    //grabbing post values
    $newDifficulty = $_POST['difficultySetting'];
    $newLanguage = $_POST['languageSetting'];
    $newGamemode = $_POST['gamemodeSetting'];

    //setting up db
    $dbSettings = new SQLite3('enemyvalues.sq3');

    //SQL queries
    $setNewDifficulty = "UPDATE UserSettings SET Difficulty = '" . $newDifficulty . "' WHERE UserID = 'bubness'";
    $setNewLanguage = "UPDATE UserSettings SET Language = '" . $newLanguage . "' WHERE UserID = 'bubness'";
    $setNewGamemode = "UPDATE UserSettings SET Gamemode = '" . $newGamemode . "' WHERE UserID = 'bubness'";
    
    //Updating database
    $update_newDiff = $dbSettings->query($setNewDifficulty);
    $update_newLang = $dbSettings->query($setNewLanguage);
    $update_newMode = $dbSettings->query($setNewGamemode);

    unset($dbSettings);
    header("Location: homepage.php");
} else {
    header("Location: homepage.php?=wrong");
}
