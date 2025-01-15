<?php
require './database.php';

$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // controleer of de username bestaat
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // controleer of het wachtwoord klopt
        if (password_verify($password, $user['password'])) {
            // inloggen en naar dashboard sturen
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            header("Location: dashboard/dashboard.php");
            exit();
        } else {
            $showError = "Wachtwoord klopt niet!";
        }
    } else {
        $showError = "Deze gebruiker bestaat niet!";
    }
}
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
    <?php
    if ($showError) {
        echo '<div class="alert alert-danger">
                <strong>Error!</strong> ' . $showError . '
              </div>';
    }
    ?>
    <div class="loginContainer">
        <div class="loginBox">
            <h1>Login</h1>
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Login</button>
                <a href="signup.php">Don't have an account? Sign Up</a>
            </form>
        </div>
    </div>
</body>
</html>