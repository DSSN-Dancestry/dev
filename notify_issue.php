<?php
include 'connection_open.php';
include 'util.php';
my_session_start();
$showEventMenu = true;

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
    $id = $_GET['id'];
    $query = "select * from bugs where id= '$id'";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  . mysqli_error($dbc));

    $query2 = "select comment from bug_comments where id= '$id'  ORDER BY uploaded_on DESC LIMIT 1";
    $event_profile2 = mysqli_query($dbc, $query2) or die('Error querying database.: '  . mysqli_error($dbc));
    $row2 = mysqli_fetch_array($event_profile2);

    while ($row = mysqli_fetch_array($event_profile)) {
        $mail = new PHPMailer;
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = 'hobbes.cse.buffalo.edu';                         // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;                                 // Enable SMTP authentication
        $mail->Port = 587;

        $mail->setFrom('no-reply@buffalo.edu', 'Dancestry');
        $mail->addCustomHeader('MIME-Version: 1.0');
        $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
        $mail->addAddress($row['user_email_id'], $row['user_name']);       // Add a recipient
        $mail->addReplyTo('lineagechoreographic@gmail.com', 'Dancestry');

        $message = '
    <html>
    <head>
      <title>' . $row['issue_title'] . '</title>
    </head>
    <body>
      <p>Hi ' . $row['user_name'] . ',</p>
      <p>The status of the bug you reported has been updated.</p>
      <p><b>The bug reported was:</b>' . $row['user_comment'] . '</p>
      <p>The following comment was added by the admin. Please reply to this mail accordingly. </p>
      <p><b>Comment :</b>' . $row2['comment'] . ' </p>

      <br/>
      <p> Thanks and Regards,</p>
      <p>The Dancestry Team</p>
      </body>
    </html>';

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $row['issue_title'];
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
    echo "<script type='text/javascript'>alert('Client notified Successfully');
var url = 'issue.php';
url += '?id=$id';
window.location.href = url;
</script>";
} else {
    $location = "login.php";
    echo ("<script>location.href='$location'</script>");
}
