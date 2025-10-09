<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    die("Access Denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $payment_method = $_POST['payment_method'];
    $customer_id = $_SESSION['user_id'];
    
    $payment_status = ($payment_method == 'Online') ? 'Paid' : 'Unpaid';
    $appointment_status = 'Confirmed'; // Confirm the appointment upon payment selection

    // Update the appointment with the payment details and confirm it
    $stmt = $conn->prepare("
        UPDATE appointments 
        SET status = ?, payment_method = ?, payment_status = ? 
        WHERE id = ? AND customer_id = ?
    ");
    $stmt->bind_param("sssii", $appointment_status, $payment_method, $payment_status, $appointment_id, $customer_id);

    if ($stmt->execute()) {
        header("Location: booking_success.php?appointment_id=" . $appointment_id);
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>