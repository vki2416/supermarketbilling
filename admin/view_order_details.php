<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get Order ID from URL
if (!isset($_GET['order_id'])) {
    header("Location: view_orders.php");
    exit();
}

$order_id = $conn->real_escape_string($_GET['order_id']);

// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = $order_id";
$order_result = $conn->query($order_query);
$order = $order_result->fetch_assoc();

// Fetch ordered items
$items_query = "SELECT oi.*, p.name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = $order_id";
$items_result = $conn->query($items_query);

// Fetch payment details
$payment_query = "SELECT * FROM payments WHERE order_id = $order_id";
$payment_result = $conn->query($payment_query);
$payment = $payment_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - #<?php echo $order_id; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile menu button -->
        <div class="md:hidden fixed top-4 left-4 z-50">
            <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-indigo-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform md:relative md:translate-x-0">
            <div class="flex items-center space-x-2 px-4">
                <i class="fas fa-cube text-2xl text-indigo-300"></i>
                <span class="text-2xl font-extrabold">AdminPro</span>
            </div>
            <nav>
                <a href="admin_dashboard.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-home text-indigo-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="manage_products.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-shopping-cart text-indigo-300"></i>
                    <span>Products</span>
                </a>
                <a href="view_orders.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200 bg-indigo-700">
                    <i class="fas fa-list text-indigo-300"></i>
                    <span>Orders</span>
                </a>
                <a href="employee_details.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-users text-indigo-300"></i>
                    <span>Employees</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full px-4 py-6">
                <button id="logout-btn" class="w-full flex items-center justify-center space-x-2 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto">
            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Order Details - #<?php echo $order_id; ?></h1>
                    <p class="text-gray-600 text-sm">View and update order information</p>
                </div>
                <a href="view_orders.php" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                </a>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Customer Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h2>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">User ID:</span> <?php echo htmlspecialchars($order['user_id']); ?>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Order Date:</span> <?php echo htmlspecialchars($order['order_date']); ?>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php 
                                        switch($order['status']) {
                                            case 'Delivered': echo 'bg-green-100 text-green-800'; break;
                                            case 'Processing': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'Shipped': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'Cancelled': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h2>
                        <?php if ($payment) { ?>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Payment Method:</span> <?php echo htmlspecialchars($payment['payment_method']); ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Amount Paid:</span> ₹<?php echo number_format($payment['amount_paid'], 2); ?>
                                </p>
                            </div>
                        <?php } else { ?>
                            <p class="text-sm text-gray-600"><strong>No payment details found</strong></p>
                        <?php } ?>
                    </div>

                    <!-- Update Status -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Update Order Status</h2>
                        <form action="update_order_status.php" method="post" class="space-y-4">
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                            <select name="status" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="Pending" <?php if ($order['status'] == 'Pending') echo "selected"; ?>>Pending</option>
                                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo "selected"; ?>>Shipped</option>
                                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo "selected"; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo "selected"; ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Ordered Items -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ordered Items</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($item = $items_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Confirm Logout</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-6">Are you sure you want to logout from your account?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancel-logout" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                <a href="../auth/logout.php" id="confirm-logout" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Logout</a>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        
        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Logout modal
        const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const closeModal = document.getElementById('close-modal');
        const cancelLogout = document.getElementById('cancel-logout');
        
        logoutBtn.addEventListener('click', () => {
            logoutModal.classList.remove('hidden');
        });
        
        closeModal.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
        });
        
        cancelLogout.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
        });
    </script>
</body>
</html>