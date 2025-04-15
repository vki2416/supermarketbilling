<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermart - Coming Soon</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css for Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #22d3ee);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .coming-soon-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
            padding: 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .coming-soon-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.3), transparent 70%);
            opacity: 0.5;
            z-index: 0;
        }

        .coming-soon-card > * {
            position: relative;
            z-index: 1;
        }

        .coming-soon-icon {
            font-size: 5rem;
            color: #4f46e5;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        .coming-soon-card h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .coming-soon-card p {
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 2rem;
        }

        .back-home-btn {
            background: #4f46e5;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .back-home-btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.6);
        }

        @media (max-width: 640px) {
            .coming-soon-card {
                padding: 2rem;
            }
            .coming-soon-card h1 {
                font-size: 1.875rem;
            }
            .coming-soon-card p {
                font-size: 1rem;
            }
            .coming-soon-icon {
                font-size: 3.5rem;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
    </style>
</head>
<body>
    <div class="coming-soon-card animate__animated animate__fadeIn">
        <i class="fas fa-shopping-bag coming-soon-icon"></i>
        <h1>Coming Soon!</h1>
        <p>Our app is under development. Stay tuned for exciting updates on how to shop smarter with Supermart!</p>
        <a href="index.php" class="back-home-btn">Back to Home</a>
    </div>
</body>
</html>