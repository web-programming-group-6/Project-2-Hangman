<?php

// Associative array of words categorized by difficulty
$wordsByDifficulty = [
    'easy' => ['apple', 'ball', 'cat', 'dog', 'elephant', 'frog', 'goat', 'house', 'ice cream', 'jellyfish', 'kite', 'lion', 'monkey', 'nose', 'octopus', 'penguin', 'queen', 'rabbit', 'snake', 'turtle', 'umbrella', 'violin', 'whale', 'xylophone', 'yak', 'zebra'],
    'medium' => ['keyboard', 'monitor', 'printer', 'laptop', 'headphones', 'charger', 'speakers', 'microphone', 'computer', 'software', 'hardware', 'motherboard', 'processor', 'graphics', 'keyboard', 'mouse', 'network', 'wireless', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub'],
    'hard' => ['encyclopedia', 'microprocessor', 'transcendental', 'programming', 'development', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub', 'firewall', 'ethernet', 'bluetooth', 'internet', 'website', 'firewall', 'encryption', 'decryption', 'algorithm', 'programming', 'developer', 'application', 'database', 'server', 'client', 'router', 'switch', 'hub']
];

// Return words based on difficulty
function getWordsByDifficulty($difficulty)
{
    global $wordsByDifficulty;

    if (array_key_exists($difficulty, $wordsByDifficulty)) {
        return $wordsByDifficulty[$difficulty];
    }

    // Return 'easy' words as default
    return $wordsByDifficulty['easy'];
}
?>