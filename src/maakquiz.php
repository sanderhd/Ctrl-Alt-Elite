<?php
require_once "database.php";
require_once "classes/quiz.php";

session_start(); // Ensure session is started
$user_id = $_SESSION['user_id'] ?? null; // Get user_id from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_name = $_POST['name'];
    $questions = [];

    for ($i = 1; isset($_POST["question$i"]); $i++) {
        $question_text = $_POST["question$i"];
        $answers = explode(',', $_POST["antwoorden$i"]);
        $correct_answer = $_POST["correctanswer$i"];
        $questions[] = [
            'question_text' => $question_text,
            'answers' => $answers,
            'correct_answer' => $correct_answer
        ];
    }

    // Check if user_id is set
    if ($user_id === null) {
        die("User ID is not set. Please log in.");
    }

    // Create a new Quiz object
    $quiz = new Quiz($quiz_name, $user_id, $questions);

    // Insert quiz into database
    $quizName = $quiz->getName();
    $quizCreator = $quiz->getCreator();
    $stmt = $conn->prepare("INSERT INTO Quiz (quiz_name, created_by) VALUES (:quiz_name, :created_by)");
    $stmt->bindParam(':quiz_name', $quizName);
    $stmt->bindParam(':created_by', $quizCreator);
    $stmt->execute();
    $quiz_id = $conn->lastInsertId();

    // Insert questions and answers into database
    foreach ($quiz->getQuestions() as $question) {
        $questionText = $question['question_text'];
        $stmt = $conn->prepare("INSERT INTO Questions (quiz_id, question_text) VALUES (:quiz_id, :question_text)");
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question_text', $questionText);
        $stmt->execute();
        $question_id = $conn->lastInsertId();

        foreach ($question['answers'] as $answer) {
            $is_correct = ($answer === $question['correct_answer']) ? 1 : 0; // Set $is_correct based on the correct answer
            $optionText = trim($answer);
            $stmt = $conn->prepare("INSERT INTO Options (question_id, option_text, is_correct) VALUES (:question_id, :option_text, :is_correct)");
            $stmt->bindParam(':question_id', $question_id);
            $stmt->bindParam(':option_text', $optionText); // Trim any extra spaces around the answer
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
</head>
<body>
    <form action="maakquiz.php" method="post">
        <span>Quiz Naam:</span><br>
        <input type="text" name="name" placeholder="Bijv. Ctrl Alt Quiz!" required><br><br>

        <div id="questions">
            <div class="question">
                <span>Vraag 1:</span><br>
                <input type="text" name="question1" placeholder="Bijv. Wat is de hoofdstad van Nederland?" required><br>
                <input type="text" name="antwoorden1" placeholder="Zet een comma tussen de antwoorden." required><br>
                <input type="text" name="correctanswer1" placeholder="Bijv. Amsterdam" required><br><br>
            </div>
        </div>

        <button type="submit">Sla quiz op</button><br>
        <button type="button" onclick="addQuestion()">Voeg vraag toe</button><br>
    </form>

    <script>
        let questionCount = 1;

        function addQuestion() {
            questionCount++;
            const questionsDiv = document.getElementById('questions');
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');
            questionDiv.innerHTML = `
                <span>Vraag ${questionCount}:</span><br>
                <input type="text" name="question${questionCount}" placeholder="Bijv. Wat is de hoofdstad van Nederland?" required><br>
                <input type="text" name="antwoorden${questionCount}" placeholder="Zet een comma tussen de antwoorden." required><br>
                <input type="text" name="correctanswer${questionCount}" placeholder="Bijv. Amsterdam" required><br><br>
            `;
            questionsDiv.appendChild(questionDiv);
        }
    </script>
</body>
</html>