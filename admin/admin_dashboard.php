<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch total sales
$sales_query = "SELECT SUM(total_amount) AS total_sales FROM orders";
$sales_result = $conn->query($sales_query);
$total_sales = $sales_result->fetch_assoc()['total_sales'] ?? 0;

// Fetch total profit
$profit_query = "SELECT SUM(profit_order) AS total_profit FROM orders";
$profit_result = $conn->query($profit_query);
$total_profit = $profit_result->fetch_assoc()['total_profit'] ?? 0;

// Fetch total employee salary
$emp_salary_query = "SELECT SUM(salary) AS total_salary FROM employees";
$emp_salary_result = $conn->query($emp_salary_query);
$emp_salary = $emp_salary_result->fetch_assoc()['total_salary'] ?? 0;

// Fetch total products
$products_query = "SELECT COUNT(*) AS total_products FROM products";
$products_result = $conn->query($products_query);
$total_products = $products_result->fetch_assoc()['total_products'] ?? 0;

// Fetch total orders
$orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$orders_result = $conn->query($orders_query);
$total_orders = $orders_result->fetch_assoc()['total_orders'] ?? 0;

// Fetch Top 5 Fast-Selling Products
$fast_selling_query = "
    SELECT p.name, COUNT(oi.product_id) AS sold_count 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    GROUP BY oi.product_id, p.name
    ORDER BY sold_count DESC
    LIMIT 5
";
$fast_selling_result = $conn->query($fast_selling_query);

// Fetch Top 5 Low Stock Products
$low_stock_query = "
    SELECT name, stock_quantity
    FROM products 
    ORDER BY stock_quantity ASC 
    LIMIT 5
";
$low_stock_result = $conn->query($low_stock_query);

// Fetch initial sales chart data (last 90 days)
$sales_chart_query = "
    SELECT 
        DATE_FORMAT(order_date, '%b') as month,
        SUM(total_amount) as monthly_sales
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)
    GROUP BY DATE_FORMAT(order_date, '%b'), MONTH(order_date)
    ORDER BY MIN(order_date) ASC
";
$sales_chart_result = $conn->query($sales_chart_query);

$months = [];
$sales_data = [];
while ($row = $sales_chart_result->fetch_assoc()) {
    $months[] = $row['month'];
    $sales_data[] = (float)$row['monthly_sales'];
}
$months_json = json_encode($months);
$sales_data_json = json_encode($sales_data);

// Handle AJAX request for chart updates
if (isset($_GET['get_sales_data'])) {
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 90;
    $query = "
        SELECT 
            DATE_FORMAT(order_date, '%b') as month,
            SUM(total_amount) as monthly_sales
        FROM orders
        WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE_FORMAT(order_date, '%b'), MONTH(order_date)
        ORDER BY MIN(order_date) ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $days);
    $stmt->execute();
    $result = $stmt->get_result();

    $months = [];
    $sales = [];
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $sales[] = (float)$row['monthly_sales'];
    }
    header('Content-Type: application/json');
    echo json_encode(['months' => $months, 'sales' => $sales]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            height: 300px;
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
                <a href="admin_dashboard.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200 bg-indigo-700">
                    <i class="fas fa-home text-indigo-300"></i>
                    <span>Dashboard</span>
                </a>
                <a href="./manage_products.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-shopping-cart text-indigo-300"></i>
                    <span>Products</span>
                </a>
                <a href="./view_orders.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-list text-indigo-300"></i>
                    <span>Orders</span>
                </a>
                <a href="./employee_details.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
                    <i class="fas fa-users text-indigo-300"></i>
                    <span>Employees</span>
                </a>
                <a href="./feedback_details.php" class="sidebar-link flex items-center space-x-2 py-3 px-4 rounded transition duration-200">
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
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            </header>

            <div class="p-6">
                <!-- Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                    <div class="card bg-white rounded-lg shadow-md p-6 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Sales</p>
                                <h3 class="text-2xl font-bold mt-2">₹<?php echo number_format($total_sales, 2); ?></h3>
                            </div>
                            <div class="bg-indigo-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-lg shadow-md p-6 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Profit</p>
                                <h3 class="text-2xl font-bold mt-2">₹<?php echo number_format($total_profit, 2); ?></h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-lg shadow-md p-6 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Employee Salary</p>
                                <h3 class="text-2xl font-bold mt-2">₹<?php echo number_format($emp_salary, 2); ?></h3>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-users text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-lg shadow-md p-6 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Orders</p>
                                <h3 class="text-2xl font-bold mt-2"><?php echo $total_orders; ?></h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-white rounded-lg shadow-md p-6 transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Products</p>
                                <h3 class="text-2xl font-bold mt-2"><?php echo $total_products; ?></h3>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-box text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart and Product Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Sales Overview</h2>
                            <select id="timeRange" class="bg-gray-100 border-0 rounded-lg px-3 py-1 text-sm">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90" selected>Last 90 days</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Top Selling Products</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php while ($row = $fast_selling_result->fetch_assoc()) { ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $row['name']; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $row['sold_count']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Low Stock Products</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($row = $low_stock_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $row['name']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $row['stock_quantity']; ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo $months_json; ?>,
                datasets: [{
                    label: 'Sales',
                    data: <?php echo $sales_data_json; ?>,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        function updateChart(days) {
            fetch(`admin_dashboard.php?get_sales_data=true&days=${days}`)
                .then(response => response.json())
                .then(data => {
                    salesChart.data.labels = data.months;
                    salesChart.data.datasets[0].data = data.sales;
                    salesChart.update();
                });
        }

        document.getElementById('timeRange').addEventListener('change', (e) => {
            updateChart(e.target.value);
        });

        setInterval(() => updateChart(document.getElementAById('timeRange').value), 30000);
    </script>
</body>
</html>