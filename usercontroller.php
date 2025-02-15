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


if (isValidJSON($json_params)){
 //load in all the potential parameters.  These should match the database columns for the objects. 
  $conn = getDbConnection();  $decoded_params = json_decode($json_params, TRUE);
  $action = $decoded_params['action'];
  $json['action'] = $action;
  // uncomment the following line if you want to turn PHP error reporting on for debug - note, this will break the JSON response
  //ini_set('display_errors', 1); error_reporting(-1);
$userId = "";
if (array_key_exists('userid', $decoded_params)){
  $userId =  $decoded_params['userid'];
}
$userFirstName = "";
if (array_key_exists('userfirstname', $decoded_params)){
  $userFirstName =  $decoded_params['userfirstname'];
}
$userLastName = "";
if (array_key_exists('userlastname', $decoded_params)){
  $userLastName =  $decoded_params['userlastname'];
}
$userEmailAddress = "";
if (array_key_exists('useremailaddress', $decoded_params)){
  $userEmailAddress =  $decoded_params['useremailaddress'];
}
$userPassword = "";
if (array_key_exists('userpassword', $decoded_params)){
  $userPassword =  $decoded_params['userpassword'];
}
$userOneTimePassword = "";
if (array_key_exists('useronetimepassword', $decoded_params)){
  $userOneTimePassword =  $decoded_params['useronetimepassword'];
}
$userType = "";
if (array_key_exists('usertype', $decoded_params)){
  $userType =  $decoded_params['usertype'];
}
if ($action == "addOrEditUserProfile"){
$args = array();
if (IsNullOrEmpty($userId)){
 $sql = "INSERT INTO user_profile (user_id,user_first_name,user_last_name,user_email_address,user_password,user_one_time_password,user_type) VALUES ( ?,?,?,?,?,?,?);";
array_push($args, $userId);
array_push($args, $userFirstName);
array_push($args, $userLastName);
array_push($args, $userEmailAddress);
array_push($args, md5($userPassword));
array_push($args, $userOneTimePassword);
array_push($args, $userType);
try{
$statement = $conn->prepare($sql);
$statement->execute($args);
$last_id = $conn->lastInsertId();
$json['Record Id'] = $last_id;
$json['Status'] = "SUCCESS - Inserted Id $last_id";
}catch (Exception $e) { 
    $json['Exception'] =  $e->getMessage();
}
}else{
$sql = "UPDATE user_profile SET user_first_name = ?,user_last_name = ?,user_email_address = ?,user_password = ?,user_one_time_password = ?,user_type = ? WHERE user_id = ?; ";
array_push($args, $userFirstName);
array_push($args, $userLastName);
array_push($args, $userEmailAddress);
array_push($args, md5($userPassword));
array_push($args, $userOneTimePassword);
array_push($args, $userType);
array_push($args, $userId);
try{
$statement = $conn->prepare($sql);
$statement->execute($args);
$count = $statement->rowCount();
if ($count > 0){
$json['Status'] = "SUCCESS - Updated $count Rows";
} else {
$json['Status'] = "ERROR - Updated 0 Rows - Check for Valid Ids ";
}
}catch (Exception $e) { 
    $json['Exception'] =  $e->getMessage();
}
$json['Action'] = $action;
}
} else if ($action == "deleteUserProfile"){
$sql = "DELETE FROM user_profile WHERE user_id = ?";
$args = array();
array_push($args, $userId);
if (!IsNullOrEmpty($userId)){
try{
  $statement = $conn->prepare($sql);
  $statement->execute($args);
$count = $statement->rowCount();
if ($count > 0){
$json['Status'] = "SUCCESS - Deleted $count Rows";
} else {
$json['Status'] = "ERROR - Deleted 0 Rows - Check for Valid Ids ";
}
}catch (Exception $e) { 
    $json['Exception'] =  $e->getMessage();
}
} else {
$json['Status'] = "ERROR - Id is required";
}
$json['Action'] = $action;
} else if ($action == "getUserProfile"){
    $args = array();
    $sql = "SELECT * FROM user_profile";
 $first = true;
if (!IsNullOrEmpty($userId)){
      if ($first) {
        $sql .= " WHERE user_id = ? ";
        $first = false;
      }else{
        $sql .= " AND user_id = ? ";
      }
      array_push ($args, $userId);
    }
if (!IsNullOrEmpty($userFirstName)){
      if ($first) {
        $sql .= " WHERE user_first_name = ? ";
        $first = false;
      }else{
        $sql .= " AND user_first_name = ? ";
      }
      array_push ($args, $userFirstName);
    }
if (!IsNullOrEmpty($userLastName)){
      if ($first) {
        $sql .= " WHERE user_last_name = ? ";
        $first = false;
      }else{
        $sql .= " AND user_last_name = ? ";
      }
      array_push ($args, $userLastName);
    }
if (!IsNullOrEmpty($userEmailAddress)){
      if ($first) {
        $sql .= " WHERE user_email_address = ? ";
        $first = false;
      }else{
        $sql .= " AND user_email_address = ? ";
      }
      array_push ($args, $userEmailAddress);
    }
if (!IsNullOrEmpty($userPassword)){
      if ($first) {
        $sql .= " WHERE user_password = ? ";
        $first = false;
      }else{
        $sql .= " AND user_password = ? ";
      }
      array_push ($args, $userPassword);
    }
if (!IsNullOrEmpty($userOneTimePassword)){
      if ($first) {
        $sql .= " WHERE user_one_time_password = ? ";
        $first = false;
      }else{
        $sql .= " AND user_one_time_password = ? ";
      }
      array_push ($args, $userOneTimePassword);
    }
if (!IsNullOrEmpty($userType)){
      if ($first) {
        $sql .= " WHERE user_type = ? ";
        $first = false;
      }else{
        $sql .= " AND user_type = ? ";
      }
      array_push ($args, $userType);
    }
    $json['SQL'] = $sql; 
    try{
      $statement = $conn->prepare($sql);
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $statement->execute($args);
      $result = $statement->fetchAll();
    }catch (Exception $e) { 
      $json['Exception'] =  $e->getMessage();
    }
    foreach($result as $row1 ) {
        $json['user_profile'][] = $row1;
    }
} 
// My code
else if ($action == "updatePastProfile"){
  $args1 = array();
  $sql1 = "SELECT profile_name from artist_profile WHERE artist_email_address = ?; ";
  array_push($args1, $userEmailAddress);
  try{
    $statement1 = $conn->prepare($sql1);
    $statement1->execute($args1);
    $count1 = $statement1->rowCount();
    if ($count1 == 1){
      $past_profile = $statement1->fetchColumn();
      $args2 = array();
      $sql2 = "UPDATE artist_profile SET past_profile_name = ?, profile_name = ? WHERE artist_email_address = ?; ";
      array_push($args2, $past_profile);
      array_push($args2, $userEmailAddress);
      array_push($args2, $userEmailAddress);
      try{
        $statement2 = $conn->prepare($sql2);
        $statement2->execute($args2);
        $count2 = $statement2->rowCount();
        if ($count2 > 0){
          $json['Status'] = "SUCCESS - Updated $count2 Rows";
        } else {
          $json['Status'] = "ERROR - Updated 0 Rows - Check for Valid Ids ";
        }
      }catch (Exception $e) { 
          $json['Exception'] =  $e->getMessage();
      }
    }
    }catch (Exception $e) { 
        $json['Exception'] =  $e->getMessage();
    }
  
  $json['Action'] = $action;
  }
// My code ends
 else { 
    $json['Exeption'] = "Unrecognized Action ";
} 
} 
else{
  
  $json['Exeption'] = "Invalid JSON on Inbound Request";
} 
echo json_encode($json);
closeConnections(); 
?>
