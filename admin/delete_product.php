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
    $query = "DELETE FROM products WHERE product_id = $id";
    
    if ($conn->query($query)) {
        header("Location: manage_products.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
