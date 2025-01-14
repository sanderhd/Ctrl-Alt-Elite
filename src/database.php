<?php
$servername = "82.165.51.200:3306";
$username = "u44_jYBa9mK8fn";
$password = "ny8YSdxf859YDnT@5=jsvUPR";
$dbname = "s44_sander";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<script>console.log('Connected successfully');</script>";
} catch(PDOException $e) {
  echo "<script>console.log('Connection failed: " . $e->getMessage() . "');</script>";
}
?>