<?php
session_start();
include 'db_connect.php';

// Security: Ensure a staff member is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    die("Access Denied.");
}

// Check if required parameters are set
if (isset($_GET['id']) && isset($_GET['action'])) {
    $appointment_id = $_GET['id'];
    $action = $_GET['action'];
    $staff_id = $_SESSION['user_id'];
    
    $new_status = '';
    if ($action == 'confirm') {
        $new_status = 'Confirmed';
    } elseif ($action == 'cancel') {
        $new_status = 'Cancelled';
    } else {
        // Invalid action
        header("Location: staff_schedule.php");
        exit();
    }

    // Prepare statement to update the appointment status
    // IMPORTANT: We also check if the appointment belongs to the logged-in staff member for security
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND staff_id = ?");
    $stmt->bind_param("sii", $new_status, $appointment_id, $staff_id);

    if ($stmt->execute()) {
        // Success! Redirect back to the schedule page to see the change.
        header("Location: staff_schedule.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if parameters are missing
    header("Location: staff_schedule.php");
    exit();
}
?>