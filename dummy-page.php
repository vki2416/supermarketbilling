<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermart - Dummy Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style_index.css">
    <style>
        .dummy-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #F8F9FA, #EDEEF0);
            animation: fadeIn 1.5s ease-in-out;
            padding: 2rem;
        }

        .dummy-content {
            text-align: center;
            background: #FFFFFF;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
            animation: bounceIn 1s ease-out;
        }

        .dummy-content h1 {
            font-size: 3rem;
            color: #FF5733;
            margin-bottom: 1rem;
            animation: slideInDown 1s ease-out;
        }

        .dummy-content p {
            font-size: 1.2rem;
            color: #666666;
            animation: slideInUp 1s ease-out;
        }

        .dummy-icon {
            font-size: 5rem;
            color: #FF5733;
            margin-bottom: 1rem;
            animation: rotateIn 1s ease-out;
        }

        @keyframes rotateIn {
            0% { transform: rotate(-20deg); opacity: 0; }
            100% { transform: rotate(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .dummy-content {
                padding: 2rem;
            }
            .dummy-content h1 {
                font-size: 2rem;
            }
            .dummy-content p {
                font-size: 1rem;
            }
            .dummy-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="dummy-container">
        <div class="dummy-content">
            <i class="bi bi-bag-check dummy-icon"></i>
            <h1>Coming Soon!</h1>
            <p>This page is under development. Stay tuned for exciting updates on our services, app downloads, or search features!</p>
            <a href="index.php" class="btn btn-get-app mt-4">Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>