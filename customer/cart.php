<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

$cart_query = "SELECT c.*, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id='$user_id'";
$cart_result = $conn->query($cart_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermart - Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        h2::after {
            content: '';
            width: 50px;
            height: 3px;
            background: #e74c3c;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .nav-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .btn {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 500;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-secondary {
            background: #34495e;
            border: none;
        }
        .btn-danger {
            background: #e74c3c;
            border: none;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ecf0f1;
        }
        .cart-table th {
            background: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }
        .cart-table td {
            color: #2c3e50;
        }
        .cart-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .cart-table img:hover {
            transform: scale(1.1);
        }
        .cart-table .btn-danger {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        .cart-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #ecf0f1;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .summary-details p {
            margin: 5px 0;
            font-size: 1.1rem;
            color: #34495e;
        }
        .summary-details .total {
            font-size: 1.5rem;
            font-weight: 600;
            color: #27ae60;
        }
        .proceed-btn {
            background: #27ae60;
            border: none;
            padding: 12px 30px;
            font-size: 1.2rem;
        }
        .empty-cart {
            font-size: 1.2rem;
            color: #e74c3c;
            font-weight: 500;
        }
        .cart-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 25px;
            color: white;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }
        .cart-popup.show {
            display: block;
            animation: fadeInOut 2s ease;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            20% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(20px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <div class="nav-links">
            <a href="home.php" class="btn btn-secondary"><i class="bi bi-cart-fill"></i> Continue Shopping</a>
            <a href="../auth/logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                while ($row = $cart_result->fetch_assoc()) {
                    $subtotal = $row['price'] * $row['quantity'];
                    $total_price += $subtotal;
                ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td>₹<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form action="remove_from_cart.php" method="post" class="remove-form">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <input type="hidden" name="quantity" value="<?php echo $row['quantity']; ?>">
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="summary-details">
                <p>Cost: ₹<?php echo number_format($total_price, 2); ?></p>
                <p>GST + VAT (8%): ₹<?php echo number_format($total_price * 0.08, 2); ?></p>
                <p class="total">Total: ₹<?php echo number_format($total_price * 1.08, 2); ?></p>
            </div>
            <?php if ($total_price > 0) { ?>
                <a href="checkout.php" class="btn btn-primary proceed-btn"><i class="bi bi-credit-card"></i> Proceed to Payment</a>
            <?php } else { ?>
                <p class="empty-cart">Your cart is empty.</p>
            <?php } ?>
        </div>
    </div>

    <!-- Popup Notification -->
    <div id="cart-popup" class="cart-popup">
        <p>Removed from Cart!</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Remove from Cart Popup
        document.querySelectorAll('.remove-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const popup = document.getElementById('cart-popup');
                popup.innerHTML = '<p>Removed from Cart!</p>';
                popup.style.background = '#e74c3c'; // Red for removal
                popup.classList.add('show');
                setTimeout(() => {
                    popup.classList.remove('show');
                    form.submit(); // Submit form after popup
                }, 2000);
            });
        });
    </script>
</body>
</html>