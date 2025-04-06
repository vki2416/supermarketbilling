<?php
$host = "sql205.infinityfree.com";
$user = "if0_38650806"; // Default user in XAMPP
$pass = "uIemY9vSdIHOrd"; // No password in XAMPP by default
$dbname = "if0_38650806_supermarket_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
