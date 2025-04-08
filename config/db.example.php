<?php
// Database configuration
// Rename this file to db.php and update with your actual credentials

$host = "localhost"; // Database host
$user = "username"; // Database username
$pass = "password"; // Database password
$dbname = "supermarket_db"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 