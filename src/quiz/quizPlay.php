<?php
require_once "../database.php";

$quiz_id = isset($_GET['quizId']) ? $_GET['quizId'] : 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch questions for the quiz
    $stmt = $conn->prepare("SELECT question_id, question_text, time_limit FROM Questions WHERE quiz_id = :quiz_id");
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
    $time_limit = $question['time_limit'];

    // Fetch answers for the first question
    $stmt = $conn->prepare("SELECT option_id, option_text, is_correct FROM Options WHERE question_id = :question_id");
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
    <link rel="stylesheet" href="../level1/CSS/style.css">
</head>
<body>
    <nav>
        <ul class="left-nav">
            <li><img src="assets/ctrlaltelite.png"></li>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../discover.php">Discover</a></li>
            <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="../login.php">Login</a></li>
            <li><a href="../signup.php">Sign up</a></li>
        </ul>
    </nav>

    <h1 id="questionDisplay"><?php echo htmlspecialchars($question_text); ?></h1>
    <h2 id="timeDisplay"><?php echo htmlspecialchars($time_limit); ?> sec</h2>
    
    <div id="optionsContainer">
        <?php foreach ($answers as $answer): ?>
            <button class="optionButton" onclick="selectAnswer(this, <?php echo $answer['is_correct'] ? 'true' : 'false'; ?>)"><?php echo htmlspecialchars($answer['option_text']); ?></button>
        <?php endforeach; ?>
    </div>

    <script>
        let questions = <?php echo json_encode($questions); ?>;
        let currentQuestionIndex = 0;
        let goodAnswers = 0;
        let timer;

        function loadQuestion() {
            if (currentQuestionIndex >= questions.length) {
                alert(`Quiz finished! You got ${goodAnswers} out of ${questions.length} correct.`);
                return;
            }

            const question = questions[currentQuestionIndex];
            document.getElementById('questionDisplay').innerText = question.question_text;
            document.getElementById('timeDisplay').innerText = question.time_limit + ' sec';

            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = '';

            fetch(`getOptions.php?question_id=${question.question_id}`)
                .then(response => response.json())
                .then(options => {
                    options.forEach(option => {
                        const button = document.createElement('button');
                        button.className = 'optionButton';
                        button.innerText = option.option_text;
                        button.onclick = () => selectAnswer(button, option.is_correct);
                        optionsContainer.appendChild(button);
                    });
                });

            startTimer(question.time_limit);
        }

        function startTimer(time) {
            if (timer) {
                clearInterval(timer);
            }

            timer = setInterval(() => {
                document.getElementById('timeDisplay').innerText = time + ' sec';
                time--;

                if (time < 0) {
                    clearInterval(timer);
                    loadNextQuestion();
                }
            }, 1000);
        }

        function selectAnswer(button, isCorrect) {
            clearInterval(timer);

            if (isCorrect) {
                goodAnswers++;
            }

            loadNextQuestion();
        }

        function loadNextQuestion() {
            currentQuestionIndex++;
            loadQuestion();
        }

        window.onload = loadQuestion;
    </script>
</body>
</html>