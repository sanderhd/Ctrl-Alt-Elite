<?php
require_once "database.php";

// Hier komt de login code, die controlleert of de gebruiker in de database staat en of het wachtwoord bij de naam klopt.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Login</title>
</head>
<body>
    <div class="loginContainer">
        <div class="loginBox">
            <h1>Login</h1><br>
            <form action="login.php">
                <input type="text" name="username" placeholder="email address"><br>
                <input type="password" name="password" placeholder="password"><br>
                <!-- <button><img src="assets/google_icon_orange.png" alt="googleLogo">Login with Google</button> -->
                <button type="submit">Login</button>
                <a href="signup.php">Sign Up</a>
            </form>
        </div>
    </div>
</body>
</html>