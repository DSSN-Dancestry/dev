<?php
include 'php/lib/PHPMailer/PHPMailerAutoload.php';
$user_data = $_POST['artist_profile_information'];
$parent_artist_profile_first_name = $user_data['parent_artist_profile_first_name'];
$parent_artist_profile_last_name = $user_data['parent_artist_profile_last_name'];
$artist_first_name = $user_data['artist_first_name'];
$artist_last_name = $user_data['artist_last_name'];
$user_email_address = $user_data['parent_artist_profile_email_address'];
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'hobbes.cse.buffalo.edu';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = false;                               // Enable SMTP authentication
  $mail->Port = 587;                                    // TCP port to connect to

  $mail->setFrom('no-reply@buffalo.edu', 'Dancestry');

$mail->isHTML(true);
$mail->addReplyTo('aceto@buffalo.edu', 'Melanie Aceto');
$mail->addCustomHeader('MIME-Version: 1.0');
$mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
$mail->Subject = 'Dancestry';

$message = "Dear $parent_artist_profile_first_name,
<br>
<br>
Thank you for making a Dancestry profile for $artist_first_name $artist_last_name.  $artist_first_name has taken over ownership of their own profile, which is why you no longer see it in your list of Another Artists. We thank you for your interest and contributions to Dancestry and hope you will visit our platform again soon.
<br>
<br>
Many thanks,<br>
The Dancestry Team
";
$mail->addAddress($user_email_address);
$mail->Body = $message;
$mail->send();
