<?php
include 'includes/customer_header.php';
include 'db_connect.php';

// Fetch this customer's appointments
$stmt = $conn->prepare("
    SELECT a.*, s.name as service_name, u.name as staff_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.staff_id = u.id
    WHERE a.customer_id = ?
    ORDER BY a.appointment_date DESC, a.start_time DESC
");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<div class="admin-content">
    <div class="dashboard-header">
        <h2>My Appointments</h2>
        <a href="book_appointment.php" class="btn">Book New Appointment</a>
    </div>
    <p>Here you can see your upcoming and past appointments.</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Stylist</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments->num_rows > 0): ?>
                    <?php while($appt = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($appt['appointment_date'])) . ' at ' . date('h:i A', strtotime($appt['start_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appt['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['service_name']); ?></td>
                        <td><span class="status-<?php echo strtolower($appt['status']); ?>"><?php echo $appt['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">You have no appointments yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $stmt->close(); $conn->close(); ?>
</main>
</body>
</html>