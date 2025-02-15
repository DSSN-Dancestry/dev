<?php
include 'util.php';
my_session_start();
require 'connect.php';

$first_name = $_POST['first_name'];
$last_name =  $_POST['last_name'];
$user_email_address =  $_POST['user_email_address'];
$query = "SELECT * FROM user_profile
WHERE user_email_address=?";
$conn = getDbConnection();
$statement_id = $conn->prepare($query);
$statement_id->setFetchMode(PDO::FETCH_ASSOC);
$statement_id->execute([$user_email_address]);
$result = $statement_id->fetchAll();

$count = $statement_id->rowCount();

if ($count == 0) {
    // if not using SSO
    if (!isset($_POST['sso'])) {
        $user_password = "PGlYFveq56MdwCoEiCaC";
        $user_one_time_password = rand(100000, 999999);

        include 'php/lib/PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        $mail->isSMTP();
        // Set mailer to use SMTP
        $mail->Host = 'hobbes.cse.buffalo.edu';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;                               // Enable SMTP authentication
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('no-reply@buffalo.edu', 'Dancestry');

        $mail->isHTML(true);
        $mail->addReplyTo('aceto@buffalo.edu', 'Melanie Aceto');
        $mail->addCustomHeader('MIME-Version: 1.0');
        $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
        $mail->Subject = 'Welcome to Dancestry';
        $message = "Hello $first_name $last_name,<br/>Welcome to Dancestry. You will need to set up your password in order to log onto the Lineage Contribution form. Please use the one time password mentioned below to log into your account and create your own password.<br/><br/>One Time Password: $user_one_time_password<br/><br/>Thank You,<br/>The Dancestry Team";
        $mail->addAddress($user_email_address);
        $mail->Body    = $message;
        $mail->send();
        $query = "INSERT INTO user_profile (user_first_name,user_last_name,	user_email_address,	user_password,	user_one_time_password)
	             VALUES	(?,?,?,?,?)";

        $statement_id = $conn->prepare($query);

        $statement_id->execute([$first_name, $last_name, $user_email_address, $user_password, $user_one_time_password]);


        $_SESSION["set_user_password"] = "Check your email for a one-time password";
        $_SESSION["email"] = $user_email_address;
        $location = "set_user_password.php";
    } else {   // if using sso
        if ($_POST['sso'] == 'yes') {
            // insert into db
            $user_password = "PGlYFveq56MdwCoEiCaC";
            $user_one_time_password = rand(100000, 999999);
            $query = "INSERT INTO user_profile (user_first_name,user_last_name,	user_email_address,	user_password,	user_one_time_password)
	                    VALUES	(?,?,?,?,?)";
            $statement_id = $conn->prepare($query);
            $statement_id->execute([$first_name, $last_name, $user_email_address, $user_password, $user_one_time_password]);

            // setup logged in state
            $query = "SELECT * FROM user_profile  
                LEFT JOIN artist_profile ON 
                artist_profile.artist_email_address=user_profile.user_email_address
                WHERE user_email_address=?
            ";

            $conn = getDbConnection();
            $statement = $conn->prepare($query);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute([$user_email_address]);
            $result = $statement->fetchAll();

            $_SESSION["user_email_address"] = $user_email_address;
            $firstrow = $result[0];
            $_SESSION["user_firstname"] = $firstrow["user_first_name"];
            $_SESSION["user_lastname"] = $firstrow["user_last_name"];
            $_SESSION["user_id"] = $firstrow["user_id"];
            $_SESSION["user_type"] = $firstrow["user_type"];
            $_SESSION["profile_id"] = $firstrow["artist_profile_id"];
            if ($firstrow['user_type'] == 'User') {
                $location = "profiles.php";
            } else {
                $_SESSION["user_type"] = 'Admin';
                $location = "index.php";
            }
            // logged in state setup end

            echo ("<script>location.href='$location'</script>");
        }
    }
} else {
    $_SESSION["add_user_profile"] = "A user with that email already exists in our system.";
    $location = "register.php";
}
closeConnections();

header("Location: " . $location . "");
