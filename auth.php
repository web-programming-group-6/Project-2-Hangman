<?php
session_start();

// If the user is already logged in, redirect to the game
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Function to register a new user
function registerUser($username, $password) {
    $userData = $username . ':' . password_hash($password, PASSWORD_DEFAULT) . "\n";
    file_put_contents('users.txt', $userData, FILE_APPEND);
}

// Function to check if a user exists
function userExists($username) {
    $users = explode("\n", file_get_contents('users.txt'));
    foreach ($users as $user) {
        if (strpos($user, $username) === 0) {
            return true;
        }
    }
    return false;
}

// Function to validate user login
function validateUser($username, $password) {
    $users = explode("\n", file_get_contents('users.txt'));
    foreach ($users as $user) {
        list($storedUser, $storedPass) = explode(':', $user);
        if ($username == $storedUser && password_verify($password, trim($storedPass))) {
            return true;
        }
    }
    return false;
}


// Handle registration
if (isset($_POST['register']) && $_POST['username'] && $_POST['password']) {
    if (userExists($_POST['username'])) {
        echo "User already exists!";
    } else {
        registerUser($_POST['username'], $_POST['password']);
        echo "Registration successful!";
    }
}

// Handle login
if (isset($_POST['login']) && $_POST['username'] && $_POST['password']) {
    if (validateUser($_POST['username'], $_POST['password'])) {
        $_SESSION['username'] = $_POST['username'];
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid username or password!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Authentication</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Register</h2>
    <form method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>
