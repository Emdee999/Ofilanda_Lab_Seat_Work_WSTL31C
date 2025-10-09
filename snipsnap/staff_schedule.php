<?php
include 'includes/staff_header.php'; // Handles session, security, and navigation
include 'db_connect.php';

$staff_id = $_SESSION['user_id'];

// Fetch all upcoming and pending appointments for this staff member
$stmt = $conn->prepare("
    SELECT 
        a.id as appointment_id,
        a.appointment_date,
        a.start_time,
        a.status,
        c.name as customer_name,
        s.name as service_name,
        s.duration_minutes
    FROM appointments a
    JOIN users c ON a.customer_id = c.id
    JOIN services s ON a.service_id = s.id
    WHERE a.staff_id = ? AND a.appointment_date >= CURDATE()
    ORDER BY a.appointment_date, a.start_time
");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$appointments_result = $stmt->get_result();

// Group appointments by date
$grouped_appointments = [];
while ($row = $appointments_result->fetch_assoc()) {
    $grouped_appointments[$row['appointment_date']][] = $row;
}

?>

<div class="admin-content">
    <h2>My Schedule</h2>
    <p>View all your upcoming appointments. Please confirm new requests promptly.</p>

    <?php if (empty($grouped_appointments)): ?>
        <p>You have no upcoming appointments.</p>
    <?php else: ?>
        <?php foreach ($grouped_appointments as $date => $appointments): ?>
            <div class="schedule-day-container">
                <h3><?php echo date('l, F j, Y', strtotime($date)); ?></h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td><?php echo date('h:i A', strtotime($appt['start_time'])); ?></td>
                                <td><?php echo htmlspecialchars($appt['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['service_name']); ?></td>
                                <td><span class="status-<?php echo strtolower($appt['status']); ?>"><?php echo $appt['status']; ?></span></td>
                                <td class="actions">
                                    <?php if ($appt['status'] == 'Pending'): ?>
                                        <a href="handle_appointment_action.php?id=<?php echo $appt['appointment_id']; ?>&action=confirm" class="btn-action btn-confirm">Confirm</a>
                                        <a href="handle_appointment_action.php?id=<?php echo $appt['appointment_id']; ?>&action=cancel" class="btn-action btn-cancel" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                                    <?php else: ?>
                                        <span>No actions available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
$stmt->close();
$conn->close();
?>
</main>
</body>
</html>