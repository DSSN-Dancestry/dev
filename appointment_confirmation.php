<script>
	Date.prototype.yyyymmdd = function() {
		var mm = this.getMonth() + 1; // getMonth() is zero-based
		var dd = this.getDate();

		return [(mm > 9 ? '' : '0') + mm,
			(dd > 9 ? '' : '0') + dd,
			this.getFullYear()
		].join('/');
	};

	var date = new Date();
	var a = date.yyyymmdd();
</script>
<?php
include 'util.php';
my_session_start();

include 'menu.php';

include 'connection_open.php';

$firstName = mysqli_real_escape_string($dbc, $_POST['first_name']);
$lastName =  mysqli_real_escape_string($dbc, $_POST['last_name']);
$email =  mysqli_real_escape_string($dbc, $_POST['email_address']);
$contact = mysqli_real_escape_string($dbc, $_POST['contact_number']);
$note = mysqli_real_escape_string($dbc, $_POST['note']);
$status = "Undone";
$dated = date("m/d/Y");

include 'php/lib/PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'hobbes.cse.buffalo.edu';  // Specify main and backup SMTP servers
$mail->SMTPAuth = false;                               // Enable SMTP authentication
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('no-reply@buffalo.edu', 'Dancestry');

$mail->isHTML(true);
$mail->addReplyTo('Dancestryglobal@gmail.com', 'Dancestry');
$mail->addCustomHeader('MIME-Version: 1.0');
$mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
$mail->Subject = "Dancestry Appointment";

$message = "Thank you for your interest in Dancestry. A team member will contact you soon to arrange a lineage appointment.<br/><br/>Thank you,<br/>Dancestry Team<br>Dancestryglobal@gmail.com<br>+1(716) 645-0605";
$mail->addAddress($email);
$mail->Body = $message;
$mail->send();


$query = "INSERT INTO phone_appointments
	(first_name, last_name, email, contact,note,status,Submitted_Date)
	VALUES
	('$firstName','$lastName','$email','$contact','$note','$status','$dated')";

$result = mysqli_query($dbc, $query)
	or die('Error querying database.: '  . mysqli_error($dbc));

include 'connection_close.php';


?>

<!DOCTYPE html>
<html>

<head>
	<title>Appointment Confirmation</title>
</head>
<style type="text/css">
	.confirmation_container {
		margin-top: 6%;
	}

	.p {
		word-wrap: normal;
	}

	.button_container {
		margin: auto;
	}
</style>

<body>
	<div class="confirmation_container">
		<div class="row">
			<div class="small-12 medium-8 large-8 small-centered columns">
				<h2 class="text-center">Thank you for your interest in Dancestry</h2>
				<h4 class="text-center"><em>A team member will contact you soon to arrange a lineage appointment.</em></h4>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-8 large-8 small-centered columns">
				<p class="text-center">Please check your inbox at <br>
					<strong><?php echo $email ?></strong>
				</p>
			</div>
		</div>
		<hr width="30%">

		<div class="row">
			<div class="small-12 medium-8 large-5 small-centered columns">
				<h4 class="text-center">Having trouble or need to reschedule?</h4>
			</div>
			<p class="text-center"> Contact us at Dancestryglobal@gmail.com &nbsp; OR &nbsp; <strong><a>+1(716) 645-0605</a></strong><br></p>
		</div>
		<div class="row">
			<div class="button_container small-12 medium-8 large-2 small-centered columns">
				<a href="profiles.php" class="button radius text-center">BACK TO HOME</a>
			</div>
		</div>
	</div>
	</div>
</body>

</html>