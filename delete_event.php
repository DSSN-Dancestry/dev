<?php
require 'connection_open.php';
require 'util.php';
require 'utils.php';
my_session_start();
//checkAdmin();


if (isset($_SESSION["user_email_address"])) {
    $user_email_address=$_SESSION["user_email_address"];
    $user_firstname=$_SESSION["user_firstname"];
    $user_lastname=$_SESSION["user_lastname"];
    $user_name=$user_firstname." ".$user_lastname;
    $id = $_GET['id'];
    $sql = "DELETE FROM event_planner WHERE event_id = $id";

    if (mysqli_query($dbc, $sql)) {
        mysqli_close($dbc);
        header('Location: event.php');
        exit;
    } else {
        echo "Error deleting record";
        header('Location: event.php');
    }
} else {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
}
