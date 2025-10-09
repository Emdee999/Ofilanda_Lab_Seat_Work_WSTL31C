<?php
// This page starts with our reusable header
include 'includes/admin_header.php';
// We also need the database connection
include 'db_connect.php';

// Fetch all services from the database to display in the table
$result = $conn->query("SELECT * FROM services ORDER BY name");
?>

<div class="admin-content">
    <h2>Manage Services</h2>

    <div class="form-container admin-form">
        <h3>Add New Service</h3>
        <form action="handle_add_service.php" method="POST">
            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="price">Price (₱)</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration_minutes" required>
                </div>
            </div>
            <button type="submit" class="btn">Add Service</button>
        </form>
    </div>

    <div class="table-container">
        <h3>Existing Services</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($service = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                    <td>₱<?php echo number_format($service['price'], 2); ?></td>
                    <td><?php echo $service['duration_minutes']; ?> mins</td>
                    <td class="actions">
                        <a href="handle_delete_service.php?id=<?php echo $service['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
?>
</main>
</body>
</html>