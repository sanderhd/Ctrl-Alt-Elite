-- User table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text VARCHAR(255) 
);

CREATE TABLE Options (
    option_id INT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text VARCHAR(255),
    is_correct BOOLEAN NOT NULL
);

CREATE TABLE Quiz (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_name VARCHAR(255),
    created_by INT NOT NULL
);
