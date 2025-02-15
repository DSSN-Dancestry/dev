<?php


include 'connection_open.php';

$user_email_address = mysqli_real_escape_string($dbc, $_REQUEST['user_email_address']);
$event_name = mysqli_real_escape_string($dbc, $_REQUEST['event_name']);
$event_location = mysqli_real_escape_string($dbc, $_REQUEST['event_location']);
$event_description = mysqli_real_escape_string($dbc, $_REQUEST['event_description']);
$event_startdate = mysqli_real_escape_string($dbc, $_REQUEST['event_startdate']);
$event_time = mysqli_real_escape_string($dbc, $_REQUEST['event_time']);
$id = $_GET['id'];

$query = "UPDATE event_planner SET event_name='$event_name', event_location='$event_location', event_description='$event_description', event_startdate='$event_startdate', event_time='$event_time' where event_id=$id ;";
$event_profile = mysqli_query($dbc,$query) or die('Error querying database.: '  .mysqli_error($dbc));

Header("Location: event_details.php?id=$id");

mysqli_close($dbc);

?>
