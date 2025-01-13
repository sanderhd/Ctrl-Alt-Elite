<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ctrl_alt_elite";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<script>console.log('Connected successfully');</script>";
} catch(PDOException $e) {
  echo "<script>console.log('Connection failed: " . $e->getMessage() . "');</script>";
}
?>