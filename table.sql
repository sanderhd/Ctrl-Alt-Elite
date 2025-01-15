-- User table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Quizz Table
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    question1 TEXT NOT NULL,
    answer1_1 TEXT NOT NULL,
    answer1_2 TEXT NOT NULL,
    answer1_3 TEXT NOT NULL,
    answer1_4 TEXT NOT NULL,
    correctanswer1 TEXT NOT NULL,
    time1 INT NOT NULL,
    question2 TEXT NOT NULL,
    answer2_1 TEXT NOT NULL,
    answer2_2 TEXT NOT NULL,
    answer2_3 TEXT NOT NULL,
    answer2_4 TEXT NOT NULL,
    correctanswer2 TEXT NOT NULL,
    time2 INT NOT NULL,
    question3 TEXT NOT NULL,
    answer3_1 TEXT NOT NULL,
    answer3_2 TEXT NOT NULL,
    answer3_3 TEXT NOT NULL,
    answer3_4 TEXT NOT NULL,
    correctanswer3 TEXT NOT NULL,
    time3 INT NOT NULL,
    question4 TEXT NOT NULL,
    answer4_1 TEXT NOT NULL,
    answer4_2 TEXT NOT NULL,
    answer4_3 TEXT NOT NULL,
    answer4_4 TEXT NOT NULL,
    correctanswer4 TEXT NOT NULL,
    time4 INT NOT NULL,
    question5 TEXT NOT NULL,
    answer5_1 TEXT NOT NULL,
    answer5_2 TEXT NOT NULL,
    answer5_3 TEXT NOT NULL,
    answer5_4 TEXT NOT NULL,
    correctanswer5 TEXT NOT NULL,
    time5 INT NOT NULL,
    question6 TEXT NOT NULL,
    answer6_1 TEXT NOT NULL,
    answer6_2 TEXT NOT NULL,
    answer6_3 TEXT NOT NULL,
    answer6_4 TEXT NOT NULL,
    correctanswer6 TEXT NOT NULL,
    time6 INT NOT NULL,
    question7 TEXT NOT NULL,
    answer7_1 TEXT NOT NULL,
    answer7_2 TEXT NOT NULL,
    answer7_3 TEXT NOT NULL,
    answer7_4 TEXT NOT NULL,
    correctanswer7 TEXT NOT NULL,
    time7 INT NOT NULL,
    question8 TEXT NOT NULL,
    answer8_1 TEXT NOT NULL,
    answer8_2 TEXT NOT NULL,
    answer8_3 TEXT NOT NULL,
    answer8_4 TEXT NOT NULL,
    correctanswer8 TEXT NOT NULL,
    time8 INT NOT NULL,
    question9 TEXT NOT NULL,
    answer9_1 TEXT NOT NULL,
    answer9_2 TEXT NOT NULL,
    answer9_3 TEXT NOT NULL,
    answer9_4 TEXT NOT NULL,
    correctanswer9 TEXT NOT NULL,
    time9 INT NOT NULL,
    question10 TEXT NOT NULL,
    answer10_1 TEXT NOT NULL,
    answer10_2 TEXT NOT NULL,
    answer10_3 TEXT NOT NULL,
    answer10_4 TEXT NOT NULL,
    correctanswer10 TEXT NOT NULL,
    time10 INT NOT NULL
);
