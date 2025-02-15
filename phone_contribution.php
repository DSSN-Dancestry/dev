<?php
include 'util.php';
my_session_start();

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
	$location = "contribution_introduction.php";
} else {
	$location = "login.php";
}
?>


<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Personal Information</title>
	<link rel="stylesheet" href="css/intlTelInput.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/intlTelInput.js"></script>
	<script src="js/utils.js"></script>
	<style type="text/css">
		.iti-flag {
			background-image: url("img/flags.png");
		}

		#prev_save_row {
			display: flex;
			justify-content: space-between;
		}

		#prev_button {
			width: unset;
			padding-left: 10px;
		}

		#submit_button {
			padding-right: 10px;
		}

		#previous,
		#submit {
			margin: 0px;
		}

		@media only screen and (max-width: 1000px) {
			#prev_button {
				width: 100%;
				padding-right: 5px;
			}

			#submit_button {
				width: 100%;
				text-align: right;
				padding-top: 0px;
				padding-left: 5px;
			}

			#previous,
			#submit {
				width: 100%;
			}
		}

		@media only screen and (-webkit-min-device-pixel-ratio: 2),
		only screen and (min--moz-device-pixel-ratio: 2),
		only screen and (-o-min-device-pixel-ratio: 2 / 1),
		only screen and (min-device-pixel-ratio: 2),
		only screen and (min-resolution: 192dpi),
		only screen and (min-resolution: 2dppx) {
			.iti-flag {
				background-image: url("img/flags@2x.png");
			}
		}
	</style>

</head>

<body>

	<form id="biography" class="biography" action="appointment_confirmation.php" method="post">
		<div class="row">
			<div class="column">
				<h3><strong>CONTRIBUTE YOUR LINEAGE BY PHONE</strong></h3>
			</div>
			<div class="column">
				<h5>Please share days and times that are good for someone from Dancestry to contact you regarding your lineage.</h5>
			</div>
		</div>

		<div class="row">
			<div class="medium-4 column">
				<label for="artist_first_name">First Name <large style="color:red;font-weight: bold;"> *</large>
					<input autocomplete="off" type="text" id="first_name" name="first_name" placeholder="First Name" required>
				</label>
			</div>
			<div class="medium-4 column">
				<label for="artist_last_name">Last Name <large style="color:red;font-weight: bold;"> *</large>
					<input autocomplete="off" type="text" id="last_name" name="last_name" placeholder="Last Name" required>
				</label>
			</div>
			<div class="medium-4 column">

			</div>
			<!-- FIRST NAME AND LAST NAME -->
		</div>
		<div class="row">
			<div class="medium-4 column">
				<label for="artist_email_address">Email Address <large style="color:red;font-weight: bold;"> *</large>
					<input autocomplete="off" type="email" id="email_address" name="email_address" placeholder="Email Address">
				</label>
			</div>
		</div>

		<div class="row">
			<div class="medium-10 columns">
				<label for="contact_number">Contact Number <large style="color:red;font-weight: bold;"> *<br></large>
					<input type="tel" name="contact_number" class="contact_number" id="contact_number" placeholder="Enter 10 digits only" pattern="[1-9]{1}[0-9]{9}" required>

				</label>
			</div>
		</div>

		<div class="row">
			<div class="medium-4 column">
				<label for="artist_email_address"><br>Send us a note
					<textarea placeholder="Feel free to add a note regarding your appointment." rows="4" name="note" class="note" id="note"></textarea>
				</label>
			</div>
		</div>

		<div class="row">
			<div id="prev_save_row">
				<div id="prev_button">
					<button class="primary button" name="previous" id="previous">
						<span>Previous</span>
					</button>
				</div>
				<div id="submit_button">
					<button class="button" type="submit" name="submit" id="submit">
						<span>Submit</span>
					</button>
				</div>
			</div>
		</div>
	</form>
</body>

</html>

<script>
	$("#contact_number").intlTelInput();
	$("#previous").click(function() {
		var location = "<?php echo $location ?>";
		window.location.href = location;
	});
</script>



<?php
include 'footer.php';
?>