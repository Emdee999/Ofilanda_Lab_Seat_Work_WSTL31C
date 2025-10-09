<?php
session_start();
include 'db_connect.php';

// Security check: only admins can perform this action
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Access Denied.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'staff'; // Explicitly set the role

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $role, $position);

    if ($stmt->execute()) {
        header("Location: admin_manage_staff.php?status=success");
    } else {
        // Handle potential errors, like a duplicate email
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>