<?php
// database verbinden
require_once "../database.php";

// dashboard alleen beschikbaar als je ingelogt bent.
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // haal alle quizzes en de usernames en hun makers op uit de database
    $stmt = $conn->prepare("SELECT quiz_name FROM Quiz WHERE created_by = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    // quizen ophalen en in een array zetten
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // debuggen
    error_log("Fetched quizzes: " . print_r($quizzes, true));
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo "Connection failed: " . $e->getMessage();
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
    <div class="dashboardContainer">
        <h2>Mijn Quizzes:</h2>
        <div class="quizContainer">
            <?php if (!empty($quizzes)): ?> <!-- als er quizen zijn -->
                <?php foreach ($quizzes as $quiz): ?> <!-- loop door alle quizen en in een div neerzetten -->
                    <div class="quizBox">
                        <h3><?php echo htmlspecialchars($quiz['quiz_name']); ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Je hebt nog geen quizzes gemaakt.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>