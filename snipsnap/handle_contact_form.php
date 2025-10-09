<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Make sure you have these files from the PHPMailer library
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // Server settings - UPDATE WITH YOUR HOSTINGER EMAIL CREDENTIALS
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@yourdomain.com'; // Your email address
        $mail->Password   = 'your_email_password';     // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your_email@yourdomain.com', 'SnipSnap Contact Form');
        $mail->addAddress('your_receiving_email@yourdomain.com'); // Where you want to receive messages

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission from $name";
        $mail->Body    = "You have received a new message from your website contact form.<br><br>".
                         "<b>Name:</b> $name<br>".
                         "<b>Email:</b> $email<br>".
                         "<b>Message:</b><br>" . nl2br($message);

        $mail->send();
        header("Location: contact.php?status=success");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>