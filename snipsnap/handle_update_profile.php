<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    die("Access Denied.");
}

$customer_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    // --- HANDLE DETAILS UPDATE ---
    if ($_POST['action'] == 'update_details') {
        $name = $_POST['name'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $customer_id);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name; // Update session name immediately
            header("Location: customer_profile.php?status=success");
        } else {
            header("Location: customer_profile.php?error=Failed to update details.");
        }
        $stmt->close();
    }

    // --- HANDLE PASSWORD CHANGE ---
    if ($_POST['action'] == 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            header("Location: customer_profile.php?error=New passwords do not match.");
            exit();
        }

        // Get current hashed password from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $hashed_password_from_db = $result['password'];

        // Verify if the current password is correct
        if (password_verify($current_password, $hashed_password_from_db)) {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_update->bind_param("si", $new_hashed_password, $customer_id);
            if ($stmt_update->execute()) {
                header("Location: customer_profile.php?status=pwdsuccess");
            } else {
                header("Location: customer_profile.php?error=Failed to change password.");
            }
            $stmt_update->close();
        } else {
            header("Location: customer_profile.php?error=Incorrect current password.");
        }
        $stmt->close();
    }

    $conn->close();
} else {
    header("Location: customer_profile.php");
    exit();
}
?>