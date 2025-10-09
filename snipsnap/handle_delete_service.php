<?php
session_start();
include 'db_connect.php';

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Access Denied.");
}

if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        header("Location: admin_manage_services.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>