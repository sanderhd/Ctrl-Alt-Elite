<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../database.php";

$user_id = $_SESSION['user_id'];

// Delete the user's account from the database
$stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();

// Destroy the session and redirect to the login page
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>
