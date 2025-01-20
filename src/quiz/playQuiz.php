<?php
session_start();
require '../database.php'; 

function getQuizInfo($quiz_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Quiz WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getQuestionsAndOptions($quiz_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Questions WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $questionsWithOptions = [];
    foreach ($questions as $question) {
        $stmt = $conn->prepare("SELECT * FROM Options WHERE question_id = :question_id");
        $stmt->bindParam(':question_id', $question['question_id']);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questionsWithOptions[] = [
            'question' => $question,
            'options' => $options
        ];
    }

    return $questionsWithOptions;
}

if (!isset($_GET['quizId'])) {
    die("Quiz ID niet gevonden.");
}

$quiz_id = $_GET['quizId'];
$quizInfo = getQuizInfo($quiz_id);

if (!$quizInfo) {
    die("Quiz niet gevonden.");
}

$questionsWithOptions = getQuestionsAndOptions($quiz_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl Alt Elite</title>
    <link rel="stylesheet" href="../CSS/quiz.css">
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
            <li><a href="../login.php">Login</a></li>
            <li><a href="../signup.php">Sign up</a></li>
        </ul>
    </nav>

<div class="quizContainer">
    <div class="quizBox">
        <h1><?php echo htmlspecialchars($quizInfo['quiz_name']); ?></h1>

    <form action="submitQuiz.php" method="post">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php foreach ($questionsWithOptions as $index => $questionWithOptions): ?>
            <div class="quiz-question">
                <p><?php echo htmlspecialchars($questionWithOptions['question']['question_text']); ?></p>
                <div class="options">
                    <?php foreach ($questionWithOptions['options'] as $option): ?>
                        <div class="option">
                            <input type="radio" 
                                   id="q<?php echo $index; ?>_option<?php echo $option['option_id']; ?>"
                                   name="question_<?php echo $questionWithOptions['question']['question_id']; ?>" 
                                   value="<?php echo $option['option_id']; ?>">
                            <label for="q<?php echo $index; ?>_option<?php echo $option['option_id']; ?>"><?php echo htmlspecialchars($option['option_text']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <br>
        <?php endforeach; ?>

        <button type="submit" class="btn">Inleveren</button>
    </form>
    </div>
</div>
</body>
</html>