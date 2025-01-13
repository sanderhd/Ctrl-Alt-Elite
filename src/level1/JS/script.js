// Vragen met antwoorden 
const questions = [
    {
        question: "De hoofdstad van Nederland is:",
        options: ["Amsterdam", "Rotterdam", "Haarlem", "Den Haag"],
        goodAnswer: "Amsterdam",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Amsterdam_%28NL%29%2C_Singel%2C_Bloemenmarkt_--_2017_--_1694.jpg/800px-Amsterdam_%28NL%29%2C_Singel%2C_Bloemenmarkt_--_2017_--_1694.jpg"
    },
    {
        question: "Wat is de grootste planeet in ons zonnestelsel?",
        options: ["Aarde", "Mars", "Jupiter", "Saturnus"],
        goodAnswer: "Jupiter",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/Jupiter_and_its_shrunken_Great_Red_Spot.jpg/800px-Jupiter_and_its_shrunken_Great_Red_Spot.jpg"
    },
    {
        question: "Wat is de hoofdstad van Frankrijk?",
        options: ["Lyon", "Marseille", "Parijs", "Nice"],
        goodAnswer: "Parijs",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Paris_-_Eiffelturm_und_Marsfeld2.jpg/800px-Paris_-_Eiffelturm_und_Marsfeld2.jpg"
    },
    {
        question: "Wie schreef 'Romeo en Julia'?",
        options: ["Charles Dickens", "William Shakespeare", "Mark Twain", "Jane Austen"],
        goodAnswer: "William Shakespeare",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Shakespeare.jpg/800px-Shakespeare.jpg"
    },
    {
        question: "Wat is de grootste oceaan ter wereld?",
        options: ["Atlantische Oceaan", "Indische Oceaan", "Stille Oceaan", "Arctische Oceaan"],
        goodAnswer: "Stille Oceaan",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Pacific_Ocean_-_en.png/800px-Pacific_Ocean_-_en.png"
    },
    {
        question: "Wat is de hoofdstad van Japan?",
        options: ["Osaka", "Kyoto", "Tokio", "Hiroshima"],
        goodAnswer: "Tokio",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Tokyo_Skyline.jpg/800px-Tokyo_Skyline.jpg"
    },
    {
        question: "Wie ontdekte penicilline?",
        options: ["Marie Curie", "Alexander Fleming", "Louis Pasteur", "Isaac Newton"],
        goodAnswer: "Alexander Fleming",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Alexander_Fleming.jpg/800px-Alexander_Fleming.jpg"
    },
    {
        question: "Wat is de langste rivier ter wereld?",
        options: ["Nijl", "Amazonerivier", "Yangtze", "Mississippi"],
        goodAnswer: "Nijl",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Nile_River.jpg/800px-Nile_River.jpg"
    },
    {
        question: "Wat is de hoofdstad van Italië?",
        options: ["Milaan", "Rome", "Venetië", "Napels"],
        goodAnswer: "Rome",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Rome_Pantheon_front_view.jpg/800px-Rome_Pantheon_front_view.jpg"
    },
    {
        question: "Wie schilderde de Mona Lisa?",
        options: ["Vincent van Gogh", "Pablo Picasso", "Leonardo da Vinci", "Claude Monet"],
        goodAnswer: "Leonardo da Vinci",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg/800px-Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg"
    }
];

let questionNumber = 0;
let goodAnswers = 0;
let wrongAnswers = 0;

const questionDisplay = document.getElementById("questionDisplay");
const questionText = document.getElementById("questionText");
const questionFeedback = document.getElementById("questionFeedback");
const scoreDisplay = document.getElementById('scoreDisplay');
const questionImage = document.getElementById('questionImage');

// laad de vraag & de mogelijke antwoorden uit de array in de buttons en in h2
function loadQuestion() {
    const question = questions[questionNumber];
    questionDisplay.innerText = `Vraag: ${questionNumber + 1}`;
    questionText.innerText = question.question;
    questionImage.src = question.image;

    const optionButtons = document.querySelectorAll('.optionButton');
    optionButtons.forEach((button, index) => {
        button.innerText = question.options[index];
        button.value = question.options[index];
        button.classList.remove('selected');
    });
}

// selecteer een antwoord en controleer het direct
function selectAnswer(button) {
    const optionButtons = document.querySelectorAll('.optionButton');
    optionButtons.forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');
    checkAnswer(button.value);
}

// controleer of het gegeven antwoord goed is
function checkAnswer(selectedAnswer) {
    const question = questions[questionNumber];

    if (selectedAnswer === question.goodAnswer) {
        goodAnswers++;
        questionFeedback.innerHTML = "Goed!";
        questionFeedback.style.color = "green";
    } else {
        wrongAnswers++;
        questionFeedback.innerHTML = `Fout! Het goede antwoord was ${question.goodAnswer}`;
        questionFeedback.style.color = "red";
    }

    scoreDisplay.innerHTML = `Goed: ${goodAnswers} | Fout: ${wrongAnswers}`;

    questionNumber++;
    if (questionNumber < questions.length) {
        loadQuestion();
    } else {
        scoreDisplay.innerHTML = `Quiz Afgelopen, je hebt ${goodAnswers} goede antwoorden en ${wrongAnswers} foute antwoorden gegeven.`;
    }
}

// laad de eerste vraag bij het openen van de pagina, en de volgende vraag na het beantwoorden van een vraag
loadQuestion();