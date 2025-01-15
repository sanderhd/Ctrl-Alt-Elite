<?php
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include './database.php';

    // uit het formulier halen en in var zetten
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $role = 'user'; // set default role to 'user'

    // controleer of de naam bestaat
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $num = $stmt->rowCount();

    // controleert of er geen bestaande gebruikers zijn met dezelfde username
    if ($num == 0) {
        // 2x wachtwoord invoeren en controleren of ze overeenkomen en als dat zo is hashen
        if ($password == $cpassword) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // in de database zetten als user
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hash);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                $showAlert = true;
            } else {
                $showError = "Oeps, er is iets fout gegaan!!";
            }
        } else {
            $showError = "Wachtwoorden komen niet overeen!";
        }
    } else {
        $showError = "Deze gebruikersnaam bestaat al!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Sign Up</title>
</head>
<body>
    <?php
    if ($showAlert) {
        echo '<div class="alert alert-success">
                <strong>Success!</strong> Your account is now created and you can login.
              </div>';
    }

    if ($showError) {
        echo '<div class="alert alert-danger">
                <strong>Error!</strong> ' . $showError . '
              </div>';
    }
    ?>
    <div class="loginContainer">
        <div class="loginBox">
            <h1>Sign Up</h1>
            <form action="signup.php" method="post">
                <input type="text" name="username" placeholder="username" required><br>
                <input type="password" name="password" placeholder="password" required><br>
                <input type="password" name="cpassword" placeholder="confirm password" required><br>
                <button type="submit">Sign Up</button><br>
                <a href="login.php">Already a user? Login</a>
            </form>
        </div>
    </div>
</body>
</html>