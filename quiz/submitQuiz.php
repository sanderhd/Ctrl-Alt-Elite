<?php
session_start();
require '../database.php';

// Functie om quiz info op te halen
function getQuizInfo($quiz_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Quiz WHERE quiz_id = :quiz_id"); // haal de quiz op waar de quiz_id gelijk is aan de quiz_id
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute(); // voer de query uit
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie om correcte antwoorden op te halen
function getCorrectAnswers($quiz_id) {
    global $conn; // global zorgt ervoor dat de variabele buiten de functie ook gebruikt kan worden
    $stmt = $conn->prepare("SELECT q.question_id, q.question_text, o.option_id, o.option_text
                           FROM Questions q 
                           JOIN Options o ON q.question_id = o.question_id
                           WHERE q.quiz_id = :quiz_id AND o.is_correct = 1"); // haal de vragen en opties op waar de is_correct 1 is
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute(); // voer de query uit
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$quiz_id = $_POST['quiz_id']; 

$quizInfo = getQuizInfo($quiz_id);

if (!$quizInfo) {
    die("Quiz niet gevonden.");
}

$correctAnswers = getCorrectAnswers($quiz_id);

$score = 0;
// voor elk goede antwoord dit uitvoeren
foreach ($correctAnswers as $correctAnswer) { // loop door alle correcte antwoorden
    if (isset($_POST['question_' . $correctAnswer['question_id']])) {
        $user_answers = $_POST['question_' . $correctAnswer['question_id']];
        if (is_array($user_answers) && in_array($correctAnswer['option_id'], $user_answers)) { // als het antwoord in de array zit
            $score++; // score ophogen
        } elseif ($user_answers == $correctAnswer['option_id']) { // als het antwoord gelijk is aan het goede antwoord
            $score++;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultaten - <?php echo htmlspecialchars($quizInfo['quiz_name']); ?></title>
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

<main class="main">
    <div class="quizContainer">
        <div class="quizBox">
            <h1>Resultaten</h1>
            <p>Je hebt <strong style="color: var(--secondary);"><?php echo $score; ?></strong> van de <strong style="color: var(--secondary);"><?php echo count($correctAnswers); ?></strong> vragen goed beantwoord.</p>
        <h3>Je Antwoorden:</h3>
        <?php foreach ($correctAnswers as $correctAnswer): ?>
            <li>
            <strong> Vraag: </strong><?php echo htmlspecialchars($correctAnswer['question_text']); ?> 
                <br> <strong> Jouw antwoord: </strong>
                <?php 
                    $user_answers = isset($_POST['question_' . $correctAnswer['question_id']]) ? $_POST['question_' . $correctAnswer['question_id']] : [];
                    
                    $user_answer_texts = [];
                    if (is_array($user_answers)) {
                        foreach ($user_answers as $user_answer) {
                            $stmt = $conn->prepare("SELECT option_text FROM Options WHERE option_id = :option_id");
                            $stmt->bindParam(':option_id', $user_answer);
                            $stmt->execute();
                            $user_answer_texts[] = $stmt->fetchColumn();
                        }
                    } else {
                        $stmt = $conn->prepare("SELECT option_text FROM Options WHERE option_id = :option_id");
                        $stmt->bindParam(':option_id', $user_answers);
                        $stmt->execute();
                        $user_answer_texts[] = $stmt->fetchColumn();
                    }
                    echo htmlspecialchars(implode(', ', $user_answer_texts));
                ?>
                <br> <strong> Correct antwoord: </strong> <?php echo htmlspecialchars($correctAnswer['option_text']); ?>
            </li>
        <?php endforeach; ?>
        </div>
    </div>

    <a href="../index.php">Terug naar Home</a>
</main>
</body>
</html>