<?php
$servername = "185.228.82.23";
$username = "u1_rZIAosUY0B";
$password = "OYX3sdlB^jsPnDGgDgJEcZ9c";
$dbname = "s1_ctrlaltelite";
 
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<script>console.log('Connected successfully');</script>";
} catch(PDOException $e) {
  echo "<script>console.log('Connection failed: " . $e->getMessage() . "');</script>";
}
?>