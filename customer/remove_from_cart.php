<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    $delete_cart = "DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $conn->query($delete_cart);

    $update_stock = "UPDATE products SET stock_quantity = stock_quantity + $quantity WHERE product_id='$product_id'";
    $conn->query($update_stock);

    header("Location: cart.php");
    exit();
}
?>