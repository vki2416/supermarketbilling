<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'customer'; // Default role

    $query = "INSERT INTO users (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$password', '$role')";
    
    if ($conn->query($query) === TRUE) {
        echo "Signup successful! <br> <a href='../index.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign_up_page</title>
</head>
<body>
<form method="post">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
    
</body>
</html>