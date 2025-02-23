<?php
$host = "localhost";
$dbname = "db_barangaymanagement";
$username = "root";
$password = "";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Set PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Set character set to utf8mb4
  $pdo->exec("SET NAMES utf8mb4");

} catch (PDOException $e) {
  // Log error to file instead of displaying
  error_log("Database connection failed: " . $e->getMessage());
  header("Location: error.php"); // Redirect to error page
  exit();
}
?>
