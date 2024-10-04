<?php
session_start();
include 'db.php'; // Make sure to include your database connection

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Your existing code for handling the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generate a reset code and store it in your database
    $reset_code = bin2hex(random_bytes(16)); // Example of generating a reset code
    $sql = "UPDATE users SET reset_token = :reset_token WHERE email = :email";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute(['reset_token' => $reset_code, 'email' => $email])) {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Your email
            $mail->Password = 'your_password'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'Mailer');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'To reset your password, please click the following link: <a href="http://yourwebsite.com/reset_password.php?email=' . urlencode($email) . '&reset_code=' . $reset_code . '">Reset Password</a>';

            $mail->send();
            $_SESSION['status'] = "Reset code has been sent to your email.";

            // Redirect to reset_password.php after sending the email
            header('Location: reset_password.php?email=' . urlencode($email) . '&reset_code=' . urlencode($reset_code));
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "Could not update reset token. Please try again.";
    }

    header('Location: forgot_password.php'); // Redirect back to the form if there's an issue
    exit();
}
?>
