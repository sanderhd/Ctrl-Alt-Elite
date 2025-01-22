<?php
// alleen beschikbaar als je ingelogt bent
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../database.php";

$showAlert = false;
$showError = false;
$user_id = $_SESSION['user_id'];

// username ophalen van t account waarop je ingelogt bent
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['username'] ?? '';
$role = $user['role'] ?? '';

// als er op de knop gedrukt wordt van change password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Fetch the current password hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_password_hash = $user['password'];

    // Verify the current password
    if (password_verify($current_password, $current_password_hash)) {
        // Check if new password and confirm password match
        if ($new_password == $confirm_password) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $new_password_hash);
            $stmt->bindParam(':id', $user_id);
            if ($stmt->execute()) {
                $showAlert = "Wachtwoord succesvol gewijzigd!";
            } else {
                $showError = "Er is iets fout gegaan bij het wijzigen van het wachtwoord!";
            }
        } else {
            $showError = "Nieuwe wachtwoorden komen niet overeen!";
        }
    } else {
        $showError = "Huidig wachtwoord is onjuist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
    <nav>
        <ul class="left-nav">
            <li><img src="../assets/ctrlaltelite.png"></li>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../discover.php">Discover</a></li>
            <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="settings.php">Settings</a></li>
            <li><a href="../signout.php">Sign Out</a></li>
        </ul>
    </nav>

    <div class="settingsContainer">
        <div class="settingsBox">
            <h1 style="text-align: center;">Settings</h1>
            <?php
            if ($showAlert) {
                echo '<div class="alert alert-success">
                        <strong>Success!</strong> ' . $showAlert . '
                      </div>';
            }

            if ($showError) {
                echo '<div class="alert alert-danger">
                        <strong>Error!</strong> ' . $showError . '
                      </div>';
            }
            ?>
            <form action="settings.php" method="post">
                <label>Username:</label><br>
                <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled><br><br>
                <label>Role:</label><br>
                <input type="text" value="<?php echo htmlspecialchars($role); ?>" disabled><br><br>
                <label>Current Password:</label><br>
                <input type="password" name="current_password" required><br><br>
                <label>New Password:</label><br>
                <input type="password" name="new_password" required><br><br>
                <label>Confirm New Password:</label><br>
                <input type="password" name="confirm_password" required><br><br>
                <button type="submit">Change Password</button>
            </form>
            <form action="deleteAccount.php" method="post">
                <button type="submit" class="deleteButton">Delete Account</button>
            </form>
        </div>
    </div>
</body>
</html>