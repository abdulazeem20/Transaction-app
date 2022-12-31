<?php

// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Load Composer's autoloader
require_once 'transactions/vendor/autoload.php';

class Email
{
    public function sendMail($rec_email, $subject, $body)
    {
        //PHPMailer Object
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'user@example.com';                     // SMTP username
        $mail->Password = $_ENV['passkey'];                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //From email address and name
        $mail->From = 'example@gmail.com';
        $mail->FromName = 'Transaction App';

        //To address and name
        //$mail->addAddress($rec_email, $rec_name);
        $mail->addAddress($rec_email); //Recipient name is optional

        //Address to which recipient will reply
        $mail->addReplyTo('example@gmail.com', 'Reply');

        //CC and BCC
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;
        //$mail->AltBody = 'This is the plain text version of the email content';

        try {
            $mail->send();
            echo 'Message has been sent successfully';
        } catch (Exception $e) {
            echo 'Mailer Error: '.$mail->ErrorInfo;
        }
    }
}
