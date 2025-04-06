<?php
$host = "xxxxxxx";
$user = "xxxxxxxxx"; // Default user in XAMPP
$pass = "xxxxxxx"; // No password in XAMPP by default
$dbname = "supermarket_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
