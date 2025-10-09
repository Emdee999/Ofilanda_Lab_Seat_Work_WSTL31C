<?php
// db_connect.php - The central database connection file

$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "MarcDave3003";     // Default XAMPP password is empty
$dbname = "snipsnap_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>