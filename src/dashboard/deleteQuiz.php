<?php
require_once "../database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['quizId'])) {
    die("Quiz ID niet gevonden.");
}

$quiz_id = $_GET['quizId'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Begin transaction
    $conn->beginTransaction();

    // Verwijder de opties
    $stmt = $conn->prepare("DELETE FROM Options WHERE question_id IN (SELECT question_id FROM Questions WHERE quiz_id = :quiz_id)");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();

    // Verwijder de vragen
    $stmt = $conn->prepare("DELETE FROM Questions WHERE quiz_id = :quiz_id");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->execute();

    // Verwijder de quiz
    $stmt = $conn->prepare("DELETE FROM Quiz WHERE quiz_id = :quiz_id AND created_by = :username");
    $stmt->bindParam(':quiz_id', $quiz_id);
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    header("Location: dashboard.php");
    exit();
} catch(PDOException $e) {
    // Rollback transaction if something failed
    $conn->rollBack();
    error_log("Connection failed: " . $e->getMessage());
    echo "Connection failed: " . $e->getMessage();
}
?>
