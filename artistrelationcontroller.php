<?php

//testing



require 'utils.php';
require 'connect.php';
require 'util.php';
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
    //load in all the potential parameters.  These should match the database columns for the objects.
    $conn = getDbConnection();
    $decoded_params = json_decode($json_params, true);
    $action = $decoded_params['action'];

    // Newcontroller method 1
    if ($action == 'getFullProfile') {
        require_once "query.php";
        $profileModel = new Model();
        $res = array();
        $artist_profile_id = $decoded_params['artist_profile_id'];
        // $artist_profile_id=216;
        $relations = $profileModel->linealRelations($artist_profile_id);
        $added_by_relations = $profileModel->reverselinealRelations($artist_profile_id);
        $education = $profileModel->education($artist_profile_id);
        $profileDetails = $profileModel->profileDetails($artist_profile_id);
        $res["relations"] = $relations;
        $res["added_by_relations"] = $added_by_relations;
        $res["education"] = $education;
        $res["profileDetails"] = $profileDetails;
        echo json_encode($res);
        exit;
    }
    // Newcontroller method 2
    else if($action == 'getProfiles') {
        require_once "query.php";
        $json = array();
        $firstName = $decoded_params['artistfirstname'];
        $lastName = $decoded_params['artistlastname'];
        
        // My code
        if ($decoded_params['artistemailaddress'] == '' || $decoded_params['artistemailaddress']==='dummyhiddenemail') {
            $str = rand();
            $decoded_params['artistemailaddress'] = 'dummyemail@'. md5($str) . sha1($str);
        }
        // My code ends
        $emailaddress = $decoded_params['artistemailaddress'];
        $profileModel= new Model();
        $route = $decoded_params['route'];
        if ($route==="none") {
            $child_artist_id = $profileModel->profile($decoded_params, 'insert');
            $json["child_artist"] = $profileModel->getProfiles($firstName, $lastName, $emailaddress)[0];
        }
        else {
            $result1=$profileModel->getProfiles($firstName, $lastName, $emailaddress);
            $result2=$profileModel->getProfiles($firstName, $lastName, '-noemail-');
            // if (sizeof($result1)==0 && sizeof($result2)==0) {
            if (sizeof($result1)==0 && sizeof($result2)==0 && isset($decoded_params['is_user_artist'])) {
                // Add code for numm email
                $child_artist_id=$profileModel->profile($decoded_params, 'insert');
                $json["child_artist"]=$profileModel->getProfiles($firstName, $lastName, $emailaddress)[0];
            }
            elseif (sizeof($result1)==1) {
                //$profileModel->profile($decoded_params,'update');
                $json["child_artist"]=$result1[0];
            }
            elseif (sizeof($result1)==0 && sizeof($result2)>0) {
                $json['similar_profiles']=$result2;
                // $json['similar_profiles']['emailaddress']=$emailaddress;
            }
        }
        $parent_firstName = $decoded_params['p_artist_fname'];
        $parent_lastName = $decoded_params['p_artist_lname'];
        $parent_emailaddress = $decoded_params['p_artist_email'];
        $result3=$profileModel->getProfiles($parent_firstName, $parent_lastName, $parent_emailaddress);
        if(!empty($result3)){
            $json["parent_artist"]=$result3[0];
        }
        echo json_encode($json);
        exit;
    }
    // Existing Methods
    else{
        $json['action'] = $action;
        // uncomment the following line if you want to turn PHP error reporting on for debug - note, this will break the JSON response
        //ini_set('display_errors', 1); error_reporting(-1);

        $relationId = "";
        if (array_key_exists('relationid', $decoded_params)) {
            $relationId =  $decoded_params['relationid'];
        }
        $artistProfileId1 = "";
        if (array_key_exists('artist_profile_id_1', $decoded_params)) {
            $artistProfileId1 =  $decoded_params['artist_profile_id_1'];
        }
        $artistProfileId2 = "";
        if (array_key_exists('artist_profile_id_2', $decoded_params)) {
            $artistProfileId2 =  $decoded_params['artist_profile_id_2'];
        }
        $artistName1 = "";
        if (array_key_exists('artistname1', $decoded_params)) {
            $artistName1 =  $decoded_params['artistname1'];
        }
        $artistEmailId1 = "";
        if (array_key_exists('artistemailId1', $decoded_params)) {
            $artistEmailId1 =  $decoded_params['artistemailId1'];
        }
        $artistName2 = "";
        if (array_key_exists('artistname2', $decoded_params)) {
            $artistName2 =  $decoded_params['artistname2'];
        }
        $artistEmailId2 = "";
        if (array_key_exists('artistemailId2', $decoded_params)) {
            $artistEmailId2 =  $decoded_params['artistemailId2'];
        }
        $artistWebsite2 = "";
        if (array_key_exists('artistwebsite2', $decoded_params)) {
            $artistWebsite2 =  $decoded_params['artistwebsite2'];
        }
        $artistRelation = "";
        if (array_key_exists('artistrelation', $decoded_params)) {
            $artistRelation =  $decoded_params['artistrelation'];
        }
        $startDate = "";
        if (array_key_exists('startdate', $decoded_params)) {
            $startDate =  $decoded_params['startdate'];
        }
        $endDate = "";
        if (array_key_exists('enddate', $decoded_params)) {
            $endDate =  $decoded_params['enddate'];
        }
        $durationYears = "";
        if (array_key_exists('durationyears', $decoded_params)) {
            $durationYears =  $decoded_params['durationyears'];
        }
        $durationMonths = "";
        if (array_key_exists('durationmonths', $decoded_params)) {
            $durationMonths =  $decoded_params['durationmonths'];
        }
        $relationIdentifier = "";
        if (array_key_exists('relationidentifier', $decoded_params)) {
            $relationIdentifier =  $decoded_params['relationidentifier'];
        }
        $artistWorks = "";
        if (array_key_exists('works', $decoded_params)) {
            $artistWorks =  $decoded_params['works'];
        }
        $relationGenres = "";
        if (array_key_exists('relation_genres', $decoded_params)) {
            $relationGenres =  $decoded_params['relation_genres'];
        }
        $relationUserGenres = "";
        if (array_key_exists('relation_user_genres', $decoded_params)) {
            $relationUserGenres =  $decoded_params['relation_user_genres'];
        }
        if ($action == "addOrEditArtistRelation") {
            $args = array();
            if (IsNullOrEmpty($relationId)) {
                $sql = "INSERT INTO artist_relation (relation_id,artist_profile_id_1,artist_profile_id_2,artist_name_1,artist_email_id_1,artist_name_2,artist_email_id_2,artist_website_2,artist_relation,start_date,end_date,duration_years,duration_months,relation_identifier,works,relation_genres,relation_user_genres) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
                array_push($args, $relationId);
                array_push($args, $artistProfileId1);
                array_push($args, $artistProfileId2);
                array_push($args, $artistName1);
                array_push($args, $artistEmailId1);
                array_push($args, $artistName2);
                array_push($args, $artistEmailId2);
                array_push($args, $artistWebsite2);
                array_push($args, $artistRelation);
                array_push($args, $startDate);
                array_push($args, $endDate);
                array_push($args, $durationYears);
                array_push($args, $durationMonths);
                array_push($args, $relationIdentifier);
                array_push($args, $artistWorks);
                array_push($args, $relationGenres);
                array_push($args, $relationUserGenres);
                try {
                    $statement = $conn->prepare($sql);
                    $statement->execute($args);
                    $last_id = $conn->lastInsertId();
                    $json['Record Id'] = $last_id;
                    $json['Status'] = "SUCCESS - Inserted Id $last_id";
                }
                catch (Exception $e) {
                    $json['Exception'] =  $e->getMessage();
                }
            }
            else {
                $sql = "UPDATE artist_relation SET artist_profile_id_1 = ?,artist_profile_id_2 = ?,artist_name_1 = ?,artist_email_id_1 = ?,artist_name_2 = ?,artist_email_id_2 = ?,artist_website_2 = ?,artist_relation = ?,start_date = ?,end_date = ?,duration_years = ?,duration_months = ?,relation_identifier = ?, works=?, relation_genres=?, relation_user_genres=? WHERE relation_id = ?; ";
                array_push($args, $artistProfileId1);
                array_push($args, $artistProfileId2);
                array_push($args, $artistName1);
                array_push($args, $artistEmailId1);
                array_push($args, $artistName2);
                array_push($args, $artistEmailId2);
                array_push($args, $artistWebsite2);
                array_push($args, $artistRelation);
                array_push($args, $startDate);
                array_push($args, $endDate);
                array_push($args, $durationYears);
                array_push($args, $durationMonths);
                array_push($args, $relationIdentifier);
                array_push($args, $artistWorks);
                array_push($args, $relationId);
                array_push($args, $relationGenres);
                array_push($args, $relationUserGenres);
                try {
                    $statement = $conn->prepare($sql);
                    $statement->execute($args);
                    $count = $statement->rowCount();
                    if ($count > 0) {
                        $json['Status'] = "SUCCESS - Updated $count Rows";
                    }
                    else {
                        $json['Status'] = "ERROR - Updated 0 Rows - Check for Valid Ids ";
                    }
                }
                catch (Exception $e) {
                    $json['Exception'] =  $e->getMessage();
                }
                $json['Action'] = $action;
            }
        }else if ($action == "addEditArtistRelationById"){
                if (!isset($_SESSION["user_id"])) {
                    //user not login
                    $json['Exception']=100;
                    echo json_encode($json);
                    closeConnections();
                    exit();
                }
                $id1= $_SESSION["profile_id"];
                $id2=$decoded_params["artist_profile_id"];

                if(is_null($id1)){
                    $json['Exception']=200;
                    echo json_encode($json);
                    closeConnections();
                    exit();
                }
                try {
                    $sql="DELETE FROM artist_relation WHERE artist_profile_id_1=$id1 and artist_profile_id_2=$id2";
                    $conn->query($sql);
                } catch (Exception $e){
                    $json['Exception'] = $e->getMessage();
                    echo json_encode($json);
                    closeConnections();
                    exit();
                }
                try {
                    $sql="SELECT artist_profile_id, artist_first_name,artist_last_name,artist_email_address,artist_website FROM artist_profile WHERE artist_profile_id=$id1 
                            UNION  
                            SELECT artist_profile_id, artist_first_name,artist_last_name,artist_email_address,artist_website FROM artist_profile WHERE  artist_profile_id=$id2";
                    $artists=$conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                    if (count($artists)!=2){
                        $json['Exception']=200;
                        echo json_encode($json);
                        closeConnections();
                        exit();
                    }
                    $artist_name_1=$artists[0] ["artist_first_name"]."-".$artists[0] ["artist_last_name"];
                    $artist_name_2=$artists[1]['artist_first_name'].'-'.$artists[1]['artist_last_name'];
                    $artist_profile_id_1=$artists[0]['artist_profile_id'];
                    $artist_profile_id_2=$artists[1]['artist_profile_id'];
                    $artist_email_id_1=$artists[0]['artist_email_address'];
                    $artist_email_id_2=$artists[1]['artist_email_address'];
                    $artist_website_1=$artists[0]['artist_website'];
                    $artist_website_2=$artists[1]['artist_website'];
                    foreach ($decoded_params['relations'] as $value) {
                        $relation=$value["artist_relation"];
                        $works=$value["works"];
                        $sql="INSERT INTO artist_relation
                                  (artist_profile_id_1, artist_profile_id_2,artist_name_1,artist_email_id_1, artist_name_2, artist_email_id_2,artist_website_2,artist_relation,works, relation_genres, relation_user_genres)
                                  VALUES
                                    ($artist_profile_id_1,$artist_profile_id_2,'$artist_name_1','$artist_email_id_1','$artist_name_2','$artist_email_id_2','$artist_website_2','$relation','$works',$relationGenres, $relationUserGenres)
                                  ON DUPLICATE KEY UPDATE
                                    artist_name_1 = '$artist_name_1',
                                    artist_email_id_1 = '$artist_email_id_1',
                                    artist_name_2 = '$artist_name_2',
                                    artist_email_id_2 = '$artist_email_id_2',
                                    artist_website_2 = '$artist_website_2',
                                    works = '$works',
                                    relation_genres = '$relationGenres',
                                    relation_user_genres = '$relationUserGenres'
                                    ";
                        $json["sql"]=$sql;
                        $json["result"]=$conn->query($sql);
                    }

                } catch (Exception $e){
                    $json['Exception'] = $e->getMessage();
                    echo json_encode($json);
                    closeConnections();
                    exit();
                }
        }else if($action == "getLoginRelatedArtistWithId"){

            if (!isset($_SESSION["user_id"])) {
                //user not login
                $json['Exception']=100;
                echo json_encode($json);
                closeConnections();
                exit();
            }
            $id1= $_SESSION["profile_id"];
            $id2=$decoded_params["artist_profile_id"];
            //user add relationship himself
            if($id1==$id2){
                $json['Exception']=300;
                echo json_encode($json);
                closeConnections();
                exit();
            }
            if(is_null($id1)){
                $json['Exception']=200;
                echo json_encode($json);
                closeConnections();
                exit();
            }

            $sql="SELECT * FROM artist_relation WHERE artist_profile_id_1=$id1 and artist_profile_id_2=$id2";
            try {
                $json["sql"]=$sql;
                $json["result"]=$conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }catch (Exception $e ){
                $json['Exception'] = $e->getMessage();
            }
        }
        elseif ($action == "addOrEditArtistRelationWithOtherFields") {
            $args = array();
            $sql = "SET   @artist_profile_id_1=?,
                      @artist_profile_id_2=?,
                      @artist_name_1 = ?,
                      @artist_email_id_1 = ?,
                      @artist_name_2 = ?,
                      @artist_email_id_2 = ?,
                      @artist_website_2 = ?,
                      @artist_relation = ?,
                      @works = ?,
                      @relation_genres = ?,
                      @relation_user_genres= ?;
                  INSERT INTO artist_relation
                      (artist_profile_id_1, artist_profile_id_2,artist_name_1,artist_email_id_1, artist_name_2, artist_email_id_2,artist_website_2,artist_relation,works, relation_genres, relation_user_genres)
                  VALUES
                      (@artist_profile_id_1, @artist_profile_id_2,@artist_name_1,@artist_email_id_1, @artist_name_2, @artist_email_id_2,@artist_website_2,@artist_relation,@works, @relation_genres, @relation_user_genres)
                  ON DUPLICATE KEY UPDATE
                    artist_name_1 = @artist_name_1,
                    artist_email_id_1 = @artist_email_id_1,
                    artist_name_2 = @artist_name_2,
                    artist_email_id_2 = @artist_email_id_2,
                    artist_website_2 = @artist_website_2,
                    works = @works,
                    relation_genres = @relation_genres,
                    relation_user_genres = @relation_user_genres;";
            array_push($args, $artistProfileId1);
            array_push($args, $artistProfileId2);
            array_push($args, $artistName1);
            array_push($args, $artistEmailId1);
            array_push($args, $artistName2);
            array_push($args, $artistEmailId2);
            array_push($args, $artistWebsite2);
            array_push($args, $artistRelation);
            array_push($args, $artistWorks);
            array_push($args, $relationGenres);
            array_push($args, $relationUserGenres);
            try {
                $statement = $conn->prepare($sql);
                $statement->execute($args);
                $last_id = $conn->lastInsertId();
                $json['Record Id'] = $last_id;
                $json['Status'] = "SUCCESS - Inserted Id $last_id";
            }
            catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
            $json['Action'] = $action;
        }
        elseif ($action == "deleteArtistRelation") {
            $sql = "DELETE FROM artist_relation WHERE relation_id = ?";
            $args = array();
            array_push($args, $relationId);
            if (!IsNullOrEmpty($relationId)) {
                try {
                    $statement = $conn->prepare($sql);
                    $statement->execute($args);
                    $count = $statement->rowCount();
                    if ($count > 0) {
                        $json['Status'] = "SUCCESS - Deleted $count Rows";
                    }
                    else {
                        $json['Status'] = "ERROR - Deleted 0 Rows - Check for Valid Ids ";
                    }
                }
                catch (Exception $e) {
                    $json['Exception'] =  $e->getMessage();
                }
            }
            else {
                $json['Status'] = "ERROR - Id is required";
            }
            $json['Action'] = $action;
        }
        elseif ($action == "deleteArtistRelationWithOtherIdentifiers") {
            $sql = "DELETE FROM artist_relation";
            $args = array();
            $first = true;
            if (!IsNullOrEmpty($artistProfileId1)) {
                if ($first) {
                    $sql .= " WHERE artist_profile_id_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_1 = ? ";
                }
                array_push($args, $artistProfileId1);
            }
            if (!IsNullOrEmpty($artistProfileId2)) {
                if ($first) {
                    $sql .= " WHERE artist_profile_id_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_2 = ? ";
                }
                array_push($args, $artistProfileId2);
            }
            if (!IsNullOrEmpty($artistRelation)) {
                if ($first) {
                    $sql .= " WHERE artist_relation = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_relation = ? ";
                }
                array_push($args, $artistRelation);
            }
            if (!IsNullOrEmpty($artistProfileId1) || !IsNullOrEmpty($artistProfileId2)) {
                try {
                    $statement = $conn->prepare($sql);
                    $statement->execute($args);
                    $count = $statement->rowCount();
                    if ($count > 0) {
                        $json['Status'] = "SUCCESS - Deleted $count Rows";
                    }
                    else {
                        $json['Status'] = "ERROR - Deleted 0 Rows - Check for Valid Ids ";
                    }
                }
                catch (Exception $e) {
                    $json['Exception'] =  $e->getMessage();
                }
            }
            else {
                $json['Status'] = "ERROR - pID1 or pID2 is required";
            }
            $json['Action'] = $action;
        }
        elseif ($action == "getArtistRelation") {
            $args = array();
            $sql = "SELECT * FROM artist_relation";
            $first = true;
            if (!IsNullOrEmpty($relationId)) {
                if ($first) {
                    $sql .= " WHERE relation_id = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND relation_id = ? ";
                }
                array_push($args, $relationId);
            }
            if (!IsNullOrEmpty($artistProfileId1)) {
                if ($first) {
                    $sql .= " WHERE artist_profile_id_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_1 = ? ";
                }
                array_push($args, $artistProfileId1);
            }
            if (!IsNullOrEmpty($artistProfileId2)) {
                if ($first) {
                    $sql .= " WHERE artist_profile_id_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_2 = ? ";
                }
                array_push($args, $artistProfileId2);
            }
            if (!IsNullOrEmpty($artistName1)) {
                if ($first) {
                    $sql .= " WHERE artist_name_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_name_1 = ? ";
                }
                array_push($args, $artistName1);
            }
            if (!IsNullOrEmpty($artistEmailId1)) {
                if ($first) {
                    $sql .= " WHERE artist_email_id_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_email_id_1 = ? ";
                }
                array_push($args, $artistEmailId1);
            }
            if (!IsNullOrEmpty($artistName2)) {
                if ($first) {
                    $sql .= " WHERE artist_name_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_name_2 = ? ";
                }
                array_push($args, $artistName2);
            }
            if (!IsNullOrEmpty($artistEmailId2)) {
                if ($first) {
                    $sql .= " WHERE artist_email_id_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_email_id_2 = ? ";
                }
                array_push($args, $artistEmailId2);
            }
            if (!IsNullOrEmpty($artistWebsite2)) {
                if ($first) {
                    $sql .= " WHERE artist_website_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_website_2 = ? ";
                }
                array_push($args, $artistWebsite2);
            }
            if (!IsNullOrEmpty($artistRelation)) {
                if ($first) {
                    $sql .= " WHERE artist_relation = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_relation = ? ";
                }
                array_push($args, $artistRelation);
            }
            if (!IsNullOrEmpty($startDate)) {
                if ($first) {
                    $sql .= " WHERE start_date = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND start_date = ? ";
                }
                array_push($args, $startDate);
            }
            if (!IsNullOrEmpty($endDate)) {
                if ($first) {
                    $sql .= " WHERE end_date = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND end_date = ? ";
                }
                array_push($args, $endDate);
            }
            if (!IsNullOrEmpty($durationYears)) {
                if ($first) {
                    $sql .= " WHERE duration_years = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND duration_years = ? ";
                }
                array_push($args, $durationYears);
            }
            if (!IsNullOrEmpty($durationMonths)) {
                if ($first) {
                    $sql .= " WHERE duration_months = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND duration_months = ? ";
                }
                array_push($args, $durationMonths);
            }
            if (!IsNullOrEmpty($relationIdentifier)) {
                if ($first) {
                    $sql .= " WHERE relation_identifier = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND relation_identifier = ? ";
                }
                array_push($args, $relationIdentifier);
            }
            if (!IsNullOrEmpty($artistWorks)) {
                if ($first) {
                    $sql .= " WHERE works = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND works = ? ";
                }
                array_push($args, $artistWorks);
            }
            if (!IsNullOrEmpty($relationGenres)) {
                if ($first) {
                    $sql .= " WHERE relation_genres = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND relation_genres = ? ";
                }
                array_push($args, $relationGenres);
            }
            if (!IsNullOrEmpty($relationUserGenres)) {
                if ($first) {
                    $sql .= " WHERE relation_user_genres = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND relation_user_genres = ? ";
                }
                array_push($args, $relationUserGenres);
            }
            $json['SQL'] = $sql;
            try {
                $statement = $conn->prepare($sql);
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                $statement->execute($args);
                $result = $statement->fetchAll();
            }
            catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
            foreach ($result as $row1) {
                $json['artist_relation'][] = $row1;
            }
        }
        elseif ($action == "getArtistWithGroupedRelations") {
            $args = array();
            $sql = "SELECT artist_profile_id_1,artist_profile_id_2,artist_name_1,artist_email_id_1,artist_name_2,artist_email_id_2,artist_website_2, GROUP_CONCAT(artist_relation)
                as artist_relation FROM artist_relation
                GROUP BY artist_profile_id_1,artist_profile_id_2";
            $first = true;
            if (!IsNullOrEmpty($artistProfileId1)) {
                if ($first) {
                    $sql .= " HAVING artist_profile_id_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_1 = ? ";
                }
                array_push($args, $artistProfileId1);
            }
            if (!IsNullOrEmpty($artistProfileId2)) {
                if ($first) {
                    $sql .= " HAVING artist_profile_id_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_profile_id_2 = ? ";
                }
                array_push($args, $artistProfileId2);
            }
            if (!IsNullOrEmpty($artistName1)) {
                if ($first) {
                    $sql .= " HAVING artist_name_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_name_1 = ? ";
                }
                array_push($args, $artistName1);
            }
            if (!IsNullOrEmpty($artistEmailId1)) {
                if ($first) {
                    $sql .= " HAVING artist_email_id_1 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_email_id_1 = ? ";
                }
                array_push($args, $artistEmailId1);
            }
            if (!IsNullOrEmpty($artistName2)) {
                if ($first) {
                    $sql .= " HAVING artist_name_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_name_2 = ? ";
                }
                array_push($args, $artistName2);
            }
            if (!IsNullOrEmpty($artistEmailId2)) {
                if ($first) {
                    $sql .= " HAVING artist_email_id_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_email_id_2 = ? ";
                }
                array_push($args, $artistEmailId2);
            }
            if (!IsNullOrEmpty($artistWebsite2)) {
                if ($first) {
                    $sql .= " HAVING artist_website_2 = ? ";
                    $first = false;
                }
                else {
                    $sql .= " AND artist_website_2 = ? ";
                }
                array_push($args, $artistWebsite2);
            }
            $json['SQL'] = $sql;
            try {
                $statement = $conn->prepare($sql);
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                $statement->execute($args);
                $result = $statement->fetchAll();
            }
            catch (Exception $e) {
                $json['Exception'] =  $e->getMessage();
            }
            foreach ($result as $row1) {
                $json['artist_relation'][] = $row1;
            }
        }
        else {
            $json['Exception'] = "Unrecognized Action";
        }
    }
}
else {
    $json['Exception'] = "Invalid JSON on Inbound Request";
}
echo json_encode($json);
closeConnections();
