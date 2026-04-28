<?php
include 'config/db.php';
include 'config/mail_config.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// =========================
// CREATE MAILER ONCE (IMPORTANT SPEED FIX)
// =========================
$mail = new PHPMailer(true);

try {

    // SMTP SETUP ONCE
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(MAIL_USER, 'Task Manager');
    $mail->isHTML(true);

    // =========================
    // LIMIT BATCH SIZE (VERY IMPORTANT)
    // =========================
    $result = $conn->query("
        SELECT * 
        FROM email_queue 
        WHERE status='pending'
        ORDER BY id ASC
        LIMIT 5
    ");

    while ($row = $result->fetch_assoc()) {

        try {

            // RESET ADDRESSES (IMPORTANT)
            $mail->clearAddresses();
            $mail->addAddress($row['email'], $row['name']);

            $mail->Subject = $row['subject'];
            $mail->Body = $row['body'];

            $mail->send();

            // MARK AS SENT
            $stmt = $conn->prepare("
                UPDATE email_queue 
                SET status='sent' 
                WHERE id=?
            ");
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();

        } catch (Exception $e) {
            error_log("Email Error ID {$row['id']}: " . $mail->ErrorInfo);
        }
    }

} catch (Exception $e) {
    error_log("SMTP Init Error: " . $mail->ErrorInfo);
}
?>