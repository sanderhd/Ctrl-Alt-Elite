<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    
    <h1 id="questionDisplay">Vraag komt hier...</h1>
    <h2 id="timeDisplay">Timer</h2>
    <img src="" alt="Vraag afbeelding" id="questionImage">

    <div id="optionsContainer">
        <button class="optionButton red" onclick="selectAnswer(this)">A</button>
        <button class="optionButton blue" onclick="selectAnswer(this)">B</button>
        <button class="optionButton green" onclick="selectAnswer(this)">C</button>
        <button class="optionButton purple" onclick="selectAnswer(this)">D</button>
    </div>

    <script src="JS/script.js"></script>
</body>
</html>
