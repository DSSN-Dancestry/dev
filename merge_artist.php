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

    $artistProfileIds = array();
    if (array_key_exists('artist_profile_ids', $decoded_params)) {
        $artistProfileIds = $decoded_params['artist_profile_ids'];
    }
    $masterId = "";
    if (array_key_exists('master_id', $decoded_params)) {
        $masterId = $decoded_params['master_id'];
    }
    if($action == "mergeArtistDetails"){
        try{
            $args = array();
            $artistName = "";
            $artistEmail = "";
            $tableArr = array('artist_profile', 'artist_genres', 'artist_education', 'artist_works', 'artist_social');
            if (count($artistProfileIds) > 0 && !IsNullOrEmpty($artistProfileIds[0]) && !IsNullOrEmpty($masterId)) {
                foreach ($tableArr as $tableName) {
                    $sqlColumns = "SHOW COLUMNS FROM ".$tableName;
                    $statement = $conn->prepare($sqlColumns);
                    $statement->setFetchMode(PDO::FETCH_ASSOC);
                    if($statement->execute()){
                        //$statement->store_result();
                        $result1 = $statement->fetchAll();

                        $stmt = $conn->prepare("SELECT * FROM ".$tableName." WHERE artist_profile_id IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")");
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        if($stmt->execute()){
                            //$stmt->store_result();
                            $result2 = $stmt->fetchAll();

                            $mainStmt = $conn->prepare("SELECT * FROM ".$tableName." WHERE artist_profile_id = " . $masterId );
                            $mainStmt->setFetchMode(PDO::FETCH_ASSOC);
                            
                            if($mainStmt->execute()){
                                $refResult = $mainStmt->fetchAll();

                                if(strcmp($tableName, "artist_education") == 0 || strcmp($tableName, "artist_social") == 0){
                                    foreach ($result2 as $row)
                                    {
                                        $row["artist_profile_id"] = $masterId;
                                        
                                        $insertSql = "INSERT into ".$tableName." ";
                                        $cols = "(";
                                        $values = "(";
                                        foreach($row as $key => $value){
                                            $cols .= $key.", ";
                                            $values .= "'" .$value. "', ";
                                        }
                                        $cols = substr($cols, 0, -2);
                                        $values = substr($values, 0, -2);
                                        $cols .= ")";
                                        $values .= ")";

                                        $insertSql .= $cols. "VALUES " .$values;
                                        $insertSql = $conn->prepare($insertSql);
                                        $insertSql->setFetchMode(PDO::FETCH_ASSOC);
                                        $insertSql->execute();
                                    }

                                    $delstmt = "DELETE FROM ".$tableName;
                                    $delstmt .= " WHERE artist_profile_id IN (" . implode(',', array_map('intval', $artistProfileIds)) . ", " .$masterId. ")";
                                    if(strcmp($tableName, "artist_education") == 0){
                                        $delstmt .= " AND institution_name = '' AND major = '' AND degree = '' ";
                                    }
                                    $delstmt = $conn->prepare($delstmt);
                                    $delstmt->setFetchMode(PDO::FETCH_ASSOC);
                                    $delstmt->execute();
                                }
                                else{
                                    $output_values = array();
                                    $output = array();

                                    if(strcmp($tableName, "artist_profile") == 0){
                                        $artistName = $refResult[0]["artist_first_name"]."-".$refResult[0]["artist_last_name"];
                                        $artistEmail = $refResult[0]["artist_email_address"];
                                    }
                                    foreach ($result1 as $rowCol){
                                        $array = array();
                                        $output[] = $rowCol['Field'];
                                        foreach ($result2 as $row)
                                        {
                                            $array[] = $row[$rowCol['Field']];
                                        }
                                        foreach ($array as $vals){
                                            $output_values[0][$rowCol['Field']] = $refResult[0][$rowCol['Field']];
                                            if(!(is_null($vals) || trim($vals) == '') && (is_null($refResult[0][$rowCol['Field']]) || trim($refResult[0][$rowCol['Field']]) == "")){
                                                $output_values[0][$rowCol['Field']] = $vals;
                                                break;
                                            }
                                        }
                                    }
                                    if(count($output_values) > 0){
                                        $updateSql = "UPDATE ".$tableName." SET ";
                                        for ($i = 0; $i < count($output); $i++){
                                            if(!($output[$i] == "profile_name" || $output[$i] == "artist_profile_id")){
                                                $updateSql .= $output[$i]. " = '" .$output_values[0][$output[$i]]. "'";
                                                if($i < count($output)-1){
                                                    $updateSql .= ", ";
                                                }
                                            }
                                        }
                                        
                                        $updateSql = str_replace("last_update_date = ''", "last_update_date = '".date('Y-m-d H:i:s')."'", $updateSql);
                                        $updateSql = str_replace("completed_date = ''", "completed_date = '".date('Y-m-d H:i:s')."'", $updateSql);

                                        $updateSql .= " WHERE artist_profile_id = ".$masterId;

                                        $updstmt = $conn->prepare($updateSql);
                                        $updstmt->setFetchMode(PDO::FETCH_ASSOC);
                                        if($updstmt->execute()){
                                            if(strcmp($tableName, "artist_profile") == 0){
                                                $delstmt = "UPDATE artist_profile SET is_deleted = 'true' WHERE artist_profile_id IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")";
                                                $delstmt = $conn->prepare($delstmt);
                                                $delstmt->setFetchMode(PDO::FETCH_ASSOC);
                                                $delstmt->execute();
                                            }else{
                                                $delstmt = "DELETE FROM ".$tableName;
                                                $delstmt .= " WHERE artist_profile_id IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")";
                                                $delstmt = $conn->prepare($delstmt);
                                                $delstmt->setFetchMode(PDO::FETCH_ASSOC);
                                                $delstmt->execute();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $sqlMasterArtist = $conn->prepare("SELECT * FROM artist_relation WHERE artist_profile_id_1 = ". $masterId );
            $sqlMasterArtist->setFetchMode(PDO::FETCH_ASSOC);
            if($sqlMasterArtist->execute()){
                $artistId = array();
                $artistRel = array();

                $masterResult = $sqlMasterArtist->fetchAll();
                foreach ($masterResult as $row)
                {
                    $artistRel[] = $row["artist_relation"];
                    $artistId[] = $row["artist_profile_id_2"];
                }
                $sqlArtist = $conn->prepare("SELECT * FROM artist_relation WHERE artist_profile_id_1 IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")");
                //$sqlArtist = $conn->prepare("SELECT * FROM artist_relation WHERE artist_profile_id_1 = 1132");
                $sqlArtist->setFetchMode(PDO::FETCH_ASSOC);
                if($sqlArtist->execute()){ 
                    $artistResult = $sqlArtist->fetchAll();
                    $rowToAdd = array();
                    foreach ($artistResult as $row)
                    {
                        if (!in_array($row["artist_profile_id_2"], $artistId) && !in_array($row["artist_profile_id_2"], $artistProfileIds)){
                            unset($row["relation_id"]);
                            $row["artist_name_1"] = $artistName;
                            $row["artist_email_id_1"] = $artistEmail;
                            $row["artist_profile_id_1"] = $masterId;
                            $rowToAdd[] = $row;
                        }
                        if(in_array($row["artist_profile_id_2"], $artistId) && !in_array($row["artist_relation"], $artistRel) && !in_array($row["artist_profile_id_2"], $artistProfileIds)){
                            unset($row["relation_id"]);
                            $row["artist_name_1"] = $artistName;
                            $row["artist_email_id_1"] = $artistEmail;
                            $row["artist_profile_id_1"] = $masterId;
                            $rowToAdd[] = $row;
                        }
                    }
                    for($i=0; $i<count($rowToAdd); $i++) {
                        $insertSql = "INSERT into artist_relation ";
                        $cols = "(";
                        $values = "(";
                        foreach($rowToAdd[$i] as $key => $value){
                            $cols .= $key.", ";
                            $values .= "'" .$value. "', ";
                        }
                        $cols = substr($cols, 0, -2);
                        $values = substr($values, 0, -2);
                        $cols .= ")";
                        $values .= ")";

                        $insertSql .= $cols. "VALUES " .$values;
                        $insertSql = $conn->prepare($insertSql);
                        $insertSql->setFetchMode(PDO::FETCH_ASSOC);
                        $insertSql->execute();               
                    }

                    $updateQuery = "UPDATE IGNORE artist_relation SET artist_profile_id_2 = ".$masterId.", artist_name_2 = '".$artistName."', artist_email_id_2 = '".$artistEmail."'";
                    $updateQuery .= " WHERE artist_profile_id_2 IN (". implode(',', array_map('intval', $artistProfileIds)) .")";
                    $updateQuery = $conn->prepare($updateQuery);
                    $updateQuery->setFetchMode(PDO::FETCH_ASSOC);
                    $updateQuery->execute();

                    $delstmt = "DELETE FROM artist_relation";
                    $delstmt .= " WHERE artist_profile_id_1 IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")";
                    $delstmt .= " OR artist_profile_id_2 IN (" . implode(',', array_map('intval', $artistProfileIds)) . ")";
                    $delstmt = $conn->prepare($delstmt);
                    $delstmt->setFetchMode(PDO::FETCH_ASSOC);
                    $delstmt->execute();
                }
            }
            $json['result'] = "Merged Successfully!!";
        }catch (Exception $e){
            $json['Exception'] = $e->getMessage();
        }    
    }
    echo json_encode($json);
    closeConnections();

}
?>