<?php
require 'database.php';

session_start();
$username = $_SESSION['username'] ?? 'Gast'; 

if(isset($_POST['submit'])) {
    $quiz_name = $_POST['quiz_name'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO quizzes (quiz_name, category, question1, answer1_1, answer1_2, answer1_3, answer1_4, correctanswer1, time1, question2, answer2_1, answer2_2, answer2_3, answer2_4, correctanswer2, time2, question3, answer3_1, answer3_2, answer3_3, answer3_4, correctanswer3, time3, question4, answer4_1, answer4_2, answer4_3, answer4_4, correctanswer4, time4, question5, answer5_1, answer5_2, answer5_3, answer5_4, correctanswer5, time5, question6, answer6_1, answer6_2, answer6_3, answer6_4, correctanswer6, time6, question7, answer7_1, answer7_2, answer7_3, answer7_4, correctanswer7, time7, question8, answer8_1, answer8_2, answer8_3, answer8_4, correctanswer8, time8, question9, answer9_1, answer9_2, answer9_3, answer9_4, correctanswer9, time9, question10, answer10_1, answer10_2, answer10_3, answer10_4, correctanswer10, time10) VALUES (:quiz_name, :category, :question1, :answer1_1, :answer1_2, :answer1_3, :answer1_4, :correctanswer1, :time1, :question2, :answer2_1, :answer2_2, :answer2_3, :answer2_4, :correctanswer2, :time2, :question3, :answer3_1, :answer3_2, :answer3_3, :answer3_4, :correctanswer3, :time3, :question4, :answer4_1, :answer4_2, :answer4_3, :answer4_4, :correctanswer4, :time4, :question5, :answer5_1, :answer5_2, :answer5_3, :answer5_4, :correctanswer5, :time5, :question6, :answer6_1, :answer6_2, :answer6_3, :answer6_4, :correctanswer6, :time6, :question7, :answer7_1, :answer7_2, :answer7_3, :answer7_4, :correctanswer7, :time7, :question8, :answer8_1, :answer8_2, :answer8_3, :answer8_4, :correctanswer8, :time8, :question9, :answer9_1, :answer9_2, :answer9_3, :answer9_4, :correctanswer9, :time9, :question10, :answer10_1, :answer10_2, :answer10_3, :answer10_4, :correctanswer10, :time10)");
    
    $stmt->bindParam(':quiz_name', $quiz_name);
    $stmt->bindParam(':category', $category);
    
    for ($i = 1; $i <= 10; $i++) {
        $stmt->bindParam(":question$i", $_POST["question$i"]);
        $stmt->bindParam(":answer{$i}_1", $_POST["answer{$i}_1"]);
        $stmt->bindParam(":answer{$i}_2", $_POST["answer{$i}_2"]);
        $stmt->bindParam(":answer{$i}_3", $_POST["answer{$i}_3"]);
        $stmt->bindParam(":answer{$i}_4", $_POST["answer{$i}_4"]);
        $stmt->bindParam(":correctanswer$i", $_POST["correctanswer$i"]);
        $stmt->bindParam(":time$i", $_POST["time$i"]);
    }

    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
</head>
<body>
    <h1>Hallo! <?php echo htmlspecialchars($username); ?></h1>
    <form action="logout.php">
        <button type="submit">Sign Out</button>
    </form>

    <form method="POST" action="dashboard.php">
        <h2>Maak een quiz</h2>
        
        <input type="text" name="quiz_name" id="quiz_name" placeholder="Quiz naam" required>
        <input type="text" name="category" id="category" placeholder="Categorie" required>

        <h3>Vraag 1</h3>
        <input type="text" name="question1" id="question1" placeholder="Vraag 1" required>
        <input type="text" name="answer1_1" id="answer1_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer1_2" id="answer1_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer1_3" id="answer1_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer1_4" id="answer1_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer1" id="correctanswer1" placeholder="Goede Antwoord" required>
        <input type="number" name="time1" id="time1" placeholder="Tijd in seconden" required>

        <h3>Vraag 2</h3>
        <input type="text" name="question2" id="question2" placeholder="Vraag 2" required>
        <input type="text" name="answer2_1" id="answer2_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer2_2" id="answer2_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer2_3" id="answer2_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer2_4" id="answer2_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer2" id="correctanswer2" placeholder="Goede Antwoord" required>
        <input type="number" name="time2" id="time2" placeholder="Tijd in seconden" required>

        <h3>Vraag 3</h3>
        <input type="text" name="question3" id="question3" placeholder="Vraag 3" required>
        <input type="text" name="answer3_1" id="answer3_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer3_2" id="answer3_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer3_3" id="answer3_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer3_4" id="answer3_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer3" id="correctanswer3" placeholder="Goede Antwoord" required>
        <input type="number" name="time3" id="time3" placeholder="Tijd in seconden" required>

        <h3>Vraag 4</h3>
        <input type="text" name="question4" id="question4" placeholder="Vraag 4" required>
        <input type="text" name="answer4_1" id="answer4_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer4_2" id="answer4_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer4_3" id="answer4_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer4_4" id="answer4_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer4" id="correctanswer4" placeholder="Goede Antwoord" required>
        <input type="number" name="time4" id="time4" placeholder="Tijd in seconden" required>

        <h3>Vraag 5</h3>
        <input type="text" name="question5" id="question5" placeholder="Vraag 5" required>
        <input type="text" name="answer5_1" id="answer5_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer5_2" id="answer5_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer5_3" id="answer5_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer5_4" id="answer5_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer5" id="correctanswer5" placeholder="Goede Antwoord" required>
        <input type="number" name="time5" id="time5" placeholder="Tijd in seconden" required>

        <h3>Vraag 6</h3>
        <input type="text" name="question6" id="question6" placeholder="Vraag 6" required>
        <input type="text" name="answer6_1" id="answer6_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer6_2" id="answer6_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer6_3" id="answer6_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer6_4" id="answer6_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer6" id="correctanswer6" placeholder="Goede Antwoord" required>
        <input type="number" name="time6" id="time6" placeholder="Tijd in seconden" required>

        <h3>Vraag 7</h3>
        <input type="text" name="question7" id="question7" placeholder="Vraag 7" required>
        <input type="text" name="answer7_1" id="answer7_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer7_2" id="answer7_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer7_3" id="answer7_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer7_4" id="answer7_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer7" id="correctanswer7" placeholder="Goede Antwoord" required>
        <input type="number" name="time7" id="time7" placeholder="Tijd in seconden" required>

        <h3>Vraag 8</h3>
        <input type="text" name="question8" id="question8" placeholder="Vraag 8" required>
        <input type="text" name="answer8_1" id="answer8_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer8_2" id="answer8_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer8_3" id="answer8_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer8_4" id="answer8_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer8" id="correctanswer8" placeholder="Goede Antwoord" required>
        <input type="number" name="time8" id="time8" placeholder="Tijd in seconden" required>

        <h3>Vraag 9</h3>
        <input type="text" name="question9" id="question9" placeholder="Vraag 9" required>
        <input type="text" name="answer9_1" id="answer9_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer9_2" id="answer9_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer9_3" id="answer9_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer9_4" id="answer9_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer9" id="correctanswer9" placeholder="Goede Antwoord" required>
        <input type="number" name="time9" id="time9" placeholder="Tijd in seconden" required>

        <h3>Vraag 10</h3>
        <input type="text" name="question10" id="question10" placeholder="Vraag 10" required>
        <input type="text" name="answer10_1" id="answer10_1" placeholder="Antwoord 1" required>
        <input type="text" name="answer10_2" id="answer10_2" placeholder="Antwoord 2" required>
        <input type="text" name="answer10_3" id="answer10_3" placeholder="Antwoord 3" required>
        <input type="text" name="answer10_4" id="answer10_4" placeholder="Antwoord 4" required>
        <input type="text" name="correctanswer10" id="correctanswer10" placeholder="Goede Antwoord" required>
        <input type="number" name="time10" id="time10" placeholder="Tijd in seconden" required>
        
        <br>
        <button type="submit" name="submit">Maak quiz</button>
    </form>
</body>
</html>