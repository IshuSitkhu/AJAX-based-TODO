<?php

include 'config/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'ishusitikhu6@gmail.com'; 
    $mail->Password = 'twohhgcmqoytrgzr';   

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Debugoutput = 'html';
    $mail->SMTPDebug = 2;

    // SENDER
    $mail->setFrom('ishusitikhu6@gmail.com', 'Task Manager');

    // RECEIVER (PUT YOUR OWN EMAIL FIRST FOR TEST)
    $mail->addAddress('ishusitikhu6@gmail.com');

    // CONTENT
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = '<h3>It works! </h3><p>Email setup is successful.</p>';

    $mail->send();

    echo " Email sent successfully";

} catch (Exception $e) {
    echo " Error: " . $mail->ErrorInfo;
}
?>