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

    $artistId = "";
    if (array_key_exists('artist_id', $decoded_params)) {
        $artistId =  $decoded_params['artist_id'];
    }

    $percentComplete = "";
    if (array_key_exists('percent_complete', $decoded_params)) {
        $percentComplete =  $decoded_params['percent_complete'];
    }

    if ($action == "updateTimelineStage") {
        $args = array();
        if (!IsNullOrEmpty($artistId)) {
            $sql = "UPDATE artist_profile SET status = ? WHERE artist_profile_id = ?; ";
            array_push($args, $percentComplete);
            array_push($args, $artistId);
            $json['SQL'] = $sql;
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
        } else {
            $json['Exception'] = "artist_id is a required field";
        }
    } else {
        $json['Exception'] = "Unrecognized Action ";
    }
} else {
    $json['Exception'] = "Invalid JSON on Inbound Request";
}
echo json_encode($json);
closeConnections();
