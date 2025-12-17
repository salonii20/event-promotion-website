<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "event_promotion_site"; // Matches your SQL import

// 1. Create MySQLi Connection (Used in Artist.php and Event.php)
$conn = new mysqli($servername, $username, $password, $dbname);
$mysqli = $conn; // Create alias to support files using both variable names

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create PDO Connection (Used in login.php and submit_event.php)
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PDO ERROR: " . $e->getMessage());
}
?>