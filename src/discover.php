<?php
require_once "./database.php";

// verbind de database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // haal alle quizzes en de ids en hun makers op uit de database
    $stmt = $conn->prepare("SELECT quiz_name, created_by, quiz_id FROM Quiz");
    $stmt->execute();

    // quizen ophalen en in een array zetten
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // debuggen
    error_log(print_r($quizzes, true));
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover</title>
    <link rel="stylesheet" href="CSS/discover.css">
    <link rel="shortcut icon" href="./assets/favicon.ico" type="image/x-icon">
</head>
<body>
    <nav>
        <ul class="left-nav">
            <li><img src="assets/ctrlaltelite.png"></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="discover.php">Discover</a></li>
            <li><a href="dashboard/dashboard.php">Dashboard</a></li>
        </ul>
        <ul class="right-nav">
            <li><a href="login.php">Login</a></li>
            <li><a href="signup.php">Sign up</a></li>
        </ul>
    </nav>

    <div class="searchContainer">
        <h1>Available Quizzes</h1>
        <input type="text" id="searchInput" onkeyup="searchQuiz()" placeholder="Search for quiz names..">
       
    </div>

    <div class="quizContainer" id="quizContainer">
        <?php foreach ($quizzes as $quiz): ?> <!-- loop door alle quizen en in een div neerzetten -->
            <div class="quizBox">
                <h3><?php echo htmlspecialchars($quiz['quiz_name']); ?></h3>  <!--  quiz naam pakken uit de array en neerzetten in h3 -->
                <h4>Created by: <?php echo htmlspecialchars($quiz['created_by']); ?></h4> <!-- quiz maker pakken uit de array en neerzetten in h4 -->
                
                <button onclick="playQuiz(<?php echo htmlspecialchars($quiz['quiz_id']); ?>)">Play Quiz</button>
                
                <button onclick="shareQuiz(<?php echo htmlspecialchars($quiz['quiz_id']); ?>)">Share</button>
            </div>
        <?php endforeach; ?> <!-- einde van de loop -->
    </div>

    <script>
        function searchQuiz() {
            let h3, i, txtValue; // variabelen voor de functie
            let input = document.getElementById('searchInput');
            let filter = input.value.toUpperCase(); // de filter die je zoekt
            let container = document.getElementById('quizContainer'); // container
            let boxes = container.getElementsByClassName('quizBox'); //vakjes met quizzen in de container
            
            // loop door alle quizen en hide de quizen die niet gezocht worden
            for (i = 0; i < boxes.length; i++) {
                h3 = boxes[i].getElementsByTagName('h3')[0]; // de quiz naam in de box van de quiz
                txtValue = h3.textContent || h3.innerText; // de value van de quiz naam in de box
                if (txtValue.toUpperCase().indexOf(filter) > -1) { // als de quiz naam hetzelfde is als wat je zoekt
                    boxes[i].style.display = ''; // quiz laten zien die je zoekt
                } else {
                    boxes[i].style.display = 'none'; // niks laten zien
                }
            }
        }

        function playQuiz(quizId) {
            window.location.href = 'quiz/playQuiz.php?quizId=' + quizId; 
        }

        function shareQuiz(quizId) {
            const quizLink = `https://ctrlaltelite.online/quiz/playQuiz.php?quizId=${quizId}`;
            navigator.clipboard.writeText(quizLink).then(() => {
                alert('Quiz link copied to clipboard: ' + quizLink);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>
</html>