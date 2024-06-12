<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'localhost'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = false; // Enable SMTP authentication
    $mail->SMTPSecure = 'tls'; // Enable SSL encryption, TLS also accepted with port 465
    $mail->Port = 25; // TCP port to connect to

    //Recipients
    $mail->setFrom('admin@mgas.ke', 'Mailer'); //This is the email your form sends From
    $mail->addAddress('muianelson@gmail.com', 'Nelson Muia'); // Add a recipient address
    //Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Subject line goes here';
    $mail->Body    = 'Body text goes here';
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>