<?php
session_start();
include '../config/db.php';

// Set the response header to JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? null;
    $quantity = (int) ($_POST['quantity'] ?? 1); // Default to 1 if not provided

    // Validate product_id
    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit();
    }

    // Fetch current stock using prepared statement
    $stock_query = "SELECT stock_quantity FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($stock_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stock_result = $stmt->get_result();
    $stock_row = $stock_result->fetch_assoc();
    $available_stock = (int) $stock_row['stock_quantity'];

    // Check if enough stock is available
    if ($quantity > $available_stock) {
        echo json_encode(['success' => false, 'message' => "Only $available_stock items available!"]);
        exit();
    }

    // Update stock in products table
    $new_stock = $available_stock - $quantity;
    $update_stock = "UPDATE products SET stock_quantity = ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_stock);
    $update_stmt->bind_param("ii", $new_stock, $product_id);
    $update_stmt->execute();

    // Check if product is already in cart
    $cart_query = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("ii", $user_id, $product_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        // Update existing cart item
        $update_cart = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $update_cart_stmt = $conn->prepare($update_cart);
        $update_cart_stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $update_cart_stmt->execute();
    } else {
        // Insert new cart item
        $insert_cart = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_cart_stmt = $conn->prepare($insert_cart);
        $insert_cart_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_cart_stmt->execute();
    }

    // Return success response with new stock
    echo json_encode(['success' => true, 'new_stock' => $new_stock]);
    exit();
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>