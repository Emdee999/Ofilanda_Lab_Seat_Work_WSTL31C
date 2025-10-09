<?php
// This starts the session on every staff page
session_start();

// Check if the user is logged in and is a staff member
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    header("Location: login.php");
    exit();
}

$staffName = $_SESSION['user_name'];
$staffId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipSnap Staff Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="admin-header staff-header">
        <h1>SnipSnap Staff Panel</h1>
        <nav>
            <a href="staff_dashboard.php">Dashboard</a>
            <a href="staff_schedule.php">My Schedule</a>
            <a href="staff_availability.php">My Availability</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
        <p class="welcome-msg">Welcome, <?php echo htmlspecialchars($staffName); ?>!</p>
    </header>
    <main class="admin-main">