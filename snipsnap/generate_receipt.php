<?php
session_start();
include 'db_connect.php';

// Security checks
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Access Denied.");
}
$appointment_id = $_GET['id'];
$customer_id = $_SESSION['user_id'];

// Fetch appointment details, ensuring it belongs to the logged-in user
$stmt = $conn->prepare("
    SELECT a.*, s.name as service_name, c.name as customer_name, st.name as staff_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users c ON a.customer_id = c.id
    JOIN users st ON a.staff_id = st.id
    WHERE a.id = ? AND a.customer_id = ?
");
$stmt->bind_param("ii", $appointment_id, $customer_id);
$stmt->execute();
$appt = $stmt->get_result()->fetch_assoc();

if (!$appt) {
    die("Receipt not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?php echo $appt['id']; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; margin: 0; padding: 20px; }
        .receipt-container { max-width: 400px; margin: auto; border: 1px dashed #333; padding: 20px; }
        h1 { text-align: center; margin: 0 0 20px 0; font-size: 24px; }
        p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; }
        .total-row td { border-top: 1px dashed #333; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <h1>SnipSnap</h1>
        <p><strong>Receipt #:</strong> <?php echo $appt['id']; ?></p>
        <p><strong>Date Issued:</strong> <?php echo date('M d, Y H:i'); ?></p>
        <p><strong>Billed To:</strong> <?php echo htmlspecialchars($appt['customer_name']); ?></p>
        <p><strong>Serviced By:</strong> <?php echo htmlspecialchars($appt['staff_name']); ?></p>
        
        <table>
            <thead>
                <tr><th>Description</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($appt['service_name']); ?></td>
                    <td>₱<?php echo number_format($appt['total_cost'], 2); ?></td>
                </tr>
                <tr class="total-row">
                    <td>Total</td>
                    <td>₱<?php echo number_format($appt['total_cost'], 2); ?></td>
                </tr>
            </tbody>
        </table>

        <p class="footer">Thank you for your business!</p>
    </div>
</body>
</html>