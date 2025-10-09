<?php
include 'includes/admin_header.php';
include 'db_connect.php';

// Fetch all staff members to display in the table
$staff_list = $conn->query("SELECT id, name, email, position, created_at FROM users WHERE role = 'staff' ORDER BY name");
?>

<div class="admin-content">
    <h2>Manage Staff</h2>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p class="message success">New staff member added successfully!</p>
    <?php endif; ?>

    <div class="form-container admin-form">
        <h3>Add New Staff Member</h3>
        <form action="handle_add_staff.php" method="POST">
            <div class="form-group-row">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="position">Position (e.g., Senior Stylist)</label>
                    <input type="text" id="position" name="position" required>
                </div>
                <div class="form-group">
                    <label for="password">Temporary Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <button type="submit" class="btn">Add Staff</button>
        </form>
    </div>

    <div class="table-container">
        <h3>Current Staff</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Date Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php while($staff = $staff_list->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($staff['name']); ?></td>
                    <td><?php echo htmlspecialchars($staff['email']); ?></td>
                    <td><?php echo htmlspecialchars($staff['position']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($staff['created_at'])); ?></td>
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