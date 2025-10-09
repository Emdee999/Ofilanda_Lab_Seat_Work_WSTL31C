<?php
// This starts the session on every admin page
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipSnap Admin</title>
    <link rel="stylesheet" href="css/style.css"> <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="admin-header">
        <h1>SnipSnap Admin Panel</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_manage_services.php">Manage Services</a>
            <a href="admin_manage_appointments.php">Appointments</a>
            <a href="admin_manage_staff.php">Manage Staff</a>       <a href="admin_manage_customers.php">Customers</a> <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
        <p class="welcome-msg">Welcome, <?php echo htmlspecialchars($adminName); ?>!</p>
    </header>
    <main class="admin-main">