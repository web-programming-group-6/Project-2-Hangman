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
        <h1 class="game-title">HANGMAN</h1>

        <!-- Display the image here -->
        <div class="game-image-container">
            <img class="game-image" src="<?php echo getCurrentPicture(getCurrentPart()); ?>" />

            <!-- Indicate game status -->
            <?php if (gameComplete()): ?>
                <h2 class="game-status">GAME COMPLETE</h2>
            <?php endif; ?>
            <?php if ($WON && gameComplete()): ?>
                <p class="won-message">You Won!</p>
            <?php elseif (!$WON && gameComplete()): ?>
                <p class="lost-message">HANG, MAN!</p>
            <?php endif; ?>

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

            </div>
        </div>

        <div class="current-guesses">
            <!-- Display the current guesses -->
            <?php
            $guess = getCurrentWord();
            foreach (str_split($guess) as $l): ?>
                <span class="guess-letter">
                    <?php echo in_array(strtolower($l), array_map('strtolower', getCurrentResponses())) ? $l : '_'; ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="footer">
            <p>DEVELOPED BY GROUP 6</p>
        </div>
    </div>
</body>

</html>