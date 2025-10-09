<?php
header('Content-Type: application/json');
include '../db_connect.php';

$result = $conn->query("SELECT id, name FROM users WHERE role = 'staff'");
$staff = [];
while ($row = $result->fetch_assoc()) {
    $staff[] = $row;
}

echo json_encode($staff);

$conn->close();
?>