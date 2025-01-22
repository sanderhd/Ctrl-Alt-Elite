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
    $stmt = $conn->prepare("SELECT quiz_id, quiz_name FROM Quiz WHERE created_by = :username"); // Ensure 'quiz_id' column is selected
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="../signout.php">Sign Out</a></li>
            <?php else: ?>
                <li><a href="../login.php">Login</a></li>
                <li><a href="../signup.php">Sign up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="dashboardContainer">
        <h1>Mijn Quizzes</h1>
        <a href="createquiz.php">Maak een quiz</a>
        <div class="quizContainer">
            <?php if (!empty($quizzes)): ?> <!-- als er quizen zijn -->
                <?php foreach ($quizzes as $quiz): ?> <!-- loop door alle quizen en in een div neerzetten -->
                    <div class="quizBox">
                        <h3><?php echo htmlspecialchars($quiz['quiz_name']); ?></h3>
                        <button onclick="playQuiz('<?php echo $quiz['quiz_id']; ?>')">Play</button>
                        <button onclick="shareQuiz('<?php echo $quiz['quiz_id']; ?>')">Share</button>
                        <button class="deleteButton" onclick="deleteQuiz('<?php echo $quiz['quiz_id']; ?>')">Delete</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Je hebt nog geen quizzes gemaakt.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function playQuiz(quizId) {
            window.location.href = `../quiz/playQuiz.php?quizId=${encodeURIComponent(quizId)}`;
        }

        function shareQuiz(quizId) {
            const quizLink = `https://ctrlaltelite.online/quiz/playQuiz.php?quizId=${quizId}`;
            navigator.clipboard.writeText(quizLink).then(() => {
                alert('Quiz link copied to clipboard: ' + quizLink);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        function deleteQuiz(quizId) {
            if (confirm('Weet je zeker dat je deze quiz wilt verwijderen?')) {
                window.location.href = `deleteQuiz.php?quizId=${encodeURIComponent(quizId)}`;
            }
        }
    </script>
</body>
</html>