<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM employees WHERE employee_id = $id";
    $result = $conn->query($query);
    $employees = $result->fetch_assoc();
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
 

    $update_query = "UPDATE employees 
    SET name='$name', 
        role='$role', 
        salary='$salary', 
        contact='$contact', 
        email='$email'
       
    WHERE employee_id=$id";

    if ($conn->query($update_query)) {
        header("Location: employee_details.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/style.css"> 
</head>
<body>
    <h2>Edit Employee</h2>
    <a href="manage_products.php">Back to Employee_info</a> | <a href="../auth/logout.php">Logout</a>

    <form method="post">
    <input type="text" name="name" placeholder="Employee Name" value="<?php echo $employees['name']; ?>" required>
    <input type="text" name="role" placeholder="Employee Role" value="<?php echo $employees['role']; ?>" required>
    <input type="number" name="salary" placeholder="Salary" value="<?php echo $employees['salary']; ?>" required>
    <input type="number" name="contact" placeholder="Mobile Number" value="<?php echo $employees['contact']; ?>" required>
    <input type="email" name="email" placeholder="Mail ID" value="<?php echo $employees['email']; ?>" required>
    <button type="submit">Update Employee</button>
</form>

</body>
</html>
