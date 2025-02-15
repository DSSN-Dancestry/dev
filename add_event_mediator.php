<?php

require 'connect.php';

$user_email_address =  $_REQUEST['user_email_address'];
$event_name =  $_REQUEST['event_name'];
$event_location =  $_REQUEST['event_location'];
$event_description =  $_REQUEST['event_description'];
$event_startdate =  $_REQUEST['event_startdate'];
$event_time =  $_REQUEST['event_time'];


$sql = "INSERT INTO event_planner (user_email_id,event_name, event_location, event_description,event_startdate,event_time) VALUES (? ,?, ?,?, ?,?)";

try {
    $conn = getDbConnection();
    $statement = $conn->prepare($sql);
    $statement->execute([$user_email_address ,$event_name, $event_location,$event_description, $event_startdate,$event_time]);

    closeConnections();


    Header("Location: event.php");
} catch (Exception $e) {
    error_log("Error adding event".$e->getMessage());
    Header("Location: add_event.php");
}
