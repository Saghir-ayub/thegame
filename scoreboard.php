<!DOCTYPE html>

<html>

<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"> </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <title>Scoreboard</title>
</head>

<body>
    <!-- querying the database to pull data-->
    <?php
    $db = new SQLite3('enemyvalues.sq3');
    $sql = "SELECT ChineseWords.ID, ChineseWords.Hanzi, ChineseWords.Pinyin, ChineseWords.English, UserChineseWords.WordScore, ChineseWords.Level,UserChineseWords.LastEntered
    FROM UserChineseWords
    JOIN
    ChineseWords
    ON ChineseWords.ID = UserChineseWords.WordID
    WHERE UserChineseWords.UserID = 'bubness'"; // ============ ID HERE  =============
    $result = $db->query($sql);

    // Create an empty array
    $hanziChars = array();
    $pinyinChars = array();
    $englishChars = array();
    $wordScore = array();

    while ($singlerow = $result->fetchArray()) {
        array_push($hanziChars, $singlerow[1]);
        array_push($pinyinChars, $singlerow[2]);
        array_push($englishChars, $singlerow[3]);
        array_push($wordScore, $singlerow[4]);

        echo "<button class='btn-primary scoreBtn' onclick='wordCard(this.id)' onmouseover='wordPinyin(this.id)' onmouseout='wordHanzi(this.id)' id='word_" . $singlerow[0] . "'>" . $singlerow[1] . "</button>";
        echo "
    <div id='wordModal_" . $singlerow[0] . "' class='modal'>
      <div class='modal-content'>
        <span id='close_" . $singlerow[0] . "' class='close' onclick='modalClose(this.id)'>&times;</span>
        <p>Hanzi: " . $singlerow[1] . "</p>
        <p>Pinyin: " . $singlerow[2] . "</p>
        <p>English: " . $singlerow[3] . "</p>
        <p>Score: " . $singlerow[4] . "</p>
        <p>Level: " . $singlerow[5] . "</p>
        <p>Last played: " . $singlerow[6] . "</p>
      </div>
    </div>";
    }
    unset($db);
    ?>


    <!-- echoing database values into variables -->
    <script type="text/javascript">
        var passHanzi = <?php echo json_encode($hanziChars); ?>;
        var passPinyin = <?php echo json_encode($pinyinChars); ?>;
        var passEnglish = <?php echo json_encode($englishChars); ?>;
        var passScore = <?php echo json_encode($wordScore); ?>;
    </script>

    <script>
        for (i = 1; i <= passHanzi.length; i++) {
            if (passScore[i - 1] < 1) {
                document.getElementById("word_" + i).classList.add('btn-secondary');
                document.getElementById("word_" + i).style.borderColor = "rgb(0, 0, 0)";
            } else if (passScore[i - 1] >= 1 && passScore[i - 1] < 20) {
                document.getElementById("word_" + i).style.backgroundColor = "rgb(209, " + passScore[i - 1] * 10 + ", 0)";
                document.getElementById("word_" + i).style.borderColor = "rgb(0, 0, 0)";
            } else if (passScore[i - 1] >= 20 && passScore[i - 1] < 40) {
                document.getElementById("word_" + i).style.backgroundColor = "rgb(" + (209 - (passScore[i - 1] - 20) * 10) + ", 209, 0)";
                document.getElementById("word_" + i).style.borderColor = "rgb(0, 0, 0)";
            } else if (passScore[i - 1] >= 40) {
                document.getElementById("word_" + i).classList.add('btn-dark');
                document.getElementById("word_" + i).classList.add('maxLevel');
                document.getElementById("word_" + i).style.font = "bold";
            }
        }

        function wordCard(clicked_word_id) {
            var word_id = clicked_word_id.substring('word_'.length);
            var modal = document.getElementById('wordModal_' + word_id);
            var span = document.getElementsByClassName("close")[0];
            modal.style.display = "block";

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        };

        function modalClose(modal_click_id) {
            var word_id = modal_click_id.substring('close_'.length);
            var modal = document.getElementById('wordModal_' + word_id);
            modal.style.display = "none";
        }

        function wordPinyin(hover_word_id) {
            var word_id = hover_word_id.substring('word_'.length);
            document.getElementById(hover_word_id).textContent = passPinyin[word_id - 1];
        }

        function wordHanzi(hover_word_id) {
            var word_id = hover_word_id.substring('word_'.length);
            document.getElementById(hover_word_id).textContent = passHanzi[word_id - 1];
        }
    </script>
</body>

</html>