<?php
$host = "xxxxxxx";//Redacted
$user = "xxxxxxxxx"; // Redacted
$pass = "xxxxxxx"; // Redacted
$dbname = "supermarket_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
