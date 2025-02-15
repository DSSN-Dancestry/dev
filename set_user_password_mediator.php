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

<!DOCTYPE html>
<html>
<head>
    <title>Password Set</title>
</head>
<body>

<body>
<div class="confirmation_container">
    <div class="row">
        <div class="small-12 medium-8 large-8 small-centered columns">
            <h2 class="text-center">
                <strong><?php
                   
                       echo "PASSWORD SET SUCCESSFULLY";
							$_SESSION["user_email_address"] = $_POST['email'];
                        $_SESSION["user_password"] = $_POST['pwd'];
                   
                    ?><br>
                </strong>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="small-12 medium-8 large-8 small-centered columns">
            <h4 class="text-center"><em>Thank You for joining Dancestry!! You will now be able to contribute your lineage.</em></h4>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="button_container small-12 medium-8 large-4 small-centered columns">
            <a href="login_mediator.php" class="button expanded radius text-center">Contribute Your Lineage</a>
        </div>
    </div>
</div>
</div>
</body>

</body>
</html>