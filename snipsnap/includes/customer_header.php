<?php
session_start();

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$customerName = $_SESSION['user_name'];
$customerId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipSnap Customer Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="admin-header customer-header">
        <h1>SnipSnap</h1>
        <nav>
            <a href="customer_dashboard.php">My Appointments</a>
            <a href="book_appointment.php">Book Now</a>
            <a href="customer_profile.php">My Profile</a> <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
        <p class="welcome-msg">Welcome, <?php echo htmlspecialchars($customerName); ?>!</p>
    </header>
    <main class="admin-main">