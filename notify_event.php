<?php
include 'connection_open.php';
include 'util.php';
my_session_start();

$showEventMenu = true;

include 'menu.php';
require_once('php/lib/PHPMailer/PHPMailerAutoload.php');


if (isset($_SESSION["user_email_address"])) {
  $user_email_address = $_SESSION["user_email_address"];
  $user_firstname = $_SESSION["user_firstname"];
  $user_lastname = $_SESSION["user_lastname"];
  $user_name = $user_firstname . " " . $user_lastname;
  $user_email_id = $_SESSION["user_email_address"];
  $query = "select * from artist_relation where artist_email_id_1= '$user_email_id'  and artist_email_id_2 != ''";
  $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  . mysqli_error($dbc));
  $id = $_GET['id'];

  $query2 = "SELECT * FROM event_planner WHERE event_id=$id;";
  $event_profile2 = mysqli_query($dbc, $query2) or die('Error querying database.: '  . mysqli_error($dbc));
  $row2 = mysqli_fetch_array($event_profile2);
  $event = $row2['event_name'];
  $flag = 0;

  while ($row = mysqli_fetch_array($event_profile)) {
    $mail = new PHPMailer;
    $mail->isSMTP();                                        // Set mailer to use SMTP
    $mail->Host = 'hobbes.cse.buffalo.edu';                         // Specify main and backup SMTP servers
    $mail->SMTPAuth = false;                                 // Enable SMTP authentication
    $mail->Port = 587;

    $mail->setFrom('no-reply@buffalo.edu', 'Dancestry');
    $mail->addCustomHeader('MIME-Version: 1.0');
    $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
    $mail->addAddress($row['artist_email_id_2'], $row['artist_name_2']);       // Add a recipient
    $mail->addReplyTo('lineagechoreographic@gmail.com', 'Dancestry');

    $message = '
   <html>
   <head>
     <title>' . $row2['event_name'] . '</title>
   </head>
   <body>
     <p>Hi ' . $row['artist_name_2'] . ',</p>
     <p>Your connection <b>' . $user_name . '</b> has posted a new event.</p>
     <p>Event Name :' . $row2['event_name'] . ' </p>
     <p>For more details please login to <a href="http://www.choreographiclineage.buffalo.edu/index.php"> Dancestry</a> </p>
     <br/>
     <p> Thanks and Regards,</p>
     <p>The Dancestry Team</p>
     </body>
   </html>';

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $row2['event_name'];
    $mail->Body = $message;

    if (!$mail->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
      $flag = 1;
    }
  }
  if ($flag == 0) {
    date_default_timezone_set('America/New_York');
    $date = date('Y-m-d H:i:s', time());
    $query = "UPDATE event_planner SET last_notified='$date' WHERE event_id=$id;";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  . mysqli_error($dbc));
    $location = "notify_successful.php?eventname=$event";
    echo ("<script>location.href='$location'</script>");
  } else {
?>
    <script type="text/javascript">
      function cnf() {
        if (confirm("Failure in notifying connections"))
          location.href = 'event.php';
      }
    </script>
<?php
    echo "<script> cnf(); </script>";
  }
} else {
  $location = "login.php";
  echo ("<script>location.href='$location'</script>");
}
?>