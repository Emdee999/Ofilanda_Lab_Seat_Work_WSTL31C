<?php include 'includes/public_header.php'; ?>

<main class="content-page">
    <div class="container">
        <h1 class="page-title">Get In Touch</h1>
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p class="message success">Thank you for your message! We'll get back to you shortly.</p>
        <?php endif; ?>
        
        <div class="contact-grid">
            <div class="contact-details">
                <h3>Our Location</h3>
                <p>123 Style Street<br>Caloocan City, Metro Manila</p>
                <h3>Hours</h3>
                <p>Monday - Saturday: 9:00 AM - 8:00 PM<br>Sunday: Closed</p>
                <h3>Contact</h3>
                <p>Email: contact@snipsnap.com<br>Phone: (02) 8123-4567</p>
            </div>
            <div class="contact-form">
                <form action="handle_contact_form.php" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/public_footer.php'; ?>