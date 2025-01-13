<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl Alt Elite</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <h1 id="questionDisplay">Vraag 1</h1>
    <p id="questionText"></p>
    <img src="" alt="Question Image" id="questionImage">
    
    <div id="optionsContainer">
        <button class="optionButton purple" onclick="selectAnswer(this)"></button>
        <button class="optionButton green" onclick="selectAnswer(this)"></button>
        <button class="optionButton blue" onclick="selectAnswer(this)"></button>
        <button class="optionButton red" onclick="selectAnswer(this)"></button>
    </div>
    
    <p id="questionFeedback">Nog geen antwoord gegeven</p>

    <div id="result">
        <p id="scoreDisplay">Goed: 0 | Fout: 0</p>
    </div>
    <script src="JS/script.js"></script>
</body>
</html>