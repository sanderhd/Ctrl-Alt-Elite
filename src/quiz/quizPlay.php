<?php
require_once "../database.php";

$quiz_id = isset($_GET['quizId']) ? $_GET['quizId'] : 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch questions for the quiz
    $stmt = $conn->prepare("SELECT question_id, question_text FROM Questions WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions)) {
        throw new Exception("No questions found for this quiz.");
    }

    // Fetch the first question
    $question = $questions[0];
    $question_id = $question['question_id'];
    $question_text = $question['question_text'];

    // Fetch answers for the first question
    $stmt = $conn->prepare("SELECT option_id, option_text FROM Options WHERE question_id = :question_id");
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl Alt Elite</title>
</head>
<body>
    <nav>
        <ul class="left-nav">
            <li><img src="assets/ctrlaltelite.png"></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="../discover.php">Discover</a></li>
            <li><a href="dashboard/dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign up</a></li>
        </ul>
    </nav>

    <h1 id="questionDisplay"><?php echo htmlspecialchars($question_text); ?></h1>
    <h2 id="timeDisplay">Timer</h2>
    
    <div id="optionsContainer">
        <?php foreach ($answers as $answer): ?>
            <button class="optionButton" onclick="selectAnswer(this)"><?php echo htmlspecialchars($answer['option_text']); ?></button>
        <?php endforeach; ?>
    </div>
</body>
</html>