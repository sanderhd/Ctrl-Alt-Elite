<?php
require_once "../database.php";
require_once "../classes/quiz.php";

session_start(); // sessie starten voor de zekerheid
$user_id = $_SESSION['user_id'] ?? null; // userid ophalen uit de sessie

// Als er een POST request komt de quiz opslaan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_name = $_POST['name']; // quiz naam ophalen
    $questions = []; // array voor de vragen

    error_log("Received POST request with quiz name: $quiz_name"); // debug

    for ($i = 1; isset($_POST["question$i"]); $i++) {
        $question_text = $_POST["question$i"]; // vraag ophalen
        $answers = explode(',', $_POST["antwoorden$i"]); // antwoorden splitten op komma
        $correct_answer = $_POST["correctanswer$i"];
        $time_limit = $_POST["timelimit$i"]; // get the time limit for the question
        $questions[] = [
            'question_text' => $question_text,
            'answers' => $answers,
            'correct_answer' => $correct_answer,
            'time_limit' => $time_limit // add time limit to the question array
        ]; // vragen in de array zetten
    }

    // Controleren of je ingelogt bent, anders zeggen dat je moet inloggen.
    if ($user_id === null) {
        die("Er is geen User ID gevonden. Log in om een quiz te maken. <script ");
    }

    // nieuwe quiz maken van de class uit classes/quiz.php 
    $quiz = new Quiz($quiz_name, $user_id, $questions);

    // quiz in de database zetten
    $quizName = $quiz->getName();
    $quizCreator = $quiz->getCreator();
    $stmt = $conn->prepare("INSERT INTO Quiz (quiz_name, created_by) VALUES (:quiz_name, :created_by)"); // querry voorbereiden
    $stmt->bindParam(':quiz_name', $quizName);//binden van de parameters
    $stmt->bindParam(':created_by', $quizCreator);
    $stmt->execute();//query uitvoeren
    $quiz_id = $conn->lastInsertId();//laatste id ophalen

    // vragen en antwoorden in de database zetten
    foreach ($quiz->getQuestions() as $question) {
        $questionText = $question['question_text'];
        $timeLimit = $question['time_limit']; // get the time limit from the question array
        error_log("Inserting question: $questionText with time limit: $timeLimit");
        $stmt = $conn->prepare("INSERT INTO Questions (quiz_id, question_text, time_limit) VALUES (:quiz_id, :question_text, :time_limit)");
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question_text', $questionText);
        $stmt->bindParam(':time_limit', $timeLimit); // bind the time limit parameter
        $stmt->execute();
        $question_id = $conn->lastInsertId();

        foreach ($question['answers'] as $answer) {
            $optionText = trim($answer); // spaties deleten
            $is_correct = (strcasecmp($optionText, $question['correct_answer']) === 0) ? 1 : 0; // hoofdlettergevoeligheid negeren
            error_log("Inserting answer: $optionText with is_correct: $is_correct"); //debug omdat ja HET WERKT NIETTT

            $stmt = $conn->prepare("INSERT INTO Options (question_id, option_text, is_correct) VALUES (:question_id, :option_text, :is_correct)");
            $stmt->bindParam(':question_id', $question_id);
            $stmt->bindParam(':option_text', $optionText);
            $stmt->bindParam(':is_correct', $is_correct);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maak Quiz</title>
    <link rel="stylesheet" href="../CSS/createQuiz.css">
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
    <div class="createContainer">
        <div class="createBox">
            <form action="createquiz.php" method="post">
                <h1>Maak een nieuwe quiz</h1>
                <h2>Quiz Naam:</h2>
                <input type="text" name="name" placeholder="Bijv. Ctrl Alt Quiz!" required><br><br>

                <div id="questions">
                    <div class="question">
                        <h3>Vraag 1:</h3>
                        <input type="text" name="question1" placeholder="Bijv. Wat is de hoofdstad van Nederland?" required><br>
                        <span>Antwoorden | Zet een komma tussen de antwoorden als je meer antwoorden wilt dan 1.</span><br>
                        <input type="text" name="antwoorden1" placeholder="Bijv. Amsterdam, Rotterdam, Groningen, Utrecht" required><br>
                        <span>Goede Antwoord | Typ het 1:1 over van de mogelijke antwoorden.</span><br>
                        <input type="text" name="correctanswer1" placeholder="Bijv. Amsterdam" required><br><br>
                        <span>Tijdslimiet (seconden):</span><br>
                        <input type="number" name="timelimit1" placeholder="Bijv. 30" required><br><br>
                    </div>
                </div>

                <button type="submit">Sla quiz op</button><br>
                <button type="button" onclick="addQuestion()">Voeg vraag toe</button><br>
            </form>
        </div>
    </div>


    <script>
        let questionCount = 1;

        function addQuestion() {
            questionCount++;
            const questionsDiv = document.getElementById('questions');
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');
            questionDiv.innerHTML = `
                <h3>Vraag ${questionCount}:</h3>
                <input type="text" name="question${questionCount}" placeholder="Bijv. Wat is de hoofdstad van Nederland?" required><br>
                <span>Antwoorden | Zet een komma tussen de antwoorden als je meer antwoorden wilt dan 1.</span><br>
                <input type="text" name="antwoorden${questionCount}" placeholder="Zet een comma tussen de antwoorden." required><br>
                <span>Goede Antwoord | Typ het 1:1 over van de mogelijke antwoorden.</span><br>
                <input type="text" name="correctanswer${questionCount}" placeholder="Bijv. Amsterdam" required><br><br>
                <span>Tijdslimiet (seconden):</span><br>
                <input type="number" name="timelimit${questionCount}" placeholder="Bijv. 30" required><br><br>
            `;
            questionsDiv.appendChild(questionDiv);
        }
    </script>
</body>

</html>