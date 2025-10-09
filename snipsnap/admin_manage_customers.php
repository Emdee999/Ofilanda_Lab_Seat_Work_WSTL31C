<?php
include 'includes/admin_header.php';
include 'db_connect.php';

// Build the dynamic query for searching customers
$sql = "SELECT u.id, u.name, u.email, u.created_at, COUNT(a.id) as appointment_count
        FROM users u
        LEFT JOIN appointments a ON u.id = a.customer_id
        WHERE u.role = 'customer'";

$params = [];
$types = '';

if (!empty($_GET['search'])) {
    $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
    $types .= 'ss';
    $search_term = '%' . $_GET['search'] . '%';
    $params[] = $search_term;
    $params[] = $search_term;
}

$sql .= " GROUP BY u.id ORDER BY u.name";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$customers = $stmt->get_result();
?>

<div class="admin-content">
    <h2>Manage Customers</h2>

    <div class="filter-container">
        <form action="admin_manage_customers.php" method="GET">
            <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" class="btn">Search</button>
            <a href="admin_manage_customers.php" class="btn-clear">Clear</a>
        </form>
    </div>

    <div class="table-container">
        <h3>Registered Customers</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date Joined</th>
                    <th>Total Appointments</th>
                </tr>
            </thead>
            <tbody>
                <?php while($customer = $customers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                    <td><?php echo $customer['appointment_count']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $conn->close(); ?>
</main>
</body>
</html>