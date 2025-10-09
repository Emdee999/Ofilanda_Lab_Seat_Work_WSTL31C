<?php
include 'includes/customer_header.php';
include 'db_connect.php';

// Ensure an appointment ID is provided
if (!isset($_GET['appointment_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}
$appointment_id = $_GET['appointment_id'];

// Fetch the appointment details to show a summary
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
    die("Appointment not found or you do not have permission to view it.");
}
?>

<div class="admin-content">
    <h2>Confirm Your Booking</h2>
    <div class="payment-container">
        <div class="order-summary">
            <h3>Booking Summary</h3>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?></p>
            <p><strong>Stylist:</strong> <?php echo htmlspecialchars($appointment['staff_name']); ?></p>
            <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($appointment['appointment_date'])); ?></p>
            <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($appointment['start_time'])); ?></p>
            <hr>
            <p class="total-cost"><strong>Total Cost:</strong> ₱<?php echo number_format($appointment['total_cost'], 2); ?></p>
        </div>
        <div class="payment-options">
            <h3>Choose Payment Method</h3>
            <form action="handle_payment.php" method="POST">
                <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                <button type="submit" name="payment_method" value="In-Shop" class="btn btn-payment">
                    Pay In-Shop
                    <span>Pay with cash or card at the salon.</span>
                </button>
                <button type="submit" name="payment_method" value="Online" class="btn btn-payment">
                    Pay Online (Simulated)
                    <span>Simulate payment with GCash or PayPal.</span>
                </button>
            </form>
        </div>
    </div>
</div>

<?php $stmt->close(); $conn->close(); ?>
</main>
</body>
</html>