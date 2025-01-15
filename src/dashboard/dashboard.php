<?php
// dashboard alleen beschikbaar als je ingelogt bent.
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
    <nav>
        <ul class="left-nav">
            <li><img src="../assets/ctrlaltelite.png"></li>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../discover.php">Discover</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="settings.php">Settings</a></li>
            <li><a href="../signout.php">Sign Out</a></li>
        </ul>
    </nav>

    <div class="myQuizzes">
        <h3>My Quizzes</h3>
        <a href="../dashboard/createquiz.php">Create a new quiz</a>
        
    </div>
</body>
</html>