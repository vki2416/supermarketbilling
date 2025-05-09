<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch product details
$product = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE product_id = $id";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST" && $product) {
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = floatval($_POST['price']);
    $cost_price = floatval($_POST['cost_price']);
    $stock = intval($_POST['stock']);
    $description = $conn->real_escape_string($_POST['description']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $update_query = "UPDATE products 
                     SET name='$name', 
                         category='$category', 
                         price=$price, 
                         cost_price=$cost_price, 
                         stock_quantity=$stock, 
                         description='$description',
                         image_url='$image_url'
                     WHERE product_id=$id";

    if ($conn->query($update_query)) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <header class="bg-blue-600 text-white shadow-md">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-box-open mr-2"></i> Inventory System
                </h1>
                <nav>
                    <a href="manage_products.php" class="px-3 py-2 rounded hover:bg-blue-700 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </nav>
            </div>
        </header>

        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-edit mr-3"></i> Edit Product
                    </h2>
                    <p class="mt-1 opacity-90">Modify the details below to update the product</p>
                </div>

                <?php if ($product) { ?>
                <form method="POST" class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-tag mr-2 text-blue-500"></i> Product Name
                            </label>
                            <input type="text" id="name" name="name" required
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                value="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-list-alt mr-2 text-blue-500"></i> Category
                            </label>
                            <input type="text" id="category" name="category" required
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                value="<?php echo htmlspecialchars($product['category']); ?>">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-align-left mr-2 text-blue-500"></i> Description
                            </label>
                            <textarea id="description" name="description" rows="3" required
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-dollar-sign mr-2 text-blue-500"></i> Selling Price
                                </label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                    class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="<?php echo htmlspecialchars($product['price']); ?>">
                            </div>
                            <div>
                                <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-money-bill-wave mr-2 text-blue-500"></i> Cost Price
                                </label>
                                <input type="number" id="cost_price" name="cost_price" step="0.01" min="0" required
                                    class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                    value="<?php echo htmlspecialchars($product['cost_price']); ?>">
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-boxes mr-2 text-blue-500"></i> Stock Quantity
                            </label>
                            <input type="number" id="stock" name="stock" min="0" required
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                value="<?php echo htmlspecialchars($product['stock_quantity']); ?>">
                        </div>

                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-image mr-2 text-blue-500"></i> Image URL
                            </label>
                            <input type="text" id="image_url" name="image_url" required
                                class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                value="<?php echo htmlspecialchars($product['image_url']); ?>">
                        </div>
                    </div>

                    <div class="md:col-span-2 pt-4 border-t border-gray-200 flex justify-end">
                        <button type="reset" class="px-6 py-2 mr-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                        <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
                <?php } else { ?>
                    <div class="p-6 text-center text-red-500">Product not found.</div>
                <?php } ?>
            </div>
        </main>
    </div>
</body>
</html>
