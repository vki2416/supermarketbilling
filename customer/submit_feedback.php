<?php
session_start();
include '../config/db.php';

$rating = intval($_POST['rating']);
$feedback = $conn->real_escape_string($_POST['comments']);

$insert_feedback = "INSERT INTO feedback (rating, comments) VALUES ('$rating', '$feedback')";
if ($conn->query($insert_feedback)) {
    $_SESSION['success_msg'] = "Thank you for your feedback!";
} else {
    $_SESSION['error_msg'] = "Error submitting feedback. Please try again.";
}

header("Location: order_success.php");
exit();
?>