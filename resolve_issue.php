<?php
include 'connection_open.php';
include 'util.php';
my_session_start();

if (isset($_SESSION["user_email_address"])) {
    $id = $_GET['id'];



    $sql = "UPDATE bugs SET status = '0' WHERE id = '$id';";

    if (mysqli_query($dbc, $sql)) {
        mysqli_close($dbc);
        header('Location: bug_report_list.php');
        exit;
    } else {
        echo "Error marking the bug as resolved";
        header('Location: bug_report_list.php');
    }
} else {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
}
