<?php
	include 'util.php';
	my_session_start();
	include 'menu.php';

	if(isset($_SESSION["forgot_user_password"])) {
		$ButtonText = "Change Password";
		unset($_SESSION["forgot_user_password"]);
	}
	else {
		$ButtonText = "Register";
	}
?>
<html>
	<head>
		<title>Set Your Password</title>
		<style type="text/css">
			.back_light_green{background: lightgreen;}
			.font_bolder{font-weight: bolder;}
			.font_size{font-size: 120%}
		</style>
  </head>
	<body>

		<div class="row">
			<div class="medium-8 column">
				<section>
					<form id="add_user_profile_form" name="add_user_profile_form" enctype="multipart/form-data">

						<fieldset>
						<legend><strong>A "one time" password has been sent to your email!  Please check for it,
                        and once you receive it, fill out the below form to reset your password.</legend></strong>
						
						<div id = 'mess'>

						</div>

							<div class="row">
								<div class="small-12 column">
									<label for="user_email_address">Email Address <large style="color:red;font-weight: bold;" > *</large>
										<input  type="email" readonly autocomplete="off" type="text" id="user_email_address" name="user_email_address" <?php if(isset($_SESSION["email"])) echo "value = ".$_SESSION["email"]; ?> placeholder="Email Address" required>
									</label>

								</div>
								<div class="small-12 column">
									<label for="user_email_address">One-time Password <large style="color:red;font-weight: bold;"> *</large><large id="otp_email_text" class="back_light_green font_bolder font_size"> (Check your email for the one-time password) <large>
										<input  autocomplete="off" type="text" id="user_one_time_password" name="user_one_time_password" placeholder="One-time Password" required>
									</label>
									<p id="otperror"></p>
								</div>
								<div class="small-12 column">
									<label for="user_email_address">New Password <large style="color:red;font-weight: bold;"> *</large>
										<input  autocomplete="off" type="password" id="user_new_password" name="user_new_password" placeholder="New Password" required>
									</label>
									<p id="passerror"></p>
								</div>
								<div class="small-12 column">
									<label for="user_email_address">Re-enter New Password <large style="color:red;font-weight: bold;"> *</large>
										<input  autocomplete="off" type="password" id="user_rnew_password" name="user_rnew_password" placeholder="Re-enter New Password" required>
									</label>
									<p id="checkpasserror"></p>
								</div>
								<div class="small-12 column">
								<button id ="pass" type="button" class="button">
									<span><?php echo $ButtonText; ?></span>
								</button>

								</div>
							</div>
						</fieldset>
					</form>
				</section>
			</div>
		</div>

		<script>
			function passSet(maindta) 
			{
				var email = document.getElementById('user_email_address').value;
				var password = document.getElementById('user_new_password').value;
				var url = 'set_user_password_mediator.php';
				var form = $('<form action="' + url + '" method="post">' +
				'<input type="text" name="email" value="' + email + '" />' +
				'<input type="text" name="pwd" value="' + password + '" />' +
				'</form>');
				$('body').append(form);
				form.submit();  
			}
		</script>
	</body>
<?php
	include 'footer.php';
?>
</html>

<script src="set_password_validation.js"></script>
<script src="submit_database_request.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
<script type="text/javascript">
document.getElementById('pass').onclick = function() {
	if(validateSetForm())
	{
		submitJsonOld(null, 'usercontroller.php', {'action':'getUserProfile','useremailaddress':document.getElementById('user_email_address').value,'useronetimepassword':document.getElementById('user_one_time_password').value}, checkOTP);
		// submitJsonOld(null, 'usercontroller.php', {'action':'updatePastProfile','useremailaddress':document.getElementById('user_email_address').value}, loadUserProfile);
	}
	else
	{
		alert('Passwords Do not match');
	}
}

var user_profile = '';
function checkProfile(maindta){
	// 1 - Profile not found
	if (!maindta.hasOwnProperty('user_profile'))
	{
		document.getElementById('mess').innerHTML = '<?php
		echo "<font color=red>".'Profile Does Not Exists! Please check your email address!'."</font></strong></legend>";
		?>';
		console.log('profile not found');
	}
	// 2 - OTP mismatch
	else
	{
		document.getElementById('mess').innerHTML = '<?php
		echo "<font color=red>".'Incorrect one-time password! Please check your email or request new one-time password!'."</font></strong></legend>";
		?>';
		console.log('otp mismatch');
	}
}

function checkOTP(maindta)
{
	// 1 - OTP mismatch
	if (!maindta.hasOwnProperty('user_profile'))
	{
		// Check profile
		submitJsonOld(null, 'usercontroller.php', {'action':'getUserProfile','useremailaddress':document.getElementById('user_email_address').value}, checkProfile);
	}
	// 2 - OTP match
	else{
		console.log('otp success');
		// update past profile
		user_profile = maindta['user_profile'];
		submitJsonOld(null, 'usercontroller.php', {'action':'updatePastProfile','useremailaddress':document.getElementById('user_email_address').value}, loadUserProfile);
	}
}

// set password
function loadUserProfile(maindta) 
{
	var userID 	= user_profile[0].user_id;
	var otp 	= Math.random() * (+100000 - +999999) + +999999; 
	var fName 	= user_profile[0].user_first_name;
	var lName 	= user_profile[0].user_last_name;
	var emailId = user_profile[0].user_email_address;
	var pasWd 	= document.getElementById('user_new_password').value;
	var uType 	= user_profile[0].user_type;

	submitJsonOld(null, 'usercontroller.php', 
	{'action':'addOrEditUserProfile','userid':userID, 'useremailaddress':document.getElementById('user_email_address').value,'useronetimepassword':otp,'userpassword':document.getElementById('user_new_password').value
	,'userfirstname':fName, 'userlastname':lName, 'usertype':uType }, passSet);
}
</script>