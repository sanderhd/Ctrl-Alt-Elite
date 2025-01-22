<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl Alt Elite</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php
    $goodAnswers = isset($_GET['goodAnswers']) ? $_GET['goodAnswers'] : 0;
    $wrongAnswers = isset($_GET['wrongAnswers']) ? $_GET['wrongAnswers'] : 0;
    ?>
    <span id="goodAnswerDisplay">Good Answers: <?php echo $goodAnswers; ?></span><br>
    <span id="wrongAnswerDisplay">Wrong Answers: <?php echo $wrongAnswers; ?></span>

    <script src="JS/script.js"></script>
</body>
</html>