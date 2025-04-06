<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Delete product
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM employees WHERE employee_id = $id";
    
    if ($conn->query($query)) {
        header("Location: employee_details.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
