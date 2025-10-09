<?php
include 'includes/staff_header.php'; // Handles session and security
include 'db_connect.php';

// --- DATA FETCHING ---
$today = date('Y-m-d');
$staff_id = $_SESSION['user_id'];

// 1. Get Today's Appointments
$stmt_today = $conn->prepare("
    SELECT a.*, c.name as customer_name, s.name as service_name
    FROM appointments a
    JOIN users c ON a.customer_id = c.id
    JOIN services s ON a.service_id = s.id
    WHERE a.staff_id = ? AND a.appointment_date = ? AND a.status IN ('Confirmed', 'Pending')
    ORDER BY a.start_time
");
$stmt_today->bind_param("is", $staff_id, $today);
$stmt_today->execute();
$todays_appointments = $stmt_today->get_result();

// 2. Get Key Performance Indicators (KPIs)
$stmt_kpi = $conn->prepare("
    SELECT
        (SELECT COUNT(*) FROM appointments WHERE staff_id = ? AND status = 'Pending') as pending_count,
        (SELECT AVG(customer_rating) FROM appointments WHERE staff_id = ? AND customer_rating IS NOT NULL) as avg_rating,
        (SELECT SUM(total_cost) FROM appointments WHERE staff_id = ? AND status = 'Completed' AND WEEK(appointment_date) = WEEK(CURDATE())) as weekly_earnings
");
$stmt_kpi->bind_param("iii", $staff_id, $staff_id, $staff_id);
$stmt_kpi->execute();
$kpis = $stmt_kpi->get_result()->fetch_assoc();

// 3. Get Weekly Schedule Overview
$stmt_week = $conn->prepare("
    SELECT DAYNAME(appointment_date) as day_name, COUNT(*) as appointment_count
    FROM appointments
    WHERE staff_id = ? AND WEEK(appointment_date) = WEEK(CURDATE()) AND status = 'Confirmed'
    GROUP BY DAYNAME(appointment_date)
    ORDER BY appointment_date
");
$stmt_week->bind_param("i", $staff_id);
$stmt_week->execute();
$weekly_overview_data = $stmt_week->get_result();

$weekly_schedule = [
    'Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0,
    'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0
];
while ($row = $weekly_overview_data->fetch_assoc()) {
    $weekly_schedule[$row['day_name']] = $row['appointment_count'];
}

?>

<div class="admin-content">
    <h2>Staff Dashboard</h2>

    <!-- KPI Cards Section -->
    <div class="kpi-container">
        <div class="kpi-card">
            <h3>Pending Appointments</h3>
            <p class="kpi-value"><?php echo $kpis['pending_count'] ?? 0; ?></p>
            <a href="staff_schedule.php">Confirm Now</a>
        </div>
        <div class="kpi-card">
            <h3>My Customer Rating</h3>
            <p class="kpi-value"><?php echo $kpis['avg_rating'] ? number_format($kpis['avg_rating'], 1) . ' / 5.0' : 'N/A'; ?></p>
            <span>Based on completed services</span>
        </div>
        <div class="kpi-card">
            <h3>Projected Weekly Earnings</h3>
            <p class="kpi-value">₱<?php echo number_format($kpis['weekly_earnings'] ?? 0, 2); ?></p>
            <span>Based on completed services this week</span>
        </div>
    </div>

    <!-- Today's Appointments Section -->
    <div class="table-container">
        <h3>Today's Appointments (<?php echo date('F d, Y'); ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($todays_appointments->num_rows > 0): ?>
                    <?php while($appt = $todays_appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('h:i A', strtotime($appt['start_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appt['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['service_name']); ?></td>
                        <td><span class="status-<?php echo strtolower($appt['status']); ?>"><?php echo $appt['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No appointments scheduled for today.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Weekly Schedule Overview Section -->
    <div class="weekly-overview-container">
        <h3>This Week's Schedule</h3>
        <div class="week-days">
            <?php foreach ($weekly_schedule as $day => $count): ?>
            <div class="day-card">
                <h4><?php echo $day; ?></h4>
                <p class="appointment-count"><?php echo $count; ?></p>
                <span>Confirmed Appt(s)</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$stmt_today->close();
$stmt_kpi->close();
$stmt_week->close();
$conn->close();
?>
</main>
</body>
</html>