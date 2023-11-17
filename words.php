<?php

// Associative array of words categorized by difficulty
$wordsByDifficulty = [
    'easy' => ['apple', 'ball', 'cat'],
    'medium' => ['keyboard', 'monitor', 'printer'],
    'hard' => ['encyclopedia', 'microprocessor', 'transcendental']
];

// Return words based on difficulty
function getWordsByDifficulty($difficulty) {
    global $wordsByDifficulty;

    if (array_key_exists($difficulty, $wordsByDifficulty)) {
        return $wordsByDifficulty[$difficulty];
    }

    // Return 'easy' words as default
    return $wordsByDifficulty['easy'];
}
?>
