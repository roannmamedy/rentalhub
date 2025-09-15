<?php

// Show all PHP errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Ensure the path to the autoloader is correct
require_once __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // --- SERVER CONFIGURATION ---
    
    // Enable verbose debug output to see the full SMTP conversation
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = '731e07dbd9c4d8'; // Your Mailtrap username
    $mail->Password   = '048c0488dad38a'; // PASTE YOUR PASSWORD HERE
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 2525;

    // --- RECIPIENTS & CONTENT ---
    $mail->setFrom('from@example.com', 'Test Sender');
    $mail->addAddress('to@example.com', 'Test Recipient'); // Can be any fake address

    $mail->isHTML(true);
    $mail->Subject = 'Test Email with Debugging';
    $mail->Body    = 'This is a test message. If you see this in Mailtrap, the connection is working!';
    $mail->AltBody = 'This is a plain-text message for non-HTML mail clients.';

    $mail->send();
    echo '<h1>Success!</h1> <p>The email was accepted for delivery. Check your Mailtrap inbox.</p>';

} catch (Exception $e) {
    echo "<h1>Error!</h1> <p>The email could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
}