<?php
// $d=new Model();
// require 'connect.php';
class Model{
	
    	// const DB_HOST = 'localhost';
        // const DB_NAME = 'choreographiclineage_db';
        // const DB_USER = 'root';
        // const DB_PASSWORD = '';
        
    	private $pdo = null;
    	

        // Creation of DB Object
        public function __construct() {
                // $conStr = sprintf("mysql:host=%s;dbname=%s;charset=utf8", self::DB_HOST, self::DB_NAME);
                 try {
                    // $this->pdo = new PDO($conStr, self::DB_USER, self::DB_PASSWORD);
                    $this->pdo = getDbConnection();
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                } catch (PDOException $e) {
                    echo $e->getMessage();

                }
            }
        	
        public function getProfiles($firstName,$lastName,$email){
            if($email=="-noemail-"){
                $sql="
                    SELECT *
                    FROM artist_profile
                    WHERE artist_first_name=? and artist_last_name =? 
                    order by
                        CASE is_user_artist
                            when 'artist' then 1
                            when 'other' then 2
                            when '' then 3
                            else 4 
                        END ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->bindParam(1,$firstName,PDO::PARAM_STR,12);
                $stmt->bindParam(2,$lastName,PDO::PARAM_STR,12);
                $stmt->execute();
                $result = $stmt->fetchAll();
                return $result;
            }
            else{   
            $sql="SELECT * FROM artist_profile WHERE artist_first_name=? and artist_last_name =? and artist_email_address=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1,$firstName,PDO::PARAM_STR,12);
            $stmt->bindParam(2,$lastName,PDO::PARAM_STR,12);
            $stmt->bindParam(3,$email,PDO::PARAM_STR,12);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
            }
        }

        public function profile($decoded_params,$action){
            $artistProfileId = "";
            if (array_key_exists('artistprofileid', $decoded_params)){
            $artistProfileId =  $decoded_params['artistprofileid'];
            }
            $isUserArtist = "";
            if (array_key_exists('is_user_artist', $decoded_params)){
            $isUserArtist =  $decoded_params['is_user_artist'];
            }
            $profileName = "";
            if (array_key_exists('profilename', $decoded_params)){
            $profileName =  $decoded_params['profilename'];
            }
            $artistFirstName = "";
            if (array_key_exists('artistfirstname', $decoded_params)){
            $artistFirstName =  $decoded_params['artistfirstname'];
            }
            $artistLastName = "";
            if (array_key_exists('artistlastname', $decoded_params)){
            $artistLastName =  $decoded_params['artistlastname'];
            }
            $artistEmailAddress = "";
            if (array_key_exists('artistemailaddress', $decoded_params)){
            $artistEmailAddress =  $decoded_params['artistemailaddress'];
            }
            $artistLivingStatus = "";
            if (array_key_exists('artistlivingstatus', $decoded_params)){
            $artistLivingStatus =  $decoded_params['artistlivingstatus'];
            }
            $artistDob = "0000-00-00";
            if (array_key_exists('artistdob', $decoded_params)){
            $artistDob =  $decoded_params['artistdob'];
            }
            $artistDod = "0000-00-00";
            if (array_key_exists('artistdod', $decoded_params)){
            $artistDod =  $decoded_params['artistdod'];
            $artistDod = null;
            }
            $artistGenre = "";
            if (array_key_exists('artistgenre', $decoded_params)){
            $artistGenre =  $decoded_params['artistgenre'];
            }
            $artistEthnicity = "";
            if (array_key_exists('artistethnicity', $decoded_params)){
            $artistEthnicity =  $decoded_params['artistethnicity'];
            }
            $artistGender = "";
            if (array_key_exists('artistgender', $decoded_params)){
            $artistGender =  $decoded_params['artistgender'];
            }
            $genderOther = "";
            if (array_key_exists('genderother', $decoded_params)){
            $genderOther =  $decoded_params['genderother'];
            }
            $genreOther = "";
            if (array_key_exists('genreother', $decoded_params)){
            $genreOther =  $decoded_params['genreother'];
            }
            $ethnicityOther = "";
            if (array_key_exists('ethnicityother', $decoded_params)){
            $ethnicityOther =  $decoded_params['ethnicityother'];
            }
            $artistResidenceCity = "";
            if (array_key_exists('artistresidencecity', $decoded_params)){
            $artistResidenceCity =  $decoded_params['artistresidencecity'];
            }
            $artistResidenceState = "";
            if (array_key_exists('artistresidencestate', $decoded_params)){
            $artistResidenceState =  $decoded_params['artistresidencestate'];
            }
            $artistResidenceProvince = "";
            if (array_key_exists('artistresidenceprovince', $decoded_params)){
            $artistResidenceProvince =  $decoded_params['artistresidenceprovince'];
            }
            $artistResidenceCountry = "";
            if (array_key_exists('artistresidencecountry', $decoded_params)){
            $artistResidenceCountry =  $decoded_params['artistresidencecountry'];
            }
            $artistBirthCountry = "";
            if (array_key_exists('artistbirthcountry', $decoded_params)){
            $artistBirthCountry =  $decoded_params['artistbirthcountry'];
            }
            $artistBiography = "";
            if (array_key_exists('artistbiography', $decoded_params)){
            $artistBiography =  $decoded_params['artistbiography'];
            }
            $artistBiographyText = "";
            if (array_key_exists('artistbiographytext', $decoded_params)){
            $artistBiographyText =  $decoded_params['artistbiographytext'];
            }
            $artistPhotoPath = "";
            if (array_key_exists('artistphotopath', $decoded_params)){
            $artistPhotoPath =  $decoded_params['artistphotopath'];
            }
            $artistWebsite = "";
            if (array_key_exists('artistwebsite', $decoded_params)){
            $artistWebsite =  $decoded_params['artistwebsite'];
            }
            $institutionName = "";
            if (array_key_exists('institutionname', $decoded_params)){
            $institutionName =  $decoded_params['institutionname'];
            }
            $artistMajor = "";
            if (array_key_exists('artistmajor', $decoded_params)){
            $artistMajor =  $decoded_params['artistmajor'];
            }
            $artistDegree = "";
            if (array_key_exists('artistdegree', $decoded_params)){
            $artistDegree =  $decoded_params['artistdegree'];
            }
            $newGenre="";
            if (array_key_exists('newgenre', $decoded_params)){
            $newGenre =  $decoded_params['newgenre'];
            }
            $userGenres = "";
            if (array_key_exists('usergenres', $decoded_params)){
                $userGenres =  $decoded_params['usergenres'];
            }
            $pastProfileName = "";
            if (array_key_exists('pastProfileName', $decoded_params)){
                $pastProfileName =  $decoded_params['pastProfileName'];
            }
            $artistYob = null;
            if (array_key_exists('artistYob', $decoded_params)){
                $artistYob =  $decoded_params['artistYob'];
            }
            if($action=='insert'){
                // if($artistEmailAddress == ''){
                //     $artistEmailAddress = 'dummyemail_'. uniqid() . '_' . uniqid();
                // }
                $args = array();
                $sql = "INSERT INTO artist_profile (is_user_artist,profile_name,past_profile_name,artist_first_name,artist_last_name,artist_email_address,artist_living_status,artist_dob,artist_yob,artist_dod,artist_genre,artist_ethnicity,artist_gender,gender_other,genre_other,ethnicity_other,artist_residence_city,artist_residence_state,artist_residence_province,artist_residence_country,artist_birth_country,artist_biography,artist_biography_text,artist_photo_path,artist_website,status,genre, user_genres, last_update_date) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,25,?,?, now());";
                // array_push($args, $artistProfileId);
                array_push($args, $isUserArtist);
                array_push($args, $profileName);
                array_push($args, $pastProfileName);
                array_push($args, $artistFirstName);
                array_push($args, $artistLastName);
                array_push($args, $artistEmailAddress);
                array_push($args, $artistLivingStatus);
                array_push($args, $artistDob);
                array_push($args, $artistYob);
                array_push($args, $artistDod);
                array_push($args, $artistGenre);
                array_push($args, $artistEthnicity);
                array_push($args, $artistGender);
                array_push($args, $genderOther);
                array_push($args, $genreOther);
                array_push($args, $ethnicityOther);
                array_push($args, $artistResidenceCity);
                array_push($args, $artistResidenceState);
                array_push($args, $artistResidenceProvince);
                array_push($args, $artistResidenceCountry);
                array_push($args, $artistBirthCountry);
                array_push($args, $artistBiography);
                array_push($args, $artistBiographyText);
                array_push($args, $artistPhotoPath);
                array_push($args, $artistWebsite);
                array_push($args, $newGenre);
                array_push($args, $userGenres);
                try{
                $statement = $this->pdo->prepare($sql);
                $statement->execute($args);
                $last_id = $this->pdo->lastInsertId();                 
                return $last_id;              
                }catch (Exception $e) {
                    print($e->getMessage());
                }
            }
            if($action=='update'){
                $args = array();
                // $sql = "UPDATE artist_profile SET is_user_artist = ?,profile_name = ?,artist_first_name = ?,artist_last_name = ?,artist_email_address = ?,artist_living_status = ?,artist_dob = ?,artist_dod = ?,artist_genre = ?,artist_ethnicity = ?,artist_gender = ?,gender_other = ?,genre_other = ?,ethnicity_other = ?,artist_residence_city = ?,artist_residence_state = ?,artist_residence_province = ?,artist_residence_country = ?,artist_birth_country = ?,artist_biography = ?,artist_biography_text = ?,artist_photo_path = ?,artist_website = ?, genre=? WHERE artist_first_name=? and artist_last_name=? and artist_email_address=?";
                $sql = "UPDATE artist_profile SET artist_website = ?, genre=? WHERE artist_first_name=? and artist_last_name=? and artist_email_address=?";
                // array_push($args, $isUserArtist);
                // array_push($args, $profileName);
                // array_push($args, $artistFirstName);
                // array_push($args, $artistLastName);
                // array_push($args, $artistEmailAddress);
                // array_push($args, $artistLivingStatus);
                // array_push($args, $artistDob);
                // array_push($args, $artistDod);
                // array_push($args, $artistGenre);
                // array_push($args, $artistEthnicity);
                // array_push($args, $artistGender);
                // array_push($args, $genderOther);
                // array_push($args, $genreOther);
                // array_push($args, $ethnicityOther);
                // array_push($args, $artistResidenceCity);
                // array_push($args, $artistResidenceState);
                // array_push($args, $artistResidenceProvince);
                // array_push($args, $artistResidenceCountry);
                // array_push($args, $artistBirthCountry);
                // array_push($args, $artistBiography);
                // array_push($args, $artistBiographyText);
                // array_push($args, $artistPhotoPath);
                array_push($args, $artistWebsite);
                array_push($args, $newGenre);
                array_push($args, $artistFirstName);
                array_push($args, $artistLastName);
                array_push($args, $artistEmailAddress);
                // // array_push($args, $artistProfileId);
                try{
                $statement = $this->pdo->prepare($sql);
                $statement->execute($args);
    
                }catch (Exception $e) { 
                    return ($e->getMessage());
                }
            }
        }

        public function linealRelations($artist_profile_id){
            $sql="select ar.artist_relation,ar.artist_name_2 from `artist_relation` ar  where ar.artist_profile_id_1=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1,$artist_profile_id,PDO::PARAM_STR,12);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

        public function reverselinealRelations($artist_profile_id){
            $sql="select ar.artist_relation,ar.artist_name_1 from `artist_relation` ar  where ar.artist_profile_id_2 = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1,$artist_profile_id,PDO::PARAM_STR,12);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

        public function education($artist_profile_id){
            $sql="select ae.institution_name from artist_education ae where ae.education_type='main' and ae.artist_profile_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1,$artist_profile_id,PDO::PARAM_STR,12);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

        public function profileDetails($artist_profile_id){
            $sql="select * from artist_profile ap where ap.artist_profile_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(1,$artist_profile_id,PDO::PARAM_STR,12);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if($result && !empty($result) && isset($result[0]['genre']) && ($result[0]['genre'] != '')){
                $genre_query = "select * from genres where genre_id in (".$result[0]['genre'].");";
                $stmt = $this->pdo->prepare($genre_query);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->execute();
                $genre_result = $stmt->fetchAll();
                $result[0]['genre_id'] = $result[0]['genre'];
                $result[0]['genre'] = implode(', ',array_column($genre_result, 'genre_name'));
            }
            else{
                $result[0]['genre'] = '';
                $result[0]['genre_id'] = '';
            }
            return $result;
        }
     
 
        public function __destruct() {
                $this->pdo = null;
        }

}


 ?>