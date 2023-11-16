<?php
/* @route http://dev.wfprojects.com/hangman/game.php */

session_start();

$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$WON = false;

// temp variables for testing

$guess = "HANGMAN";
$maxLetters = strlen($guess) - 1;
$responses = ["H","G","A"];


// Live variables here


// ALl the body parts
$bodyParts = ["nohead","head","body","hand","hands","leg","legs"];


// Random words for the game and you to guess
// Easy words
$easyWords = ["HANGMAN", "BUTTERFLY", "APPLE"];

// Medium words
$mediumWords = ["INSIDIOUSLY", "DUPLICATE", "CASUALTY"];

// Hard words
$hardWords = ["GLOOMFUL", "SYZYGY", "CRYPTIC"];

// Set the default difficulty
$words = $easyWords;



function getCurrentPicture($part){
    return "./images/hangman_". $part. ".png";
}


function startGame(){
    // Check if the difficulty level is set
    if (!isset($_SESSION["difficulty"])) {
        // Set default difficulty to easy
        $_SESSION["difficulty"] = "easy";
    }

    // Select a random word based on the difficulty level
    switch ($_SESSION["difficulty"]) {
        case "easy":
            $words = [
                "HANGMAN", "BUTTERFLY", "APPLE", "INSIDIOUSLY", "DUPLICATE",
                "CASUALTY", "GLOOMFUL"
            ];
            break;
        case "medium":
            $words = [
                "JACKET", "TABLE", "COMPUTER", "MUSIC", "FLOWER", "BOTTLE"
            ];
            break;
        case "hard":
            $words = [
                "SYZYGY", "CRYPTIC", "QUETZAL", "JUXTAPOSE", "MYSTERIOUS", "ZEPHYR"
            ];
            break;
        default:
            $words = [
                "HANGMAN", "BUTTERFLY", "APPLE", "INSIDIOUSLY", "DUPLICATE",
                "CASUALTY", "GLOOMFUL"
            ];
            break;
    }

    // Set the random word
    $key = array_rand($words);
    $_SESSION["word"] = $words[$key];
}

// restart the game. Clear the session variables
function restartGame(){
    session_destroy();
    session_start();

}

// Get all the hangman Parts
function getParts(){
    global $bodyParts;
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : $bodyParts;
}

// add part to the Hangman
function addPart(){
    $parts = getParts();
    array_shift($parts);
    $_SESSION["parts"] = $parts;
}

// get Current Hangman Body part
function getCurrentPart(){
    $parts = getParts();
    return $parts[0];
}

// get the current words
function getCurrentWord(){
    global $easyWords, $mediumWords, $hardWords;
    
    if (!isset($_SESSION["word"]) || empty($_SESSION["word"])) {
        switch ($_SESSION["difficulty"]) {
            case "easy":
                $words = $easyWords;
                break;
            case "medium":
                $words = $mediumWords;
                break;
            case "hard":
                $words = $hardWords;
                break;
            default:
                $words = $easyWords;
                break;
        }
        
        $key = array_rand($words);
        $_SESSION["word"] = $words[$key];
    }
    
    return $_SESSION["word"];
}



// user responses logic

// get user response
function getCurrentResponses(){
    return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
}

function addResponse($letter){
    $responses = getCurrentResponses();
    array_push($responses, $letter);
    $_SESSION["responses"] = $responses;
}

// check if pressed letter is correct
function isLetterCorrect($letter){
    $word = getCurrentWord();
    $max = strlen($word) - 1;
    for($i=0; $i<= $max; $i++){
        if($letter == $word[$i]){
            return true;
        }
    }
    return false;
}

// is the word (guess) correct

function isWordCorrect(){
    $guess = getCurrentWord();
    $responses = getCurrentResponses();
    $max = strlen($guess) - 1;
    for($i=0; $i<= $max; $i++){
        if(!in_array($guess[$i],  $responses)){
            return false;
        }
    }
    return true;
}

// check if the body is ready to hang

function isBodyComplete(){
    $parts = getParts();
    // is the current parts less than or equal to one
    if(count($parts) <= 1){
        return true;
    }
    return false;
}

// manage game session

// is game complete
function gameComplete(){
    return isset($_SESSION["gamecomplete"]) ? $_SESSION["gamecomplete"] :false;
}


// set game as complete
function markGameAsComplete(){
    $_SESSION["gamecomplete"] = true;
}

// start a new game
function markGameAsNew(){
    $_SESSION["gamecomplete"] = false;
}



/* Detect when the game is to restart. From the restart button press*/
if(isset($_GET['start'])){
    restartGame();
}


/* Detect when Key is pressed */
if(isset($_GET['kp'])){
    $currentPressedKey = isset($_GET['kp']) ? $_GET['kp'] : null;
    // if the key press is correct
    if($currentPressedKey 
    && isLetterCorrect($currentPressedKey)
    && !isBodyComplete()
    && !gameComplete()){
        
        addResponse($currentPressedKey);
        if(isWordCorrect()){
            $WON = true; // game complete
            markGameAsComplete();
        }
    }else{
        // start hanging the man :)
        if(!isBodyComplete()){
           addPart(); 
           if(isBodyComplete()){
               markGameAsComplete(); // lost condition
           }
        }else{
            markGameAsComplete(); // lost condition
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hangman The Game</title>
    
</head>

    <body style="background: slategrey">
        
        <!-- Main app display -->
        <div style="margin: 0 auto; background: black; width:1060px; height:920px; padding:5px; border-radius:3px; text-align: center;">
        <h1 style="color: red; font-family: cursive;">HANGMAN</h1>

            <!-- Display the image here -->
            <div style="display:inline-block; width: 1050px; background:#fff; ">
                 <img style="width:80%; display:inline-block;" src="<?php echo getCurrentPicture(getCurrentPart());?>"/>
          
                <!-- Indicate game status -->
               <?Php if(gameComplete()):?>
                    <h2 style="font-family:cursive;">GAME COMPLETE</h2>
                <?php endif;?>
                <?php if($WON  && gameComplete()):?>
                    <p style="color: darkgreen; font-size: 15px; font-family: cursive;">You Won! :)</p>
                <?php elseif(!$WON  && gameComplete()): ?>
                    <p style="color: darkred; font-size: 15px; font-family: cursive;">HANG, MAN!</p>
                <?php endif;?>
                <div style="float:right; display:inline; vertical-align:top; background: darkgrey;">
                
                <form method="post">
                <label for="difficulty">Select Difficulty:</label>
                <select name="difficulty" id="difficulty">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
                <button type="submit" name="start" style="margin: 5px; background-color:lightblue;">Start Game</button>
            </form>
                <div style="display:inline-block;">
                    <form method="get">
                    <?php
                        $max = strlen($letters) - 1;
                        for($i=0; $i<= $max; $i++){
                            echo "<button type='submit' name='kp' value='". $letters[$i] . "'>".
                            $letters[$i] . "</button>";
                            if ($i % 7 == 0 && $i>0) {
                               echo '<br>';
                            }
                            
                        }
                    ?>
                    <br><br>
                    <!-- Restart game button -->
                    <button type="submit" name="start" style="margin: 5px; background-color:lightred;">Restart Game</button>
                    </form>
                </div>
                
            </div>
            
            </div>
            
            
            
            <div style="margin-top:20px; padding:15px; background: white; color: #fcf8e3">
                <!-- Display the current guesses -->
                <?php 
                 $guess = getCurrentWord();
                 $maxLetters = strlen($guess) - 1;
                for($j=0; $j<= $maxLetters; $j++): $l = getCurrentWord()[$j]; ?>
                    <?php if(in_array($l, getCurrentResponses())):?>
                        <span style=" color:black; font-size: 35px; border-bottom: 3px solid #000; margin-right: 5px;"><?php echo $l;?></span>
                    <?php else: ?>
                        <span style="color:black; font-size: 35px; border-bottom: 3px solid #000; margin-right: 5px;">&nbsp;&nbsp;&nbsp;</span>
                    <?php endif;?>
                <?php endfor;?>
                
            </div>
            <div style="vertical-align:bottom; "> 
            <p style = "font-family: cursive; color:red; ">DEVELOPED BY GROUP 6</p>
            </div>
        </div>
        
        
        
    </body>
    
    
</html>