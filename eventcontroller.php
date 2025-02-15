<?php

//testing
require 'utils.php';
require 'connect.php';

// the response will be a JSON object
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");$json = array();
// pull the input, which should be in the form of a JSON object
$json_params = file_get_contents('php://input');
// check to make sure that the JSON is in a valid format
if (isValidJSON($json_params)) {
    //load in all the potential parameters.  These should match the database columns for the objects.
    $conn = getDbConnection();
    $decoded_params = json_decode($json_params, true);
    $action = $decoded_params['action'];
    $json['action'] = $action;
    // uncomment the following line if you want to turn PHP error reporting on for debug - note, this will break the JSON response
    //ini_set('display_errors', 1); error_reporting(-1);
    $eventId = "";
    if (array_key_exists('eventid', $decoded_params)) {
        $eventId =  $decoded_params['eventid'];
    }
    $userEmailId = "";
    if (array_key_exists('useremailid', $decoded_params)) {
        $userEmailId =  $decoded_params['useremailid'];
    }
    $eventName = "";
    if (array_key_exists('eventname', $decoded_params)) {
        $eventName =  $decoded_params['eventname'];
    }
    $eventLocation = "";
    if (array_key_exists('eventlocation', $decoded_params)) {
        $eventLocation =  $decoded_params['eventlocation'];
    }
    $eventDescription = "";
    if (array_key_exists('eventdescription', $decoded_params)) {
        $eventDescription =  $decoded_params['eventdescription'];
    }
    $eventStartdate = "";
    if (array_key_exists('eventstartdate', $decoded_params)) {
        $eventStartdate =  $decoded_params['eventstartdate'];
    }
    $eventTime = "";
    if (array_key_exists('eventtime', $decoded_params)) {
        $eventTime =  $decoded_params['eventtime'];
    }
    $current = "";
    if (array_key_exists('current', $decoded_params)) {
        $current =  $decoded_params['current'];
    }
    if ($action == "addOrEditEventPlanner") {
        $args = array();
        if (IsNullOrEmpty($eventId)) {
            $sql = "INSERT INTO event_planner (event_id,user_email_id,event_name,event_location,event_description,event_startdate,event_time) VALUES ( ?,?,?,?,?,?,?);";
            array_push($args, $eventId);
            array_push($args, $userEmailId);
            array_push($args, $eventName);
            array_push($args, $eventLocation);
            array_push($args, $eventDescription);
            array_push($args, $eventStartdate);
            array_push($args, $eventTime);
            try {
                $statement = $conn->prepare($sql);
                $statement->execute($args);
                $last_id = $conn->lastInsertId();
                $json['Record Id'] = $last_id;
                $json['Status'] = "SUCCESS - Inserted Id $last_id";
            } catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
        } else {
            $sql = "UPDATE event_planner SET user_email_id = ?,event_name = ?,event_location = ?,event_description = ?,event_startdate = ?,event_time = ? WHERE event_id = ?; ";
            array_push($args, $userEmailId);
            array_push($args, $eventName);
            array_push($args, $eventLocation);
            array_push($args, $eventDescription);
            array_push($args, $eventStartdate);
            array_push($args, $eventTime);
            array_push($args, $eventId);
            try {
                $statement = $conn->prepare($sql);
                $statement->execute($args);
                $count = $statement->rowCount();
                if ($count > 0) {
                    $json['Status'] = "SUCCESS - Updated $count Rows";
                } else {
                    $json['Status'] = "ERROR - Updated 0 Rows - Check for Valid Ids ";
                }
            } catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
            $json['Action'] = $action;
        }
    } elseif ($action == "deleteEventPlanner") {
        $sql = "DELETE FROM event_planner WHERE event_id = ?";
        $args = array();
        array_push($args, $eventId);
        if (!IsNullOrEmpty($eventId)) {
            try {
                $statement = $conn->prepare($sql);
                $statement->execute($args);
                $count = $statement->rowCount();
                if ($count > 0) {
                    $json['Status'] = "SUCCESS - Deleted $count Rows";
                } else {
                    $json['Status'] = "ERROR - Deleted 0 Rows - Check for Valid Ids ";
                }
            } catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
        } else {
            $json['Status'] = "ERROR - Id is required";
        }
        $json['Action'] = $action;
    } elseif ($action == "getEventPlanner") {
        $args = array();
        $sql = "SELECT * FROM event_planner";
        $first = true;
        if (!IsNullOrEmpty($eventId)) {
            if ($first) {
                $sql .= " WHERE event_id = ? ";
                $first = false;
            } else {
                $sql .= " AND event_id = ? ";
            }
            array_push($args, $eventId);
        }
        if (!IsNullOrEmpty($userEmailId)) {
            if ($first) {
                $sql .= " WHERE user_email_id = ? ";
                $first = false;
            } else {
                $sql .= " AND user_email_id = ? ";
            }
            array_push($args, $userEmailId);
        }
        if (!IsNullOrEmpty($eventName)) {
            if ($first) {
                $sql .= " WHERE event_name = ? ";
                $first = false;
            } else {
                $sql .= " AND event_name = ? ";
            }
            array_push($args, $eventName);
        }
        if (!IsNullOrEmpty($eventLocation)) {
            if ($first) {
                $sql .= " WHERE event_location = ? ";
                $first = false;
            } else {
                $sql .= " AND event_location = ? ";
            }
            array_push($args, $eventLocation);
        }
        if (!IsNullOrEmpty($eventDescription)) {
            if ($first) {
                $sql .= " WHERE event_description = ? ";
                $first = false;
            } else {
                $sql .= " AND event_description = ? ";
            }
            array_push($args, $eventDescription);
        }
        if (!IsNullOrEmpty($eventStartdate)) {
            if ($first) {
                $sql .= " WHERE event_startdate = ? ";
                $first = false;
            } else {
                $sql .= " AND event_startdate = ? ";
            }
            array_push($args, $eventStartdate);
        }
        if (!IsNullOrEmpty($eventTime)) {
            if ($first) {
                $sql .= " WHERE event_time = ? ";
                $first = false;
            } else {
                $sql .= " AND event_time = ? ";
            }
            array_push($args, $eventTime);
        }
        if (!IsNullOrEmpty($current)) {
            if ($first) {
                $sql .= " WHERE event_startdate >= CURDATE()  ";
                $first = false;
            } else {
                $sql .= " AND event_startdate >= CURDATE()  ";
            }
        }

        $sql .= " ORDER BY event_startdate ";

        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] =  $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['event_planner'][] = $row1;
        }
    } elseif ($action == "getEventsByEmail") {

        $email_addr=$decoded_params['useremailid'];
        $sql= "SELECT * FROM event_planner WHERE user_email_id='$email_addr' ORDER BY event_startdate";

          try {
              $result=$conn->query($sql);
              $json["result"] = $result->fetchAll(PDO::FETCH_ASSOC);
          } catch (Exception $e) {
              $json['Exception'] = $e->getMessage();
          }
    }else {
        $json['Exeption'] = "Unrecognized Action ";
    }
}


else {
    $json['Exeption'] = "Invalid JSON on Inbound Request";
}
echo json_encode($json);
closeConnections();
