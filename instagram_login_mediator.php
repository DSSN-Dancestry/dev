<?php
require 'util.php';
require_once 'config.php';  // for getting instagram client
my_session_start();

require 'connect.php';

echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';

// if user already logged in
if (isset($_SESSION["user_email_address"])) {
    $user_email_address =  $_SESSION['user_email_address'];
} else {  // if user is not logged in follow the flow of google OAuth to retrieve details of the user
    try{
        if (isset($_GET['code'])) {
            $igClient->set_redirect_uri(INSTAGRAM_LOGIN_MEDIATOR_REDIRECT_URI);
            $token = $igClient->get_access_token($_GET['code']);
            $igClient->set_access_token($token->access_token);
            $ig_user_id = strval($token->user_id);
            $token = ($igClient->get_long_lived_token())->access_token;
            $ig_user = $igClient->get_user($ig_user_id);
            // var_dump($ig_user);
            $user_email_address = $ig_user->username.'@ig.com';
            $user_first_name = $ig_user->username;
            $user_last_name = $ig_user->username;
        }
    }
    // if any error in using auth code -> then redirect to login page
    catch (Exception $e){
        echo("<script>location.href='login.php'</script>");
    }
}

// check if the user exists already in db
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

// if user already exists in db then set up the session and log in the user
if ($result[0]) {
    $_SESSION["user_email_address"] = $user_email_address;
    $firstrow = $result[0];
    $_SESSION["user_firstname"] = $firstrow["user_first_name"];
    $_SESSION["user_lastname"] = $firstrow["user_last_name"];
    $_SESSION["user_id"] = $firstrow["user_id"];
    $_SESSION["user_type"] = $firstrow["user_type"];
    $_SESSION["profile_id"]=$firstrow["artist_profile_id"];
    if ($firstrow['user_type']=='User') {
        $location = "profiles.php";
    } else {
        $_SESSION["user_type"] = 'Admin';
        $location = "index.php";
    }
    echo("<script>location.href='$location'</script>");
} else {  // if the user does not exist in db then register the user with email from google oAuth flow
    echo('
    <form name="detailsForm" method="post" action="user_profile_mediator.php" hidden>
        <input type="text" name="sso" value="yes">
        <input type="text" name="first_name" value="'.$user_first_name.'">
        <input type="text" name="last_name" value="'.$user_last_name.'">
        <input type="text" name="user_email_address" value="'.$user_email_address.'">
        <button type="submit">submit</button>
    </form>
    <script>
        window.onload=function(){
            document.forms["detailsForm"].submit();
        };
    </script>
    ');
    // echo('<script>
    // $.ajax({
    //     type: "POST",
    //     url: "user_profile_mediator.php",
    //     data: {
    //         first_name: "ajax_first",
    //         last_name: "ajax_last",
    //         user_email_address: "ajax_email@gmail.com"
    //     },
    //     success: function(data){
    //         console.log("post request sent to register user from google oAuth flow");
    //         // location.href="profiles.php";
    //         // window.location.replace("user_profile_mediator.php");
    //     },
    //     error: function(xhr, status, error){
    //         console.log(xhr);
    //     }
    // });
    // </script>');
}

closeConnections();
