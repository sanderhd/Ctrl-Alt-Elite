<?php
require_once "../database.php";

$question_id = isset($_GET['question_id']) ? $_GET['question_id'] : 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch options for the question
    $stmt = $conn->prepare("SELECT option_id, option_text, is_correct FROM Options WHERE question_id = :question_id");
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($options);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
