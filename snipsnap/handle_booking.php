<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    die("Access Denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];
    $appointment_date = $_POST['appointment_date'];
    $start_time_str = $_POST['start_time']; // e.g., "10:30 AM"
    $start_time = date('H:i:s', strtotime($start_time_str));

    // Server-side validation: Get service details from DB to prevent tampering
    $stmt = $conn->prepare("SELECT price, duration_minutes FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $service = $stmt->get_result()->fetch_assoc();

    if (!$service) {
        die("Invalid service selected.");
    }
    
    $total_cost = $service['price'];
    $duration = $service['duration_minutes'];
    
    // Calculate end time
    $end_time = date('H:i:s', strtotime("$start_time + $duration minutes"));

    // Insert into appointments table
    $stmt_insert = $conn->prepare("
        INSERT INTO appointments (customer_id, staff_id, service_id, appointment_date, start_time, end_time, total_cost, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt_insert->bind_param("iiisssd", $customer_id, $staff_id, $service_id, $appointment_date, $start_time, $end_time, $total_cost);

    if ($stmt_insert->execute()) {
        // Get the ID of the appointment we just created
        $appointment_id = $conn->insert_id;
        
        // Redirect to the new payment page instead of the dashboard
        header("Location: payment.php?appointment_id=" . $appointment_id);
        exit();
    } else {
        echo "Error: " . $stmt_insert->error;
    }
    
    $stmt->close();
    $stmt_insert->close();
    $conn->close();
}
?>