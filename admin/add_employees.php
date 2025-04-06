<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
 

    $query = "INSERT INTO employees (name,role,salary,contact, email) VALUES ('$name','$role', '$salary','$contact', '$email')";
    if ($conn->query($query)) {
        header("Location: employee_details.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <input type="text" name="name" placeholder="Employee Name" required>
        <input type="text" name="role" placeholder="Employee Role" required>
        <input type="number" name="salary" placeholder="Salary" required>
        <input type="number" name="contact" placeholder="Mobile_number" required>
        <input type="email" name="email" placeholder="Mail id" required>
        <button type="submit">Add Employee</button>
    </form>
</body>
</html>
