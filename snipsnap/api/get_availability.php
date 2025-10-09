<?php
header('Content-Type: application/json');
include '../db_connect.php';

$staff_id = $_GET['staff_id'];
$date = $_GET['date'];
$duration = $_GET['duration'];
$day_of_week = date('N', strtotime($date)); // 1 (for Monday) through 7 (for Sunday)

// --- Step 1: Get Staff's DYNAMIC Working Hours ---
$stmt_avail = $conn->prepare("SELECT start_time, end_time FROM staff_availability WHERE staff_id = ? AND day_of_week = ? AND is_available = 1");
$stmt_avail->bind_param("ii", $staff_id, $day_of_week);
$stmt_avail->execute();
$availability = $stmt_avail->get_result()->fetch_assoc();

if (!$availability) {
    echo json_encode([]); // Staff is not available on this day
    exit();
}

$work_start_time = new DateTime($availability['start_time']);
$work_end_time = new DateTime($availability['end_time']);

// --- Step 2: Get Existing Appointments for that day ---
$stmt_appt = $conn->prepare("SELECT start_time, end_time FROM appointments WHERE staff_id = ? AND appointment_date = ? AND status != 'Cancelled'");
$stmt_appt->bind_param("is", $staff_id, $date);
$stmt_appt->execute();
$result = $stmt_appt->get_result();
$booked_slots = [];
while ($row = $result->fetch_assoc()) {
    $booked_slots[] = [
        'start' => new DateTime($row['start_time']),
        'end' => new DateTime($row['end_time'])
    ];
}

// --- Step 3: Generate Potential Slots and Check for Conflicts ---
$available_slots = [];
$current_slot = clone $work_start_time;
$appointment_interval = new DateInterval('PT' . $duration . 'M');

while ($current_slot < $work_end_time) {
    $potential_end_time = clone $current_slot;
    $potential_end_time->add($appointment_interval);

    if ($potential_end_time > $work_end_time) {
        break;
    }

    $is_available = true;
    foreach ($booked_slots as $booked) {
        if ($current_slot < $booked['end'] && $potential_end_time > $booked['start']) {
            $is_available = false;
            break;
        }
    }

    if ($is_available) {
        $available_slots[] = $current_slot->format('h:i A');
    }
    
    $current_slot->add(new DateInterval('PT15M'));
}

echo json_encode($available_slots);

$stmt_avail->close();
$stmt_appt->close();
$conn->close();
?>