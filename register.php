<?php
include 'util.php';
my_session_start();
include 'menu.php';
?>
<html>

<head>
	<script src="js/platform.js"></script>
	<script type="text/javascript" src="js/browserCheck.js"></script>
	<script>
		window.onload = function() {
			strict_check();
		}
	</script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<title>Login | Dancestry</title>
</head>

<body>
	<?php
	?>
	<div class="row register-element">
		<section>
			<form id="add_user_profile_form" name="add_user_profile_form" method="post" action="user_profile_mediator.php" enctype="multipart/form-data">
				<div class="register_section">
					<fieldset>
						<h4 align="center"><strong>REGISTER</strong></h4>
						<h5 align="center">Create Your Account<br><br></h5>
						<?php
						if (isset($_SESSION["add_user_profile"])) {
							echo "<font color=red>" . $_SESSION["add_user_profile"] . "</font>";
							my_session_unset();
						}
						?>
						<div class="row">
							<div class="small-6 column">
								<label for="user_name">First Name <large style="color:red;font-weight: bold;"> *</large>
									<input autocomplete="off" type="text" id="first_name" name="first_name" placeholder="First Name" required>
								</label>
							</div>
							<div class="small-6 column">
								<label for="user_name">Last Name <large style="color:red;font-weight: bold;"> *</large>
									<input autocomplete="off" type="text" id="last_name" name="last_name" placeholder="Last Name" required>
								</label>
							</div>
							<div class="small-12 column">
								<label for="user_name">Email Address <large style="color:red;font-weight: bold;"> *</large>
									<input autocomplete="off" type="email" id="user_email_address" name="user_email_address" placeholder="Email Address" required>
								</label>
							</div>
							<div class="small-12 column">
								<!-- Terms and conditions dialog -->
								<div class="row">
									<p class="column">
										<input type="checkbox" name="terms" id="terms" value="accepted"> Please accept <a href="javascript:readTermsConditions();">Terms and Conditions</a></input>
									</p>
								</div>

								<div id="extraControls" style="display: none;">
									<div id="dialog-1" style="width:600px;height:700px;" title="TERMS AND CONDITIONS">
										1. You are filling this form out voluntarily.<br>
										2. You are aware that the information you provide, except your email address, will be used as a global resource, accessible to the general public, unless otherwise noted in the survey. <br>
										3. Dancestry will not sell, share or rent your personal information to any third party or use your e-mail address for unsolicited mail. Any emails sent by Dancestry will only be in connection with the Dancestry resource. <br>
										4. The information you provide to Dancestry is accurate to the best of your knowledge. <br>
										5. You are accepting the terms and conditions for your current entries and your future additions to your lineage.<br>
										<br>
										<button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="Accept" onclick="acceptTerms()">
											<span>Accept</span>
										</button>
									</div>
								</div>
							</div>
							<div class="small-12 column">
								<button class="button" id="register-button" type="submit" name="user_profile_submit">
									<span>Register</span>
								</button>
							</div>
						</div>
						<div class="row">
							<p class="column">Already have an account? <a href="login.php">Login Here</a>
								<br>
								<a href="phone_contribution.php">Or, contribute your data via Phone</a>
							</p>
						</div>
					</fieldset>
				</div>
			</form>
		</section>
	</div>
</body>

<link rel="stylesheet" type="text/css" href="css/login.css">

<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	var dialog_open = false;
	$('#add_user_profile_form').submit(function(event) {
		if ($('#terms').is(':checked') == false) {
			event.preventDefault();
			alert("Please accept terms and conditions.");
			return false;
		}
	});

	// if a user clicks "accept terms" in the T&C popup, close the
	// popup and check the checkbox
	function acceptTerms() {
		console.log('accept tnc')
		if (dialog_open) {
			$("#dialog-1").dialog("close");
			document.getElementById("terms").checked = true;
		}
	};

	// display the terms and conditions popup
	function readTermsConditions() {
		$("#dialog-1").dialog({
			width: 600
		});
		$("#dialog-1").dialog("open");
		console.log("ok")
		dialog_open = true;
	}
</script>

</html>