<?php
// the response will be a JSON object
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$json_params = json_decode(file_get_contents('php://input'), true);

$first_name =  $json_params['first_name'];
$last_name =  $json_params['last_name'];
// $user_email_address =  $json_params['user_email_address'];
$user_genres =  $json_params['user_genres'];

include 'php/lib/PHPMailer/PHPMailerAutoload.php';
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
$mail->Subject = 'Additional Genres';

$message = "The following genres have been added by $first_name $last_name:<br>$user_genres<br><br>Thank You,<br>The Dancestry Team";
$mail->addAddress("aceto@buffalo.edu");
$mail->Body    = $message;
$mail->send();
$result["Success"] = True;
echo json_encode($result);
?>
