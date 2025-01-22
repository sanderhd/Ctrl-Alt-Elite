<?php
require_once "../database.php";
require_once "../classes/quiz.php";

session_start(); // sessie starten voor de zekerheid
$user_id = $_SESSION['user_id'] ?? null; // userid ophalen uit de sessie
$username = $_SESSION['username'] ?? null; // username ophalen uit de sessie

$showAlert = false;
$showError = false;

// Check if the user has already created a quiz in the last 5 minutes
if (isset($_COOKIE['quiz_created'])) {
    $showError = "You can only create one quiz every 5 minutes.";
} else {
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
        if ($user_id === null || $username === null) {
            die("Er is geen User ID of username gevonden. Log in om een quiz te maken. <script ");
        }

        // nieuwe quiz maken van de class uit classes/quiz.php 
        $quiz = new Quiz($quiz_name, $username, $questions);

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

        $showAlert = true;
        // Set a cookie to rate limit quiz creation
        setcookie('quiz_created', 'true', time() + 600); // 300 seconds = 5 minutes
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
    <?php
    if ($showAlert) {
        echo '<div class="alert alert-success">
                <strong>Success!</strong> Your quiz has been created.
              </div>';
    }
    if ($showError) {
        echo '<div class="alert alert-danger">
                <strong>Error!</strong> ' . $showError . '
              </div>';
    }
    ?>
    <div class="createContainer">
        <div class="createBox">
            <form id="quizForm" action="createquiz.php" method="post" onsubmit="return validateForm()">
                <h1>Create a new quiz.</h1>
                <h2>Quiz Name:</h2>
                <input type="text" name="name" placeholder="e.g. Ctrl Alt Quiz!" maxlength="20" minlength="1" required><br><br>

                <div id="questions">
                    <div class="question" id="question1">
                        <h3>Question 1:</h3>
                        <input type="text" name="question1" placeholder="e.g. What is the capital of the Netherlands?" required><br>
                        <span>Answers | Separate answers with a comma if you want more than one answer.</span><br>
                        <input type="text" name="antwoorden1" placeholder="e.g. Amsterdam, Rotterdam, Groningen, Utrecht" max="4" required><br>
                        <span>Correct Answer | Type it exactly as one of the possible answers.</span><br>
                        <input type="text" name="correctanswer1" placeholder="e.g. Amsterdam" required><br><br>
                        <span>Time limit (seconds):</span><br>
                        <input type="number" name="timelimit1" placeholder="e.g. 30" min="1" required><br><br>
                        <button type="button" onclick="removeQuestion(1)">Remove question</button>
                    </div>
                </div>

                <button type="submit">Save quiz</button><br>
                <button type="button" onclick="addQuestion()">Add question</button><br>
            </form>
        </div>
    </div>

    <script>
        let questionCount = 1;
        const maxQuestions = 10; // maximaal aantal vragen

        function addQuestion() {
            if (questionCount >= maxQuestions) { // als je meer dan 10 vragen hebt krijg je een alert
                alert("Je kunt maximaal " + maxQuestions + " vragen toevoegen.");
                return;
            }

            questionCount++;
            const questionsDiv = document.getElementById('questions');
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');
            questionDiv.id = `question${questionCount}`;
            questionDiv.innerHTML = `
                <h3>Question ${questionCount}:</h3>
                <input type="text" name="question${questionCount}" placeholder="e.g. What is the capital of the Netherlands?" max="4" required><br>
                <span>Answers | Separate answers with a comma if you want more than one answer.</span><br>
                <input type="text" name="antwoorden${questionCount}" placeholder="Separate answers with a comma." required><br>
                <span>Correct Answer | Type it exactly as one of the possible answers.</span><br>
                <input type="text" name="correctanswer${questionCount}" placeholder="e.g. Amsterdam" required><br><br>
                <span>Time limit (seconds):</span><br>
                <input type="number" name="timelimit${questionCount}" placeholder="e.g. 30" min="1" required><br><br>
                <button type="button" onclick="removeQuestion(${questionCount})">Remove question</button>
            `;
            questionsDiv.appendChild(questionDiv);
        }

        function removeQuestion(questionNumber) {
            const questionDiv = document.getElementById(`question${questionNumber}`);
            if (questionDiv) {
                questionDiv.remove();
                questionCount--;
            }
        }

        function validateForm() {
            if (questionCount < 1) {
                alert("Je moet minimaal één vraag toevoegen.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>