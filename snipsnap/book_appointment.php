<?php
include 'includes/customer_header.php';
include 'db_connect.php';

// Fetch all available services
$services = $conn->query("SELECT * FROM services WHERE is_active = 1 ORDER BY name");
?>

<div class="admin-content">
    <h2>Book a New Appointment</h2>
    <form action="handle_booking.php" method="POST" id="booking-form" class="booking-form">

        <div id="step1" class="booking-step">
            <h3>Step 1: Choose Your Service</h3>
            <div class="service-selection">
                <?php while($service = $services->fetch_assoc()): ?>
                <label class="service-card">
                    <input type="radio" name="service_id" value="<?php echo $service['id']; ?>"
                           data-duration="<?php echo $service['duration_minutes']; ?>" required>
                    <div class="service-info">
                        <strong><?php echo htmlspecialchars($service['name']); ?></strong>
                        <span><?php echo $service['duration_minutes']; ?> mins</span>
                        <em>₱<?php echo number_format($service['price'], 2); ?></em>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                </label>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="step2" class="booking-step" style="display:none;">
            <h3>Step 2: Select a Stylist & Date</h3>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="staff">Stylist</label>
                    <select name="staff_id" id="staff" class="form-control" required>
                        <option value="">Select a service first</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="appointment_date" id="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
        </div>

        <div id="step3" class="booking-step" style="display:none;">
            <h3>Step 3: Choose an Available Time</h3>
            <div id="time-slots-container">
                <p>Please select a stylist and date to see available times.</p>
            </div>
        </div>

        <input type="hidden" name="start_time" id="start_time" required>

        <button type="submit" class="btn" id="submit-btn" style="display:none;">Confirm Booking</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    const staffSelect = document.getElementById('staff');
    const dateInput = document.getElementById('date');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const startTimeInput = document.getElementById('start_time');
    const submitBtn = document.getElementById('submit-btn');

    let selectedDuration = 0;

    // --- Step 1 Logic ---
    step1.addEventListener('change', function(e) {
        if (e.target.name === 'service_id') {
            selectedDuration = e.target.dataset.duration;
            step2.style.display = 'block';
            fetchStaff();
        }
    });

    async function fetchStaff() {
        const response = await fetch('api/get_staff.php');
        const staff = await response.json();
        staffSelect.innerHTML = '<option value="">Any Available</option>'; // Or choose a default
        staff.forEach(member => {
            staffSelect.innerHTML += `<option value="${member.id}">${member.name}</option>`;
        });
    }

    // --- Step 2 Logic ---
    staffSelect.addEventListener('change', fetchAvailability);
    dateInput.addEventListener('change', fetchAvailability);

    async function fetchAvailability() {
        const staffId = staffSelect.value;
        const date = dateInput.value;

        if (staffId && date && selectedDuration > 0) {
            step3.style.display = 'block';
            timeSlotsContainer.innerHTML = '<p>Loading available times...</p>';

            const response = await fetch(`api/get_availability.php?staff_id=${staffId}&date=${date}&duration=${selectedDuration}`);
            const slots = await response.json();

            timeSlotsContainer.innerHTML = '';
            if (slots.length > 0) {
                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'time-slot-btn';
                    btn.textContent = slot;
                    btn.dataset.time = slot;
                    timeSlotsContainer.appendChild(btn);
                });
            } else {
                timeSlotsContainer.innerHTML = '<p>Sorry, no available slots for this day. Please try another date or stylist.</p>';
            }
        }
    }

    // --- Step 3 Logic ---
    timeSlotsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('time-slot-btn')) {
            // Remove active class from any previously selected button
            document.querySelectorAll('.time-slot-btn.active').forEach(btn => btn.classList.remove('active'));
            
            // Add active class to the clicked button
            e.target.classList.add('active');

            startTimeInput.value = e.target.dataset.time;
            submitBtn.style.display = 'block';
        }
    });
});
</script>

<?php $conn->close(); ?>
</main>
</body>
</html>