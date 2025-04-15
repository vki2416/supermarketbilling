<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermart - Your Grocery Solution</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Import Poppins Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        /* Custom Gradient for Hero */
        .hero-bg {
            background: linear-gradient(120deg, #1e3a8a 0%, #22d3ee 100%);
        }

        /* Sticky Nav with Shadow */
        .nav-sticky {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="bg-white sticky top-0 z-50 nav-sticky">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <i class="fas fa-store text-indigo-600 text-2xl mr-2"></i>
                    <span class="font-semibold text-xl text-gray-800">Supermart</span>
                </div>

                <!-- Menu Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#about" class="text-gray-600 hover:text-indigo-600 font-medium">About</a>
                    <a href="#faq" class="text-gray-600 hover:text-indigo-600 font-medium">FAQ</a>
                    <div class="relative">
                        <button id="dropdown-btn" class="text-gray-600 hover:text-indigo-600 font-medium flex items-center">
                            Services <i class="fas fa-caret-down ml-1"></i>
                        </button>
                        <div id="dropdown-menu" class="hidden absolute mt-2 w-44 bg-white rounded-lg shadow-lg">
                            <a href="#branches" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Branches</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Track Order</a>
                        </div>
                    </div>
                    <button id="signin-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Sign In
                    </button>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-nav" class="hidden md:hidden bg-white shadow-lg">
            <div class="px-4 py-2 space-y-2">
                <a href="#about" class="block text-gray-600 hover:text-indigo-600 py-2">About</a>
                <a href="#faq" class="block text-gray-600 hover:text-indigo-600 py-2">FAQ</a>
                <a href="#branches" class="block text-gray-600 hover:text-indigo-600 py-2">Branches</a>
                <button id="mobile-signin-btn" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                    Sign In
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 text-center md:text-left mb-8 md:mb-0 animate__animated animate__fadeIn">
                <h1 class="text-3xl md:text-5xl font-semibold mb-4">Groceries Made Simple</h1>
                <p class="text-lg mb-6">Enjoy fast delivery and a wide selection of fresh products at Supermart.</p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <button id="start-shopping-btn" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition">
                        Start Shopping
                    </button>
                    <a href="./dummy-page.php" class="border-2 border-white px-6 py-3 rounded-lg hover:bg-white hover:text-indigo-600 transition">
                        Get the App
                    </a>
                </div>
            </div>
            <div class="md:w-1/2">
                <img src="https://images.unsplash.com/photo-1606787366850-de6330128bfc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Groceries" class="rounded-lg shadow-lg w-full animate__animated animate__zoomIn">
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="bg-white py-8 -mt-6 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col md:flex-row gap-4">
                <input type="text" placeholder="Search products..." class="w-full md:w-1/2 p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <input type="text" placeholder="Your location" class="w-full md:w-1/4 p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <button class="w-full md:w-auto bg-indigo-600 text-white p-3 rounded-lg hover:bg-indigo-700 transition">Find</button>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6 animate__animated animate__slideInUp">Who We Are</h2>
            <p>Welcome to Supermarket  your trusted partner in modern retail billing and management.

We are here to transform how supermarts operate by providing a fast, secure, and feature-rich billing system that simplifies every aspect of store management. From real-time billing and inventory tracking to profit analysis, employee management, and customer loyalty tools – Supermarket is designed to handle it all.

Our mission is simple: to make supermarket operations smarter, smoother, and more efficient. Whether you run a single store or manage a chain, our platform scales with your needs and grows with your business.

Built with powerful tech and a clean interface, Supermarket helps you spend less time managing and more time serving.</p>
        </div>
    </section>

<!-- FAQ Section -->
<section id="faq" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-semibold text-gray-800 text-center mb-8 animate__animated animate__slideInDown">Frequently Asked Questions</h2>
        <div class="space-y-4">
            <!-- FAQ 1: How do I place an order? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq1">
                    How do I place an order?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq1" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    Simply browse our products, add them to your cart, and proceed to checkout. You’ll need to log in or create an account to complete your order.
                </div>
            </div>
            <!-- FAQ 2: What are your delivery options? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq2">
                    What are your delivery options?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq2" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    We offer same-day delivery in select areas, as well as scheduled delivery slots. Check your options at checkout.
                </div>
            </div>
            <!-- FAQ 3: What products are available? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq3">
                    What products are available?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq3" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    Our supermart typically offers groceries, fresh fruits and vegetables, dairy products, packaged foods, personal care items, household essentials, and more.
                </div>
            </div>
            <!-- FAQ 4: Can I shop online? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq4">
                    Can I shop online?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq4" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    Yes, Supermart now provides online shopping options, allowing you to order groceries and other items for home delivery or pick-up.
                </div>
            </div>
            <!-- FAQ 5: What is the return policy? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq5">
                    What is the return policy?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq5" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    Supermart allows returns for unsatisfactory products, either at the time of delivery or within a specified period.
                </div>
            </div>
            <!-- FAQ 6: Are there loyalty programs? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq6">
                    Are there loyalty programs?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq6" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    Yes, some supermarkets offer loyalty programs where you can earn points on purchases and redeem them for discounts or rewards.
                </div>
            </div>
            <!-- FAQ 7: What payment methods are accepted? -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq7">
                    What payment methods are accepted?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq7" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    We accept cash, credit/debit cards, and digital wallets. We also offer payment options through our apps.
                </div>
            </div>
            <!-- FAQ 8: What are your delivery hours? (Existing) -->
            <div class="faq-item bg-white rounded-lg shadow-sm">
                <button class="faq-button w-full text-left p-4 text-xl font-medium text-gray-800 flex justify-between items-center hover:bg-gray-100 transition" data-target="faq8">
                    What are your delivery hours?
                    <i class="fas fa-chevron-down text-gray-500 transform transition-transform"></i>
                </button>
                <div id="faq8" class="faq-content hidden p-4 text-gray-600 border-t border-gray-200">
                    We deliver from 8 AM to 10 PM, seven days a week.
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Branches Section -->
    <section id="branches" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-semibold text-gray-800 text-center mb-8">Our Locations</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-medium text-gray-800 mb-2">City Center</h3>
                    <p class="text-gray-600">456 Elm St, City, ST 12345</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <p class="text-gray-300">Email: <a href="mailto:info@supermart.com" class="hover:text-indigo-400">info@supermart.com</a></p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Location</h4>
                <p class="text-gray-300">789 Market Rd, Townsville, TS 67890</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Social</h4>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-300 hover:text-indigo-400"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-300 hover:text-indigo-400"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center mt-8 text-gray-400">© 2025 Supermart. All rights reserved.</div>
    </footer>

    <!-- Login Modal -->
<div id="login-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-semibold text-gray-800">Sign In</h3>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex gap-4 mb-4">
            <button class="role-select px-4 py-2 text-indigo-600 border-b-2 border-indigo-600" data-role="customer">Operator</button>
            <button class="role-select px-4 py-2 text-gray-500" data-role="admin">Admin</button>
        </div>
        <form id="login-form" action="auth/login.php" method="POST" class="space-y-4">
            <input type="hidden" name="role" id="role-input" value="customer">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <button type="submit" id="login-submit" class="w-full bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition">Sign In as Operator</button>
            <a href="check.html" class="block w-full bg-gray-200 text-gray-800 p-2 rounded-lg hover:bg-gray-300 text-center transition">View Credentials</a>
        </form>
    </div>
</div>

    <!-- JavaScript -->
    <script>
        // Mobile Menu
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-nav').classList.toggle('hidden');
        });

        // Dropdown Menu
        const dropdownBtn = document.getElementById('dropdown-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');
        dropdownBtn.addEventListener('click', () => dropdownMenu.classList.toggle('hidden'));
        document.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Login Modal
        const signinBtns = [
            document.getElementById('signin-btn'),
            document.getElementById('mobile-signin-btn'),
            document.getElementById('start-shopping-btn')
        ];
        const modal = document.getElementById('login-modal');
        const closeModal = document.getElementById('close-modal');
        signinBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
        });
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Role Selection
        document.querySelectorAll('.role-select').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.role-select').forEach(b => {
                    b.classList.remove('text-indigo-600', 'border-indigo-600');
                    b.classList.add('text-gray-500');
                });
                btn.classList.add('text-indigo-600', 'border-indigo-600');
                btn.classList.remove('text-gray-500');
                const role = btn.dataset.role;
                document.getElementById('role-input').value = role;
                document.getElementById('login-submit').textContent = `Sign In as ${role === 'customer' ? 'Operator' : 'Admin'}`;
            });
        });

        // Form Submission
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('auth/login.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = data.redirect || 'index.php';
                } else {
                    alert(data.error || 'Login failed');
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const id = anchor.getAttribute('href');
                if (id !== '#') {
                    document.querySelector(id)?.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        // FAQ Accordion
document.querySelectorAll('.faq-button').forEach(button => {
    button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const content = document.getElementById(targetId);
        const icon = button.querySelector('i');

        // Toggle visibility
        const isOpen = !content.classList.contains('hidden');
        document.querySelectorAll('.faq-content').forEach(c => {
            c.classList.add('hidden');
        });
        document.querySelectorAll('.faq-button i').forEach(i => {
            i.classList.remove('rotate-180');
        });

        if (!isOpen) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        }
    });
});
    </script>
</body>
</html>