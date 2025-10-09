<?php
include 'includes/staff_header.php';
include 'db_connect.php';

// Fetch the staff's current availability to pre-fill the form
$stmt = $conn->prepare("SELECT day_of_week, start_time, end_time, is_available FROM staff_availability WHERE staff_id = ?");
$stmt->bind_param("i", $staffId);
$stmt->execute();
$result = $stmt->get_result();

$availability = [];
while ($row = $result->fetch_assoc()) {
    $availability[$row['day_of_week']] = $row;
}

$days_of_week = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
?>

<div class="admin-content">
    <h2>Manage My Weekly Availability</h2>
    <p>Set your standard working hours for each day. This will determine the time slots customers can book.</p>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p class="message success">Availability updated successfully!</p>
    <?php endif; ?>

    <div class="form-container admin-form">
        <form action="handle_update_availability.php" method="POST">
            <table class="availability-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Available?</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days_of_week as $day_num => $day_name): ?>
                        <?php
                            $current_day = $availability[$day_num] ?? null;
                            $is_available = $current_day ? $current_day['is_available'] : 0;
                            $start_time = $current_day ? date('H:i', strtotime($current_day['start_time'])) : '09:00';
                            $end_time = $current_day ? date('H:i', strtotime($current_day['end_time'])) : '17:00';
                        ?>
                        <tr>
                            <td><strong><?php echo $day_name; ?></strong></td>
                            <td>
                                <input type="checkbox" name="is_available[<?php echo $day_num; ?>]" value="1" <?php echo $is_available ? 'checked' : ''; ?>>
                            </td>
                            <td>
                                <input type="time" name="start_time[<?php echo $day_num; ?>]" value="<?php echo $start_time; ?>">
                            </td>
                            <td>
                                <input type="time" name="end_time[<?php echo $day_num; ?>]" value="<?php echo $end_time; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn">Save Availability</button>
        </form>
    </div>
</div>

<?php $stmt->close(); $conn->close(); ?>
</main>
</body>
</html>