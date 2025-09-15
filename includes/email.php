<?php
/**
 * Email Helper for Transactional Messages using PHPMailer.
 * Usage: send_mail($to, $subject, $htmlBody, $textBodyOptional)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

if (!function_exists('send_mail')) {
    /**
     * Sends a multipart/alternative email using SMTP. 
     *
     * @param string $to The recipient's email address.
     * @param string $subject The email subject.
     * @param string $html The HTML content of the email.
     * @param string|null $text Optional plain-text version of the email. If null, it will be auto-generated from the HTML.
     * @return bool True on success, false on failure.
     */
    function send_mail($to, $subject, $html, $text = null) {
        $to = trim($to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            error_log('Email sending failed: Invalid recipient address provided.');
            return false;
        }

        $mail = new PHPMailer(true); // Enable exceptions

        try {
            // --- SERVER CONFIGURATION ---
            // For testing, use your Mailtrap.io credentials.
            // When you go LIVE, replace these with your real SMTP provider's details.
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';   
            $mail->SMTPAuth   = true;
            $mail->Username   = '731e07dbd9c4d8';             
            $mail->Password   = '048c0488dad38a';              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 2525;                         

            // --- RECIPIENTS & CONTENT ---
            // Use a fixed, valid-looking "From" address for reliability.
            $fromEmail = 'no-reply@rentalhub.com';
            $fromName  = 'RentalHub';

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);
            $mail->addReplyTo($fromEmail, $fromName);

            // --- EMAIL CONTENT ---
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            
            // Create a plain-text version if one isn't provided
            $mail->AltBody = $text ?: strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $html));
            
            $mail->CharSet = 'UTF-8';

            $mail->send();
            return true;

        } catch (Exception $e) {
            // Log the detailed error for debugging without crashing the script.
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}