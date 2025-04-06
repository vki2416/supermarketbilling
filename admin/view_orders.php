<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch orders
$query = "SELECT * FROM orders ORDER BY order_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
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
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
                <span class="text-2xl font-extrabold">Admin</span>
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
                <a href="feedback_details.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-users text-indigo-300"></i>
                    <span>Feedback</span>
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
                    <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
                    <p class="text-gray-600 text-sm">View and manage all orders</p>
                </div>
            </header>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="order-card bg-white rounded-lg shadow-md p-6 transition duration-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Order #<?php echo $row['order_id']; ?></h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php 
                                        switch($row['delivery_status']) {
                                            case 'Delivered': echo 'bg-green-100 text-green-800'; break;
                                            case 'Processing': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'Shipped': echo 'bg-blue-100 text-blue-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($row['delivery_status']); ?>
                                </span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">User ID:</span> <?php echo htmlspecialchars($row['user_id']); ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Total Amount:</span> ₹<?php echo number_format($row['total_amount'], 2); ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Profit:</span> ₹<?php echo number_format($row['profit_order'], 2); ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Date:</span> <?php echo htmlspecialchars($row['order_date']); ?>
                                </p>
                            </div>
                            <div class="mt-4">
                                <a href="view_order_details.php?order_id=<?php echo $row['order_id']; ?>" 
                                   class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php } ?>
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
        
        close_modal.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
        });
        
        cancelLogout.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
        });
    </script>
</body>
</html>