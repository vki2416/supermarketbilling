<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['payment_method']) || !isset($_SESSION['total_price'])) {
    header("Location: checkout.php");
    exit();
}

$payment_method = $_SESSION['payment_method'];
$total_price = $_SESSION['total_price'];
$total_profit = $_SESSION['total_profit'];
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_query = "INSERT INTO orders (user_id, total_amount, profit_order, status) 
                    VALUES ('$user_id', '$total_price', '$total_profit', 'completed')";
    $conn->query($order_query);
    $order_id = $conn->insert_id;

    $cart_query = "SELECT * FROM cart WHERE user_id='$user_id'";
    $cart_result = $conn->query($cart_query);

    while ($row = $cart_result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $subtotal = $price * $quantity;

        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, subtotal) 
                      VALUES ('$order_id', '$product_id', '$quantity', '$subtotal')");
    }

    $conn->query("INSERT INTO payments (order_id, payment_method, amount_paid, payment_status) 
                  VALUES ('$order_id', '$payment_method', '$total_price', 'completed')");

    $conn->query("DELETE FROM cart WHERE user_id='$user_id'");
    header("Location: order_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_payment_gateway.css">
    <script>
        function showPaymentFields() {
            var method = "<?php echo $payment_method; ?>";
            if (method === "card") document.getElementById("card_fields").style.display = "block";
            else if (method === "UPI") document.getElementById("upi_fields").style.display = "block";
        }

        function validatePayment() {
            var method = "<?php echo $payment_method; ?>";
            var valid = true;

            if (method === "card") {
                var cardNumber = document.getElementById("card_number").value;
                var cvv = document.getElementById("cvv").value;
                if (!/^\d{16}$/.test(cardNumber)) { alert("Invalid Card Number (Must be 16 digits)"); valid = false; }
                if (!/^\d{3}$/.test(cvv)) { alert("Invalid CVV (Must be 3 digits)"); valid = false; }
            } else if (method === "UPI") {
                var upiID = document.getElementById("upi_id").value;
                if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z]+$/.test(upiID)) { alert("Invalid UPI ID format"); valid = false; }
            }

            if (valid) {
                document.getElementById("payment_form").style.display = "none";
                document.getElementById("processing").style.display = "block";
                setTimeout(() => document.getElementById("payment_form").submit(), 3000);
            }
        }

        window.onload = showPaymentFields;
    </script>
</head>
<body>
    <div class="container">
        <h2>Payment Gateway</h2>
        <p>Selected Payment Method: <b><?php echo ucfirst($payment_method); ?></b></p>
        <p>Total Amount: ₹<?php echo number_format($total_price, 2); ?></p>

        <form method="POST" id="payment_form" class="payment-form">
            <div id="card_fields" class="payment-fields">
                <label>Card Number:</label>
                <input type="text" id="card_number" placeholder="1234 5678 9012 3456" maxlength="16" required>
                <label>CVV:</label>
                <input type="password" id="cvv" placeholder="123" maxlength="3" required>
                <label>Expiry Date:</label>
                <input type="month" required>
            </div>
            <div id="upi_fields" class="payment-fields">
                <label>UPI ID:</label>
                <input type="text" id="upi_id" placeholder="example@upi" required>
            </div>
            <button type="button" onclick="validatePayment()">Pay Now</button>
        </form>

        <div id="processing" class="processing">
            <div class="loader"></div>
            <p>Processing Payment... Please wait.</p>
        </div>
    </div>
</body>
</html>