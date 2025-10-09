<?php
include 'includes/customer_header.php';
include 'db_connect.php';

if (!isset($_GET['appointment_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}
$appointment_id = $_GET['appointment_id'];

// Fetch the confirmed appointment details
$stmt = $conn->prepare("
    SELECT a.*, s.name as service_name, u.name as staff_name 
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.staff_id = u.id
    WHERE a.id = ? AND a.customer_id = ?
");
$stmt->bind_param("ii", $appointment_id, $customerId);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();

if (!$appointment) {
    die("Booking not found.");
}
?>

<div class="admin-content">
    <div class="success-container">
        <h2>✅ Booking Confirmed!</h2>
        <p>Your appointment is locked in. We look forward to seeing you!</p>
        <div class="booking-summary-box">
            <p><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?></p>
            <p><strong>Stylist:</strong> <?php echo htmlspecialchars($appointment['staff_name']); ?></p>
            <p><strong>Date & Time:</strong> <?php echo date('F d, Y', strtotime($appointment['appointment_date'])) . ' at ' . date('h:i A', strtotime($appointment['start_time'])); ?></p>
            <p><strong>Total:</strong> ₱<?php echo number_format($appointment['total_cost'], 2); ?></p>
            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($appointment['payment_status']); ?> (via <?php echo htmlspecialchars($appointment['payment_method']); ?>)</p>
        </div>
        <div class="success-actions">
            <a href="generate_receipt.php?id=<?php echo $appointment_id; ?>" target="_blank" class="btn">Download Receipt</a>
            <a href="customer_dashboard.php" class="btn-secondary">Go to My Appointments</a>
        </div>
    </div>
</div>

<?php $stmt->close(); $conn->close(); ?>
</main>
</body>
</html>