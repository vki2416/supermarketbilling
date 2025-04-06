<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

$cart_query = "SELECT c.*, p.name, p.price, p.cost_price, p.image_url 
               FROM cart c 
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id='$user_id'";
$cart_result = $conn->query($cart_query);

$total_price = 0;
$total_profit = 0;
$cart_items = [];

while ($row = $cart_result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $profit = ($row['price'] - $row['cost_price']) * $row['quantity'];
    $total_price += $subtotal;
    $total_profit += $profit;
    $cart_items[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['payment_method'] = $_POST['payment_method'];
    $_SESSION['total_price'] = $total_price;
    $_SESSION['total_profit'] = $total_profit;
    header("Location: payment_gateway.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_checkout.css">
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <div class="cart-items">
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="item-details">
                        <p><strong><?php echo $item['name']; ?></strong></p>
                        <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Subtotal: ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="total-amount">
            <p>Total Amount: ₹<?php echo number_format($total_price, 2); ?></p>
        </div>
        <form method="POST" class="payment-form">
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" required>
                <option value="cash">Cash</option>
                <option value="card">Credit/Debit Card</option>
                <option value="UPI">UPI</option>
            </select>
            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
    <footer>
        <p>© 2025 Supermart</p>
    </footer>
</body>
</html>