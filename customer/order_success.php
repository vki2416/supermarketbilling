<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_order_success.css">
</head>
<body>
    <div class="container">
        <h2>🎉 Order Placed Successfully! 🎉</h2>
        <p>Thank you for shopping with us.</p>
        <div class="nav-links">
            <a href="home.php" class="btn btn-primary">Return to Home</a>
            <a href="../index.php" class="btn btn-danger">Log out</a>
        </div>

        <h3>Give us your feedback</h3>
        <form action="submit_feedback.php" method="POST" class="feedback-form">
            <label>Rating:</label>
            <select name="rating" required>
                <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (Good)</option>
                <option value="3">⭐⭐⭐ (Average)</option>
                <option value="2">⭐⭐ (Poor)</option>
                <option value="1">⭐ (Very Bad)</option>
            </select>
            <label>Comments (Optional):</label>
            <textarea name="comments" rows="3" placeholder="Write your feedback..."></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>