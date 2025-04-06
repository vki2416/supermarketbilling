<?php
ob_start();
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? ''; // "customer" or "admin"

$stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ? AND role = ?");
if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $stored_hash = $user['password'];

    if ($role === "admin" && md5($password) === $stored_hash) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        echo json_encode(['success' => true, 'redirect' => 'admin/admin_dashboard.php']);
    } elseif ($role === "customer" && password_verify($password, $stored_hash)) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        echo json_encode(['success' => true, 'redirect' => 'customer/home.php']);
    } else {
        echo json_encode(['error' => 'Incorrect password']);
    }
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
ob_end_flush();
?>