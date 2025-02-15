<?php
    include 'php/lib/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $message = "Use this one time password:\n";

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'hobbes.cse.buffalo.edu';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = false;                               // Enable SMTP authentication
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('dcl@buffalo.edu', 'Dancestry');

    $mail->isHTML(true); 
    $mail->Subject = 'Dancestry One Time Password';
?>
