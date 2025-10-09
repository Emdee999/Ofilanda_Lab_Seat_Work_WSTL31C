<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'staff') {
    die("Access Denied.");
}

$staff_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_times = $_POST['start_time'];
    $end_times = $_POST['end_time'];
    $is_available_data = $_POST['is_available'] ?? [];

    // Prepare the statement once
    $stmt = $conn->prepare("
        INSERT INTO staff_availability (staff_id, day_of_week, start_time, end_time, is_available)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        start_time = VALUES(start_time), 
        end_time = VALUES(end_time), 
        is_available = VALUES(is_available)
    ");

    // Loop through all 7 days of the week
    for ($day_num = 1; $day_num <= 7; $day_num++) {
        $start_time = $start_times[$day_num];
        $end_time = $end_times[$day_num];
        // If the checkbox for a day is not ticked, it won't be in the POST data.
        $is_available = isset($is_available_data[$day_num]) ? 1 : 0;
        
        $stmt->bind_param("iissi", $staff_id, $day_num, $start_time, $end_time, $is_available);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    header("Location: staff_availability.php?status=success");
    exit();
}
?>