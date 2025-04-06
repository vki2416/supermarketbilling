<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch distinct categories
$category_query = "SELECT DISTINCT TRIM(LOWER(category)) AS category FROM products";
$category_result = $conn->query($category_query);

// Handle search filter
$category_filter = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $category_filter = $conn->real_escape_string($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermart - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        
        .category-scroll {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .category-scroll::-webkit-scrollbar {
            display: none;
        }
        
        .promo-card {
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .promo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0.2));
            z-index: 1;
        }
        
        .promo-card > * {
            position: relative;
            z-index: 2;
        }
        
        .product-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .cart-popup {
            position blister: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #10b981;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .cart-popup.show {
            bottom: 30px;
            opacity: 1;
        }
        
        .category-item {
            flex: 0 0 auto;
            margin-right: 12px;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .category-item:hover {
            transform: scale(1.05);
        }
        
        .category-item img {
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            transition: border-color 0.3s;
        }
        
        .category-item:hover img {
            border-color: #10b981;
        }
        
        .search-bar {
            position: relative;
            width: 100%;
            max-width: 500px;
        }
        
        .search-bar input {
            padding-right: 50px;
        }
        
        .search-bar button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
        }
        
        .animate-bounce {
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <header class="py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center">
                <h1 class="text-3xl font-bold text-green-600 flex items-center">
                    <i class="fas fa-shopping-basket mr-2"></i>
                    SuperMart
                </h1>
            </div>
            <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <div class="search-bar relative">
                    <input 
                        type="text" 
                        placeholder="Search by category..." 
                        class="w-full py-2 px-4 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        id="searchInput"
                        value="<?php echo htmlspecialchars($category_filter); ?>"
                    >
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="flex gap-2 justify-center md:justify-start">
                    <a href="cart.php" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        <span>Cart</span>
                        <span class="ml-2 bg-white text-green-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold" id="cartCount">0</span>
                    </a>
                    <a href="../auth/logout.php" class="flex items-center px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p id="errorText"></p>
        </div>

        <!-- Promotional Banners -->
        <section class="mb-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="promo-card" style="background-image: url('https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80')">
                    <h3 class="text-xl font-bold mb-1">10% cashback on personal care</h3>
                    <p class="text-sm mb-3">Max cashback: $12<br>Code: CARE12</p>
                    <a href="#" class="self-start bg-black text-white px-4 py-1 rounded-full text-sm hover:bg-gray-800 transition">Shop Now</a>
                </div>
                <div class="promo-card" style="background-image: url('https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80')">
                    <h3 class="text-xl font-bold mb-1">Say yes to season's fresh</h3>
                    <p class="text-sm mb-3">Refresh your day the fruity way</p>
                    <a href="#" class="self-start bg-black text-white px-4 py-1 rounded-full text-sm hover:bg-gray-800 transition">Shop Now</a>
                </div>
                <div class="promo-card" style="background-image: url('https://images.unsplash.com/photo-1560008581-09826d1de69e?ixlib=rb-4.0.3&auto=format&fit=crop&w=744&q=80')">
                    <h3 class="text-xl font-bold mb-1">When in doubt, eat ice cream</h3>
                    <p class="text-sm mb-3">Enjoy a scoop of summer today</p>
                    <a href="#" class="self-start bg-black text-white px-4 py-1 rounded-full text-sm hover:bg-gray-800 transition">Shop Now</a>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="mb-10">
            <h3 class="text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-star text-yellow-400 mr-2 animate-bounce"></i>
                Featured Categories
            </h3>
            <div class="category-scroll pb-4">
                <?php
                while ($cat_row = $category_result->fetch_assoc()) {
                    $category = ucfirst($cat_row['category']);
                    $category_lower = $cat_row['category'];
                    $category_id = str_replace(' ', '-', strtolower($category));
                    $image_query = "SELECT image_url FROM products WHERE TRIM(LOWER(category)) = '$category_lower' LIMIT 1";
                    $image_result = $conn->query($image_query);
                    $image = $image_result->fetch_assoc()['image_url'] ?? 'https://via.placeholder.com/150x150?text=' . urlencode($category);
                ?>
                    <div class="category-item">
                        <a href="#<?php echo $category_id; ?>" class="category-link">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($category); ?>" class="w-20 h-20">
                            <p class="text-sm font-medium mt-2"><?php echo htmlspecialchars($category); ?></p>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Product List by Category -->
        <section id="productList">
            <?php
            $category_result->data_seek(0); // Reset pointer
            while ($cat_row = $category_result->fetch_assoc()) {
                $category = $cat_row['category'];
                $category_display = ucfirst($category);
                $category_id = str_replace(' ', '-', strtolower($category_display));
                $query = $category_filter 
                    ? "SELECT * FROM products WHERE TRIM(LOWER(category)) = '$category' AND category LIKE '%$category_filter%'"
                    : "SELECT * FROM products WHERE TRIM(LOWER(category)) = '$category'";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
            ?>
                <div class="category-section mb-12" id="<?php echo $category_id; ?>">
                    <h3 class="category-title text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-tag text-green-500 mr-2"></i>
                        <?php echo htmlspecialchars($category_display); ?>
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <div class="product-card bg-white rounded-lg overflow-hidden">
                                <div class="relative pt-[100%]">
                                    <img 
                                        src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                        alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                        class="absolute top-0 left-0 w-full h-full object-cover"
                                    >
                                </div>
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-800 mb-1 truncate"><?php echo htmlspecialchars($row['name']); ?></h4>
                                    <p class="text-green-600 font-bold mb-2">₹<?php echo htmlspecialchars($row['price']); ?></p>
                                    <p class="text-sm text-gray-500 mb-3">Stock: <?php echo htmlspecialchars($row['stock_quantity']); ?></p>
                                    <?php if ($row['stock_quantity'] > 0) { ?>
                                        <button 
                                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full transition flex items-center justify-center add-to-cart-btn"
                                            data-product-id="<?php echo $row['product_id']; ?>"
                                        >
                                            <i class="fas fa-cart-plus mr-2"></i>
                                            Add to Cart
                                        </button>
                                    <?php } else { ?>
                                        <p class="text-red-500 text-sm font-medium text-center">Out of Stock</p>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </section>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500 mb-4"></div>
                <p class="text-gray-700">Loading products...</p>
            </div>
        </div>

        <!-- Popup Notification -->
        <div id="cartPopup" class="cart-popup">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>Added to Cart!</p>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const cartCount = document.getElementById('cartCount');
        const cartPopup = document.getElementById('cartPopup');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Error message display
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
            setTimeout(() => errorMessage.classList.add('hidden'), 5000);
        }

        // Loading spinner controls
        function showLoading() { loadingSpinner.classList.remove('hidden'); }
        function hideLoading() { loadingSpinner.classList.add('hidden'); }

        // Add to cart functionality with AJAX
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', 1);

                showLoading();

                fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        // Update cart count
                        const currentCount = parseInt(cartCount.textContent);
                        cartCount.textContent = currentCount + 1;

                        // Show popup
                        cartPopup.classList.add('show');
                        setTimeout(() => cartPopup.classList.remove('show'), 2000);

                        // Update stock on the page
                        const stockElement = this.closest('.product-card').querySelector('.text-sm.text-gray-500');
                        const newStock = data.new_stock;
                        stockElement.textContent = `Stock: ${newStock}`;

                        // If stock reaches zero, replace button with out-of-stock message
                        if (newStock <= 0) {
                            this.remove();
                            const outOfStock = document.createElement('p');
                            outOfStock.className = 'text-red-500 text-sm font-medium text-center';
                            outOfStock.textContent = 'Out of Stock';
                            this.parentElement.appendChild(outOfStock);
                        }
                    } else {
                        showError('Error adding to cart: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Something went wrong: ' + error.message);
                });
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', () => {
            const filter = searchInput.value.trim();
            window.location.href = `?search=${encodeURIComponent(filter)}`;
        });

        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                const filter = searchInput.value.trim();
                window.location.href = `?search=${encodeURIComponent(filter)}`;
            }
        });

        // Smooth scrolling for category links
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>