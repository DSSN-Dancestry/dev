<?php

require 'util.php';
my_session_start();

require 'connect.php';

if (isset($_SESSION["user_password"])) {
    // console.log("user password was set");
    $user_password = $_SESSION["user_password"];
    $user_email_address =  $_SESSION['user_email_address'];
} else {
    $user_email_address = $_POST['user_email_address'];
    $user_password =  $_POST['user_password'];
}
$user_password = md5($user_password);


$query = "SELECT * FROM user_profile  
    LEFT JOIN artist_profile ON 
    artist_profile.artist_email_address=user_profile.user_email_address
    WHERE user_email_address=? and user_password=?
    ";



$conn = getDbConnection();
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute([$user_email_address,$user_password]);
$result = $statement->fetchAll();

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
} else {
    $_SESSION["login_message"] = "Incorrect credentials!";
    $location = "login.php";
    error_log('invalid login for '.$user_email_address." - ".$user_password);
}

closeConnections();
echo("<script>location.href='$location'</script>");
