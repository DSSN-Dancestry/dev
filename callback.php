<?php
require_once('config.php');
require_once('util.php');
require 'connect.php';
my_session_start();
try {
  $helper->getPersistentDataHandler()->set('state', $_GET['state']);
  $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (!isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

$fb->setDefaultAccessToken($accessToken);
$response = $fb->get('/me?fields=first_name,last_name,email,id');
$userNode = $response->getGraphUser();
$user_email_address = $userNode['email'];
$first_name = $userNode['first_name'];
$last_name = $userNode['last_name'];
$provider_user_id = $userNode['id'];

#always prompt to create new password if they are the first time user
#email shouldnt be the primary key (on social_tokens) as user can change their email (on user_profile)

#1st scenario: first time user (no data on user_profile nor social_tokens)
#2nd scenario: an existing user without social login (data on user_profile but not social_tokens)
#3rd scenario: an existing user with social login (then must have registered manually before)

#flow
#Case 1: a user with both social account and password
#1)
$query = "SELECT * FROM social_tokens
WHERE provider_user_id = ? AND provider_type = ?";                  //? - a unique parameter marker to be later passed when executing
$conn = getDbConnection();
$statement_id = $conn->prepare($query);                   //prepare query to be executed by PDO::execute
$statement_id->setFetchMode(PDO::FETCH_ASSOC);            //return next row as an array indexed by column name (user_email_address)
$statement_id->execute([$provider_user_id, 'facebook']);   //pass $user_email_address to the prepared statement ($query)
$result = $statement_id->fetchAll();                      //returns an array containing the remain rows in the result set
$count = $statement_id->rowCount();

#a user without social account or a completely new user
if ($count == 0) {
  $query = "SELECT * FROM user_profile
  WHERE user_email_address = ?";
  $statement = $conn->prepare($query);
  $statement->setFetchMode(PDO::FETCH_ASSOC);
  $statement->execute([$user_email_address]);
  $result = $statement->fetchAll();
  $count1 = $statement->rowCount();
  #a completely new user - prompt to create a new account
  if ($count1 == 0) {
    $user_password = "PGlYFveq56MdwCoEiCaC";
    $user_one_time_password = rand(100000, 999999);

    include 'php/lib/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

    $mail->isSMTP();                                 // Set mailer to use SMTP
    $mail->Host = 'hobbes.cse.buffalo.edu';          // Specify main and backup SMTP servers
    $mail->SMTPAuth = false;                         // Enable SMTP authentication
    $mail->Port = 587;                               // TCP port to connect to

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
    #add user profile
    $query = "INSERT INTO user_profile (user_first_name,user_last_name,	user_email_address,	user_password,	user_one_time_password)
	             VALUES	(?,?,?,?,?)";
    $statement_id = $conn->prepare($query);
    $statement_id->execute([$first_name, $last_name, $user_email_address, $user_password, $user_one_time_password]);
    $id = $conn->lastInsertId();
    #add social profile for this new user
    $query = "INSERT INTO social_tokens (provider_user_id,provider_type, user_id)
	            VALUES	(?,?,?)";
    $statement = $conn->prepare($query);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute([$provider_user_id, 'facebook', $id]);

    $_SESSION["set_user_password"] = "Check your email for a one-time password";
    $_SESSION["email"] = $user_email_address;
    $location = "set_user_password.php";
  } else { #an existing user without social account
    $id = $result[0]["user_id"];
    $query = "INSERT INTO social_tokens (provider_user_id,provider_type,user_id)
	            VALUES	(?,?,?)";
    $statement = $conn->prepare($query);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute([$provider_user_id, 'facebook', $id]);

    $query = "SELECT * FROM user_profile  
    LEFT JOIN artist_profile ON 
    artist_profile.artist_email_address=user_profile.user_email_address
    WHERE user_email_address=?
    ";

    $statement = $conn->prepare($query);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute([$email_address]);
    $result = $statement->fetchAll();

    $_SESSION["user_email_address"] = $email_address;
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
      $location = "profiles.php";
    }
  }
} else { #a user with a social account -> must have registered manually before
  $user_id = $result[0]["user_id"];

  #retrieve user email address based on unique user_id
  $query = "SELECT * FROM user_profile  
    INNER JOIN social_tokens ON 
    user_profile.user_id=social_tokens.user_id 
    WHERE user_profile.user_id = ? 
    ";
  $statement = $conn->prepare($query);
  $statement->setFetchMode(PDO::FETCH_ASSOC);
  $statement->execute([$user_id]);
  $result = $statement->fetchAll();
  $email_address = $result[0]["user_email_address"];

  #retrieve user profile joint with artist_profile given the email_address
  $query = "SELECT * FROM user_profile  
    LEFT JOIN artist_profile ON 
    artist_profile.artist_email_address=user_profile.user_email_address
    WHERE user_email_address=?
    ";

  $statement = $conn->prepare($query);
  $statement->setFetchMode(PDO::FETCH_ASSOC);
  $statement->execute([$email_address]);
  $result = $statement->fetchAll();

  $_SESSION["user_email_address"] = $email_address;
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
    $location = "profiles.php";
  }
}
closeConnections();
echo ("<script>location.href='$location'</script>");
