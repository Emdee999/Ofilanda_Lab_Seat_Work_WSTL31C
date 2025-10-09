<?php
include 'includes/admin_header.php';
include 'db_connect.php';

// --- Get Data for Filters ---
$staff_members = $conn->query("SELECT id, name FROM users WHERE role = 'staff' ORDER BY name");

// --- Build the Dynamic SQL Query based on Filters ---
$sql = "SELECT a.id, a.appointment_date, a.start_time, a.status, a.total_cost, 
               c.name as customer_name, s.name as staff_name, serv.name as service_name
        FROM appointments a
        JOIN users c ON a.customer_id = c.id
        JOIN users s ON a.staff_id = s.id
        JOIN services serv ON a.service_id = s.id
        WHERE 1=1"; // Start with a true condition to easily append AND clauses

$params = [];
$types = '';

// Check for search by customer name
if (!empty($_GET['search_customer'])) {
    $sql .= " AND c.name LIKE ?";
    $types .= 's';
    $params[] = '%' . $_GET['search_customer'] . '%';
}
// Check for filter by staff
if (!empty($_GET['filter_staff'])) {
    $sql .= " AND a.staff_id = ?";
    $types .= 'i';
    $params[] = $_GET['filter_staff'];
}
// Check for filter by status
if (!empty($_GET['filter_status'])) {
    $sql .= " AND a.status = ?";
    $types .= 's';
    $params[] = $_GET['filter_status'];
}

$sql .= " ORDER BY a.appointment_date DESC, a.start_time DESC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$appointments = $stmt->get_result();
?>

<div class="admin-content">
    <h2>Manage All Appointments</h2>

    <div class="filter-container">
        <form action="admin_manage_appointments.php" method="GET">
            <input type="text" name="search_customer" placeholder="Search by customer name..." value="<?php echo htmlspecialchars($_GET['search_customer'] ?? ''); ?>">
            
            <select name="filter_staff">
                <option value="">All Staff</option>
                <?php while($staff = $staff_members->fetch_assoc()): ?>
                    <option value="<?php echo $staff['id']; ?>" <?php echo (($_GET['filter_staff'] ?? '') == $staff['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($staff['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="filter_status">
                <option value="">All Statuses</option>
                <option value="Pending" <?php echo (($_GET['filter_status'] ?? '') == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Confirmed" <?php echo (($_GET['filter_status'] ?? '') == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                <option value="Completed" <?php echo (($_GET['filter_status'] ?? '') == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo (($_GET['filter_status'] ?? '') == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            
            <button type="submit" class="btn">Filter</button>
            <a href="admin_manage_appointments.php" class="btn-clear">Clear</a>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Service</th>
                    <th>Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments->num_rows > 0): ?>
                    <?php while($appt = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($appt['appointment_date'])) . ' @ ' . date('h:i A', strtotime($appt['start_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appt['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['staff_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['service_name']); ?></td>
                        <td>₱<?php echo number_format($appt['total_cost'], 2); ?></td>
                        <td><span class="status-<?php echo strtolower($appt['status']); ?>"><?php echo $appt['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No appointments found matching your criteria.</td>
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