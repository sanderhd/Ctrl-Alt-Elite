<?php
require_once "./database.php";

// verbind de database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // haal alle quizzes en hun makers op uit de database
    $stmt = $conn->prepare("SELECT quiz_name, created_by FROM Quiz");
    $stmt->execute();

    // zet ze neer in een array
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // debug: print de array met quiz namen en makers
    error_log(print_r($quizzes, true));
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// print de array met quiz namen en makers
print_r($quizzes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>