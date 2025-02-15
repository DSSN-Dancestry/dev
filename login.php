<?php
require_once 'config.php';  // for getting google client, facebook client, instagram client
include 'util.php';
include 'menu.php';
// my_session_start();
// ^ the above "session start" broke things since util already starts the session?"

// if user already loggedin then redirect to home page
if (isset($_SESSION["user_email_address"])) {
	echo ("<script>location.href='index.php'</script>");
}

// old
// $RedirectURL = 'http://localhost/choreographic-lineage/callback.php';
// $permissions = ['email', 'public_profile'];
// $loginURL = $helper->getLoginURL($RedirectURL, $permissions);
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
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
	<title>Login | Choreographic Lineage</title>
</head>

<body>
	<div class="row login-element">
		<section>
			<div class="credited-image" id="login-image">
				<img src="data/images/TrioForCommonMan_PaulHokanson_cropped.jpg" alt="Tranquil Unrest">
				<span class="image-credits">Image credit: Paul Hokanson</span>
			</div>
			<form id="login_form" name="login_form" method="post" action="login_mediator.php" enctype="multipart/form-data">
				<div class="login_section">
					<fieldset id="login-text">
						<h4 align="center"><strong>LOGIN</strong></h4>
						<h5 align="center">Sign into your account<br><br></h5>
						<?php
						if (isset($_SESSION["login_message"])) {
							echo "<font color=red>" . $_SESSION["login_message"] . "</font>";
							my_session_unset();
						}
						?>
						<div class="row">
							<div class="large-12 column">
								<label for="user_name">Email Address<large style="color:red;font-weight: bold;"> *</large>
									<input type="email" autocomplete="off" type="text" id="user_email_address" name="user_email_address" placeholder="Email Address" required>
								</label>
							</div>
							<div class="large-12 column">
								<label for="user_password">Password <large style="color:red;font-weight: bold;"> *</large>
									<input autocomplete="off" type="password" id="user_password" name="user_password" placeholder="Password" required>
								</label>
							</div>
							<div class="large-12 column">
								<button class="button" id="login-button" type="submit" name="login_submit">
									<span>Login</span>
								</button>
								<a href="forgot_user_password.php" style="float:right;margin-top: 9px">Forgot Password?</a>
							</div>
							<div class="large-12 column" style="display: flex; flex-direction: row; justify-content: space-between;">
								<div style="width: 100%;">
									<hr>
								</div>
								<div style="padding-left: 15px; padding-right: 15px; padding-top: 7px;">
									OR
								</div>
								<div style="width: 100%;">
									<hr>
								</div>
							</div>
							<div class="large-12 column" style="display: flex; justify-content: space-evenly; padding-bottom: 7px;">
								<div>
									<?php echo "<a class='btn btn-primary' href='" . $fbHelper->getLoginUrl(FACEBOOK_LOGIN_MEDIATOR_REDIRECT_URI) . "' role='button'>"; ?>
									<div style="float:left;color:white;background-color:#3b5998;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
										<span><i class="fab fa-facebook-f fa-lg"></i></span>
									</div>
									</a>
								</div>
								<div>
									<?php $googleClient->setRedirectUri(GOOGLE_LOGIN_MEDIATOR_REDIRECT_URI); ?>
									<?php echo "<a class='btn btn-primary' href='" . $googleClient->createAuthUrl() . "' role='button'>"; ?>
									<div style="float:left;color:white;background-color:#dd4b39;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
										<span><i class="fab fa-google fa-lg"></i></span>
									</div>
									</a>
								</div>
								<div>
									<!-- Uncomment the following two lines when instagram login works -->
									<!-- <?php $igClient->set_redirect_uri(INSTAGRAM_LOGIN_MEDIATOR_REDIRECT_URI); ?> -->
									<!-- <?php echo "<a class='btn btn-primary' href='" . $igClient->get_authorize_url() . "' role='button'>"; ?>  -->
									<div style="float:left;color:white;background-color:#bc2a8d;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
										<span><a href="coming_soon.php" style="color: rgba(255,255,255,1)"><i class="fab fa-instagram fa-lg"></i></span>
									</div>
									</a>
								</div>
							</div>
						</div>
						<div class="row column">
							<p>Don't have an account? <a href="register.php">Register Here</a></p>
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