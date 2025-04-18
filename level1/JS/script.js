// Vragen met antwoorden 
const questions = [
    {
        question: "De hoofdstad van Nederland is:",
        options: ["Amsterdam", "Rotterdam", "Haarlem", "Den Haag"],
        goodAnswer: "Amsterdam",
        time: 10,
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/b/be/KeizersgrachtReguliersgrachtAmsterdam.jpg/1024px-KeizersgrachtReguliersgrachtAmsterdam.jpg"
    },
    {
        question: "Wat is de grootste planeet in ons zonnestelsel?",
        options: ["Aarde", "Mars", "Jupiter", "Saturnus"],
        goodAnswer: "Jupiter",
        time: 30,
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Jupiter_and_its_shrunken_Great_Red_Spot.jpg/800px-Jupiter_and_its_shrunken_Great_Red_Spot.jpg"
    },
    {
        question: "Wat is de hoofdstad van Frankrijk?",
        options: ["Lyon", "Marseille", "Parijs", "Nice"],
        goodAnswer: "Parijs",
        time: 15,
        image: "https://www.zininfrankrijk.nl/wp-content/uploads/2019/03/Parijs-%C3%8Ele-de-France-shutterstock_710380270-660x330.jpg"
    },
    {
        question: "Wie schreef 'Romeo en Julia'?",
        options: ["Charles Dickens", "William Shakespeare", "Mark Twain", "Jane Austen"],
        goodAnswer: "William Shakespeare",
        time: 5,
        image: "https://hips.hearstapps.com/hmg-prod/images/william-shakespeare-194895-1-402.jpg?crop=1xw:1.0xh;center,top&resize=980:*"
    },
    {
        question: "Wat is de grootste oceaan ter wereld?",
        options: ["Atlantische Oceaan", "Indische Oceaan", "Stille Oceaan", "Arctische Oceaan"],
        goodAnswer: "Stille Oceaan",
        time: 10,
        image: "https://cdn.knmi.nl/system/data_center_article_blocks/image1s/000/000/441/large/transportband1.gif?1433258076"
    },
    {
        question: "Wat is de hoofdstad van Japan?",
        options: ["Osaka", "Kyoto", "Tokyo", "Hiroshima"],
        goodAnswer: "Tokyo",
        time: 20,
        image: "https://res.cloudinary.com/hello-tickets/image/upload/c_limit,f_auto,q_auto,w_1300/v1666062109/nfvpcqvorybqcin4j5en.jpg"
    },
    {
        question: "Wie ontdekte penicilline?",
        options: ["Marie Curie", "Alexander Fleming", "Louis Pasteur", "Isaac Newton"],
        goodAnswer: "Alexander Fleming",
        time: 15,
        image: "https://www.nobelprize.org/images/fleming-13037-content-portrait-mobile-tiny.jpg"
    },
    {
        question: "Wat is de langste rivier ter wereld?",
        options: ["Nijl", "Amazonerivier", "Yangtze", "Mississippi"],
        goodAnswer: "Nijl",
        time: 30,
        image: "https://www.natgeojunior.nl/wp-content/uploads/2017/07/Nijl4.jpg"
    },
    {
        question: "Wat is de hoofdstad van Italië?",
        options: ["Milaan", "Rome", "Venetië", "Napels"],
        goodAnswer: "Rome",
        time: 40,
        image: "https://worldstrides.com/wp-content/uploads/2015/07/api268.jpg"
    },
    {
        question: "Wie schilderde de Mona Lisa?",
        options: ["Vincent van Gogh", "Pablo Picasso", "Leonardo da Vinci", "Claude Monet"],
        goodAnswer: "Leonardo da Vinci",
        time: 30,
        image: "https://hips.hearstapps.com/hmg-prod/images/portrait-of-leonardo-da-vinci-1452-1519-getty.jpg?crop=1xw:1.0xh;center,top&resize=980:*"
    }
];

let questionNumber = 0;
let goodAnswers = 0;
let wrongAnswers = 0;

const questionDisplay = document.getElementById("questionDisplay");
const questionImage = document.getElementById('questionImage');
const timeDisplay = document.getElementById('timeDisplay');

if (!questionDisplay || !questionImage || !timeDisplay) {
    console.error("One or more required elements are missing from the DOM.");
}

let timer;

function nextQuestion() {
    loadQuestion(); // Load the next question
}

function countdown() {
    let time = questions[questionNumber].time;

    // timer clearen als hij bestaat
    if (timer) {
        clearInterval(timer);
    }

    // Timer die elke seconde omlaag gaat
    timer = setInterval(() => {
        timeDisplay.innerHTML = time + ' sec';
        time--;

        // stop de tijd als hij op 0 is
        if (time <= 0) {
            clearInterval(timer);
            checkAnswer(); // controleer het antwoord als de tijd 0 is
        }
    }, 1000);
}

function loadQuestion() {
    if (!questionDisplay || !questionImage || !timeDisplay) return;

    const question = questions[questionNumber];
    questionDisplay.innerText = question.question;
    questionImage.src = question.image;

    const optionButtons = document.querySelectorAll('.optionButton');
    optionButtons.forEach((button, index) => {
        button.innerText = question.options[index];
        button.value = question.options[index];
        button.classList.remove('selected');
    });

    countdown(); // Start the timer for each question
}

// Ensure the quiz starts immediately when the page loads
window.onload = function() {
    loadQuestion(); // Start the quiz immediately
}

// selecteer een antwoord en controleer het direct
function selectAnswer(button) {
    const optionButtons = document.querySelectorAll('.optionButton');
    optionButtons.forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');
    clearInterval(timer); // de timer stoppen als er een antwoord gekozen is zodat hij niet verder gaat bij de volgende vraag.
    checkAnswer(button.value);
}

// controleer of het gegeven antwoord goed is
function checkAnswer(selectedAnswer = null) {
    const question = questions[questionNumber];
    const isCorrect = selectedAnswer === question.goodAnswer;

    if (isCorrect) {
        goodAnswers++;
    } else {
        wrongAnswers++;
    }

    questionNumber++;
    if (questionNumber < questions.length) {
        loadQuestion();
    } else {
        // sturen naar result.php met het goede en foute antwoorden
        window.location.href = `result.php?goodAnswers=${goodAnswers}&wrongAnswers=${wrongAnswers}`;
    }
}

// laad de eerste vraag bij het openen van de pagina, en de volgende vraag na het beantwoorden van een vraag
loadQuestion();