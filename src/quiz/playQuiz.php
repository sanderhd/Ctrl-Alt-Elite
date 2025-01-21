<?php
session_start();
require '../database.php'; 

// Functie om quiz info op te halen
function getQuizInfo($quiz_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Quiz WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie om vragen en opties op te halen
function getQuestionsAndOptions($quiz_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Questions WHERE quiz_id = :quiz_id"); // haal de vragen op waar de quiz_id gelijk is aan de quiz_id
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // zet de vragen in een array

    $questionsWithOptions = []; // maak een lege array aan
    foreach ($questions as $question) { // loop door alle vragen
        $stmt = $conn->prepare("SELECT * FROM Options WHERE question_id = :question_id"); // haal de opties op waar de question_id gelijk is aan de question_id
        $stmt->bindParam(':question_id', $question['question_id']);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC); // zet de opties in een array

        $questionsWithOptions[] = [ // zet de vragen en opties in een array
            'question' => $question,
            'options' => $options
        ];
    }

    return $questionsWithOptions; // return de array
}

if (!isset($_GET['quizId'])) { // als de quizId niet is gevonden dan kill de pagina
    die("Quiz ID niet gevonden.");
}

$quiz_id = $_GET['quizId']; // pak de quizId uit de url
$quizInfo = getQuizInfo($quiz_id); // haal de quiz info op

if (!$quizInfo) { // als de quiz niet bestaat dan kill de pagina
    die("Quiz niet gevonden.");
}

$questionsWithOptions = getQuestionsAndOptions($quiz_id); // haal de vragen en opties op

// Totale tijd berekenen
$totalTime = 0; // zet de totale tijd op 0
foreach ($questionsWithOptions as $questionWithOptions) { // loop door alle vragen en opties
    $totalTime += $questionWithOptions['question']['time_limit']; // tel de tijd op bij de totale tijd
}
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
        <h2 id="totalTimeDisplay"><?php echo $totalTime; ?> sec</h2>

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

<script>
    let totalTime = <?php echo $totalTime; ?>;
    const totalTimeDisplay = document.getElementById('totalTimeDisplay');

    // Start de totale timer
    function startTotalTimer() {
        const timer = setInterval(() => {
            totalTimeDisplay.innerText = totalTime + ' sec';
            totalTime--;

            if (totalTime < 0) {
                clearInterval(timer);
                alert('Time is up!');
                document.querySelector('form').submit();
            }
        }, 1000);
    }

    window.onload = startTotalTimer;
</script>
</body>
</html>