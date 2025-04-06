<?php
session_start();
include '../config/db.php'; // Ensure database connection

// 🔹 Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 🔹 Validate if `order_id` and `delivery_status` are set
    if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
        die("Missing required fields.");
    }

    $order_id = $_POST['order_id'];
    $delivery_status = $_POST['status'];

    // 🔹 Ensure valid status values
    $allowed_statuses = ["Pending", "Shipped", "Delivered", "Cancelled"];
    if (!in_array($delivery_status, $allowed_statuses)) {
        die("Invalid status value.");
    }

    // 🔹 Update query
    $update_query = "UPDATE orders SET delivery_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $delivery_status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully.";
        header("Location: view_orders.php");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
} else {
    die("Invalid request.");
}

$conn->close();
?>
