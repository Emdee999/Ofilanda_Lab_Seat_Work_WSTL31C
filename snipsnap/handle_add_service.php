<?php
session_start();
include 'db_connect.php';

// Security check: only admins can add services
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Access Denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration_minutes = $_POST['duration_minutes'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO services (name, description, price, duration_minutes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $description, $price, $duration_minutes);

    if ($stmt->execute()) {
        // Success! Redirect back to the manage services page
        header("Location: admin_manage_services.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>