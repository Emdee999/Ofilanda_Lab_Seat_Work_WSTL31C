<?php
include 'includes/customer_header.php';
include 'db_connect.php';

// Get the current user's data to pre-fill the form
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $customerId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="admin-content">
    <h2>My Profile</h2>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p class="message success">Profile updated successfully!</p>
    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'pwdsuccess'): ?>
        <p class="message success">Password changed successfully!</p>
    <?php elseif(isset($_GET['error'])): ?>
        <p class="message error"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>


    <div class="form-container admin-form">
        <h3>Update Your Details</h3>
        <form action="handle_update_profile.php" method="POST">
            <input type="hidden" name="action" value="update_details">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>

    <div class="form-container admin-form">
        <h3>Change Password</h3>
        <form action="handle_update_profile.php" method="POST">
            <input type="hidden" name="action" value="change_password">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>
</div>

<?php $stmt->close(); $conn->close(); ?>
</main>
</body>
</html>