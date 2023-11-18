<?php
include 'words.php'; // Include the words
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the alphabet for the game
$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$WON = false; // Track if the game is won

// Define all the body parts corresponding to the hangman stages
$bodyParts = [
    "post",
    "post_head",
    "post_head_eyes",
    "post_head_eyes_mouth",
    "post_head_eyes_mouth_neck",
    "post_head_eyes_mouth_neck_torso",
    "post_head_eyes_mouth_neck_torso_arms",
    "post_head_eyes_mouth_neck_torso_arms_legs"
];

// Function to get the current picture path
function getCurrentPicture($part)
{
    return "./images/hangman_" . $part . ".png";
}

// Function to start a new game
function startGame()
{
    global $easyWords, $mediumWords, $hardWords;

    // Set the default difficulty
    if (!isset($_SESSION["difficulty"])) {
        $_SESSION["difficulty"] = "easy";
    }

    // Get words based on difficulty
    $words = getWordsByDifficulty($_SESSION["difficulty"]);

    // Set the random word
    $key = array_rand($words);
    $_SESSION["word"] = $words[$key];
    $_SESSION["parts"] = $GLOBALS['bodyParts']; // Reset body parts for new game
    $_SESSION["responses"] = []; // Reset responses for new game
    $_SESSION["gamecomplete"] = false; // Reset game completion status
}

// Function to reset the game while maintaining the session
function resetGame()
{
    $_SESSION["word"] = "";
    $_SESSION["parts"] = $GLOBALS['bodyParts'];
    $_SESSION["responses"] = [];
    $_SESSION["gamecomplete"] = false;
    $_SESSION["won"] = false; // Ensure this is reset as well
    startGame();
}


// Check if the difficulty form has been submitted
if (isset($_POST['changeDifficulty'])) {
    $_SESSION['difficulty'] = $_POST['difficulty'];
    resetGame(); // Reset and restart the game with the new difficulty
}

// Function to get the current hangman parts
function getParts()
{
    return $_SESSION["parts"];
}

// Function to add a part to the hangman
function addPart()
{
    $parts = getParts();
    array_shift($parts);
    $_SESSION["parts"] = $parts;
}

// Function to get the current hangman body part
function getCurrentPart()
{
    $parts = getParts();
    return $parts[0];
}

// Function to get the current word being guessed
function getCurrentWord()
{
    return $_SESSION["word"];
}

// Function to get the current user responses
function getCurrentResponses()
{
    return $_SESSION["responses"];
}

// Function to add a user response
// Function to add a user response and check if the word is complete
function addResponse($letter)
{
    $responses = getCurrentResponses();
    $responses[strtolower($letter)] = isLetterCorrect($letter);
    $_SESSION["responses"] = $responses;

    // Check if the word is complete after adding the response
    if (isWordCorrect()) {
        $_SESSION['won'] = true;  // Set win state
        markGameAsComplete();
    }
}



// Check if the pressed letter is in the word
function isLetterCorrect($letter)
{
    $word = strtolower(getCurrentWord());
    $letter = strtolower($letter);
    return strpos($word, $letter) !== false;
}

// Check if the word is completely guessed
function isWordCorrect()
{
    $guess = strtolower(getCurrentWord());
    $responses = array_map('strtolower', getCurrentResponses());
    foreach (str_split($guess) as $letter) {
        if (!in_array($letter, $responses)) {
            return false;
        }
    }
    return true;
}

// Function to check if the hangman is complete
function isBodyComplete()
{
    return count(getParts()) <= 1;
}

// Function to check if the game is complete
function gameComplete()
{
    return isset($_SESSION["gamecomplete"]) && $_SESSION["gamecomplete"];
}


// Function to mark the game as complete
function markGameAsComplete()
{
    $_SESSION["gamecomplete"] = true;
}


// Function to process a key press
if (isset($_GET['kp'])) {
    $currentPressedKey = strtolower($_GET['kp']);
    if (!gameComplete()) {
        addResponse($currentPressedKey);
        if (isWordCorrect()) {
            $_SESSION['won'] = true;  // Set win state
            markGameAsComplete();
        } else if (!isLetterCorrect($currentPressedKey)) {
            addPart();
            if (isBodyComplete()) {
                $_SESSION['won'] = false; // Set loss state
                markGameAsComplete();
            }
        }
    }
}



// Function to restart the game from a button press
if (isset($_GET['start'])) {
    resetGame();
}

// Start a new game if one hasn't been started yet
if (!isset($_SESSION["word"])) {
    startGame();
}
?>