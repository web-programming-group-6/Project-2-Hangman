<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: auth.php');
    exit();
}

include 'game.php'; // Include the game logic
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hangman The Game</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <!-- Main app display -->
    <div class="game-container">
        <!-- ... other elements ... -->
        <div class="game-title">Pirate's Hangman</div>
        <img src="./images/jolly-roger.png" alt="Pirate Flag" id="pirate-flag" />
        <!-- Display the image here -->
        <div class="game-image-container">
            <?php if (!gameComplete()): ?>
                <img class="game-image" src="<?php echo getCurrentPicture(getCurrentPart()); ?>" />
            <?php endif; ?>

            <!-- Indicate game status -->
            <?php if (gameComplete()): ?>
                <h2 class="game-status">GAME COMPLETE</h2>
                <?php if (isset($_SESSION['won']) && $_SESSION['won']): ?>
                    <p class="won-message">Good aye mate! You'll live to guess another day!</p>
                <?php else: ?>
                    <p class="lost-message">Aaaarrrrgggghhhh! Hang ‘Em from the Yardarm!</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="difficulty-container">
            <form method="post" class="difficulty-form">
                <label for="difficulty">Select Difficulty:</label>
                <select name="difficulty" id="difficulty">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
                <button type="submit" name="changeDifficulty">Change Difficulty</button>
            </form>

        </div>


        <div class="keyboard-form">
            <form method="get">
                <?php
                for ($i = 0; $i <= strlen($letters) - 1; $i++) {
                    $letter = $letters[$i];
                    $lowerLetter = strtolower($letter);
                    $class = '';

                    if (isset($_SESSION['responses'][$lowerLetter])) {
                        $class = $_SESSION['responses'][$lowerLetter] ? 'correct' : 'incorrect';
                    }

                    echo "<button type='submit' name='kp' value='" . $letter . "' class='" . $class . "'>" . $letter . "</button>";

                    if ($i % 7 == 0 && $i > 0) {
                        echo '<br>';
                    }
                }
                ?>
                <br><br>
                <!-- Restart game button -->
                <button type="submit" name="start">Restart Game</button>
            </form>
        </div>

        <div class="current-guesses">
            <!-- Display the current guesses -->
            <?php
            $guess = getCurrentWord(); // This should be the word to guess
            foreach (str_split($guess) as $l):
                $lowerL = strtolower($l);
                if (isset($_SESSION['responses'][$lowerL]) && $_SESSION['responses'][$lowerL]): ?>
                    <span class="guess-letter">
                        <?php echo $l; ?>
                    </span>
                <?php else: ?>
                    <span class="guess-letter">&nbsp;_&nbsp;</span>
                <?php endif;
            endforeach; ?>
        </div>


        <div class="footer">
            <p>DEVELOPED BY GROUP 6</p>
        </div>
    </div>

    <div id="ship-container">
        <img src="./images/sailing-ship1.png" alt="Sailing Ship" id="sailing-ship" />
    </div>

</body>

</html>