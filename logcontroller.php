<?php

require 'utils.php';
require 'connect.php';
include 'util.php';
my_session_start();


// the response will be a JSON object
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = array();

// pull the input, which should be in the form of a JSON object
$json_params = file_get_contents('php://input');

// check to make sure that the JSON is in a valid format
if (isValidJSON($json_params)) {
    $conn = getDbConnection();
    $decoded_params = json_decode($json_params, true);
    $action = $decoded_params['action'];
    $json['action'] = $action;

    $data = "";
    if (array_key_exists('data', $decoded_params)) {
        $data = $decoded_params['data'];
    }

    if ($action == "getUserLogs") {
        $args = array();
        $sql = "SELECT * FROM user_logs";
        $json['SQL'] = $sql;
        try{
            $statement = $conn->prepare($sql);
            $statement->execute($args);
            $result = $statement->fetchAll();
        }catch (Exception $e){
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row) {
            $row['artist_name'] = getUserName($row['artist_name']);
            $row['log_details'] = getArtistName($row['log_details']);
            $json['user_logs'][] = $row;
        }
    }

    if($action == "addUserLogs"){
        $args = array();
        $sql = "INSERT INTO user_logs(artist_name, operation_name, log_details, date_time) VALUES (?, ?, ?, now())";
        //$outputDets = json_decode($data, true);
        foreach($data as $key => $value){
            array_push($args, $value);
        }
        try{
            $statement = $conn->prepare($sql);
            $statement->execute($args);
            $json['user_logs'][] = "Logs added successfully!";
        }catch (Exception $e){
            $json['Exception'] = $e->getMessage();
        }
    }

    echo json_encode($json);
    closeConnections();
}

function getUserName($userId){
    $userName = "";

    $conn = getDbConnection();
    $sql = "SELECT * FROM user_profile WHERE user_id = " .$userId;
    $statement = $conn->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $userName = $row['user_first_name']."-".$row['user_last_name'];
    }
    return $userName;
}

function getArtistName($artistId){
    $artistName = "";
    if(strpos($artistId, ',') !== false){
        $artistId = substr($artistId, 0, -1);
    }
    $conn = getDbConnection();
    $sql = "SELECT * FROM artist_profile WHERE artist_profile_id IN (".$artistId.")";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
    $maxCount = count($result);
    $i = 1;
    foreach ($result as $row) {
        $artistName .= $row['artist_first_name']."-".$row['artist_last_name'];
        if($i < $maxCount){
            $artistName .= ",";
            $i = $i+1;
        }
    }
    return $artistName;
}
?>