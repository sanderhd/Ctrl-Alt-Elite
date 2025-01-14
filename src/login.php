<?php
// database verbinden
// pak de username en password uit het formulier van HTML
// controleer of de gebruikersnaam bestaat
// controleer of het wachtwoord erbij hoort
// als het klopt login en stuur door naar het dashboard
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