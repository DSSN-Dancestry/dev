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
    //load in all the potential parameters.  These should match the database columns for the objects.
    $conn = getDbConnection();
    $decoded_params = json_decode($json_params, true);
    $action = $decoded_params['action'];
    $json['action'] = $action;

    // uncomment the following line if you want to turn PHP error reporting on for debug - note, this will break the JSON response
    //ini_set('display_errors', 1);
    //error_reporting(-1);


    $artistProfileId = "";
    if (array_key_exists('artist_profile_id', $decoded_params)) {
        $artistProfileId = $decoded_params['artist_profile_id'];
    }
    $userId = "";
    if (array_key_exists('user_id', $decoded_params)) {
        $userId = $decoded_params['user_id'];
    }
    $isUserArtist = "";
    if (array_key_exists('is_user_artist', $decoded_params)) {
        $isUserArtist = $decoded_params['is_user_artist'];
    }
    $profileName = "";
    if (array_key_exists('profile_name', $decoded_params)) {
        $profileName = $decoded_params['profile_name'];
    }
    $pastProfileName = "";
    if (array_key_exists('past_profile_name', $decoded_params)) {
        $pastProfileName = $decoded_params['past_profile_name'];
    }
    $artistFirstName = "";
    if (array_key_exists('artist_first_name', $decoded_params)) {
        $artistFirstName = $decoded_params['artist_first_name'];
    }
    $artistLastName = "";
    if (array_key_exists('artist_last_name', $decoded_params)) {
        $artistLastName = $decoded_params['artist_last_name'];
    }

    $artistEmailAddress = "";
    if (array_key_exists('artist_email_address', $decoded_params) && !isNullOrEmpty($decoded_params['artist_email_address'])) {
        error_log("looking up id by email address " . $decoded_params['artist_email_address']);
        $artistEmailAddress = $decoded_params['artist_email_address'];
        // My code
        $sql_id = "SELECT
        artist_profile_id FROM artist_profile WHERE artist_email_address = '$artistEmailAddress'";
        // $json['SQL artist_education'] = $sql_id;
        try {
            $conn_id = getDbConnection();
            $statement_id = $conn_id->prepare($sql_id);
            $statement_id->setFetchMode(PDO::FETCH_ASSOC);
            $statement_id->execute();
            $result_id = $statement_id->fetchColumn();
            if ($result_id != false) {
                $artistProfileId = $result_id;
                error_log("set artist id to " . $artistProfileId);
            }
            // $artistProfileId = $result_id[]['artist_profile_id'];
        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }

    $artistLivingStatus = "";
    if (array_key_exists('artist_status', $decoded_params)) {
        $artistLivingStatus = $decoded_params['artist_status'];
    }
    $artistYob = "";
    if (array_key_exists('year_of_birth', $decoded_params)) {
        $artistYob = $decoded_params['year_of_birth'];
    }
    $artistDod = null;
    if (array_key_exists('date_of_death', $decoded_params)) {
        $artistDod = $decoded_params['date_of_death'];
    }
    $artistGenre = "";
    if (array_key_exists('artist_genre', $decoded_params)) {
        $artistGenre = $decoded_params['artist_genre'];
    }
    $artistEthnicity = "";
    if (array_key_exists('ethnicity', $decoded_params)) {
        $artistEthnicity = $decoded_params['ethnicity'];
    }
    $artistGender = "";
    if (array_key_exists('gender', $decoded_params)) {
        $artistGender = $decoded_params['gender'];
    }
    $genderOther = "";
    if (array_key_exists('gender_other', $decoded_params)) {
        $genderOther = $decoded_params['gender_other'];
    }
    $genreOther = "";
    if (array_key_exists('other_artist_text_input', $decoded_params)) {
        $genreOther = $decoded_params['other_artist_text_input'];
    }
    $ethnicityOther = "";
    if (array_key_exists('ethnicity_other', $decoded_params)) {
        $ethnicityOther = $decoded_params['ethnicity_other'];
    }
    $artistResidenceCity = "";
    if (array_key_exists('city_residence', $decoded_params)) {
        $artistResidenceCity = $decoded_params['city_residence'];
    }
    $artistResidenceState = "";
    if (array_key_exists('state_residence', $decoded_params)) {
        $artistResidenceState = $decoded_params['state_residence'];
    }
    $artistResidenceProvince = "";
    if (array_key_exists('state_province', $decoded_params)) {
        $artistResidenceProvince = $decoded_params['state_province'];
    }
    $artistResidenceCountry = "";
    if (array_key_exists('country_residence', $decoded_params)) {
        $artistResidenceCountry = $decoded_params['country_residence'];
    }
    $artistBirthCountry = "";
    if (array_key_exists('country_birth', $decoded_params)) {
        $artistBirthCountry = $decoded_params['country_birth'];
    }
    $artistBiography = "";
    if (array_key_exists('artistbiography', $decoded_params)) {
        $artistBiography = $decoded_params['artistbiography'];
    }
    $artistBiographyText = "";
    if (array_key_exists('artistbiographytext', $decoded_params)) {
        $artistBiographyText = $decoded_params['artistbiographytext'];
    }
    $artistPhotoPath = "";
    if (array_key_exists('artistphotopath', $decoded_params)) {
        $artistPhotoPath = $decoded_params['artistphotopath'];
    }
    $artistWebsite = "";
    if (array_key_exists('artistwebsite', $decoded_params)) {
        $artistWebsite = $decoded_params['artistwebsite'];
    }

    $institutionName = "";
    if (array_key_exists('institution_name', $decoded_params)) {
        $institutionName = $decoded_params['institution_name'];
    }

    $university = "";
    if (array_key_exists('university', $decoded_params)) {
        $university = $decoded_params['university'];
    }

    $artistMajor = "";
    if (array_key_exists('major', $decoded_params)) {
        $artistMajor = $decoded_params['major'];
    }

    $artistDegree = "";
    if (array_key_exists('degree', $decoded_params)) {
        $artistDegree = $decoded_params['degree'];
    }

    $artistOtherDegree = "";
    if (array_key_exists('other_degree', $decoded_params)) {
        $artistOtherDegree = $decoded_params['other_degree'];
    }

    $newGenre = "";
    if (array_key_exists('genre', $decoded_params)) {
        $newGenre = $decoded_params['genre'];
    }

    $userGenres = "";
    if (array_key_exists('user_genres', $decoded_params)) {
        $userGenres = $decoded_params['user_genres'];
    }

    $status = "";
    if (array_key_exists('status', $decoded_params)) {
        $status = $decoded_params['status'];
    }

    $completedDate = "";
    if (array_key_exists('completed_date', $decoded_params)) {
        $completedDate = $decoded_params['completed_date'];
    }

    $lastUpdateDate = "";
    if (array_key_exists('last_update_date', $decoded_params)) {
        $lastUpdateDate = $decoded_params['last_update_date'];
    }

    $source_page = "";
    if (array_key_exists('source_page', $decoded_params)) {
        $source_page = $decoded_params['source_page'];
    }

    if ($action == "addOrEditArtistProfile") {

        // on add or edit, set any of the inbound fields into the session as well
        $keys = array_keys($decoded_params);
        foreach ($keys as $key) {
            if ($key == "status") {
                error_log("checking status - current is " . isset($_SESSION["status"]) ? $_SESSION["status"] : "null" . " New is " . $decoded_params[$key]);
                if (!isset($_SESSION["status"]) || $decoded_params[$key] > $_SESSION["status"]) {
                    $_SESSION[$key] = $decoded_params[$key];
                }
            } else {
                $_SESSION[$key] = $decoded_params[$key];
            }
        }
        // genre and artist genre need special handling
        if (array_key_exists('artist_genre', $decoded_params)) {
            $_SESSION["artist_genre"] = implode(",", $artistGenre);
        }
        if (array_key_exists('genre', $decoded_params)) {
            $_SESSION['genre'] = implode(",", $newGenre);
        }

        $args = array();

        // if we didn't get a profile ID passed in, then we are adding a new
        // record
        if (IsNullOrEmpty($artistProfileId)) {
            // email is required, but for adding other artists, it may not exist.  If we didn't get a value
            // for that, we need to create a dummy value.
            if (isNullOrEmpty($artistEmailAddress)) {
                $str = rand();
                $artistEmailAddress = 'dummyemail@' . md5($str) . sha1($str);
                $_SESSION['artist_email_address'] = $artistEmailAddress;
            }
            // genre and artist genre need special handling
            if (array_key_exists('artist_genre', $decoded_params)) {
                $artistGenre = implode(",", $artistGenre);
                // echo "<script>console.log('Debug Objects: " . $artistGenre . "' );</script>";
            }
            if (array_key_exists('genre', $decoded_params)) {
                $newGenre = implode(",", $newGenre);
            }
            if ($artistDod == '') {
                $artistDod = null;
                // echo "<script>console.log('Debug Objects: " . $artistGenre . "' );</script>";
            }
            if ($artistYob == '') {
                $artistYob = null;
            }


            $sql = "INSERT INTO artist_profile (is_user_artist,profile_name,past_profile_name,artist_first_name,artist_last_name,artist_email_address,artist_living_status,artist_dob,artist_yob,artist_dod,artist_genre,artist_ethnicity,artist_gender,gender_other,genre_other,ethnicity_other,artist_residence_city,artist_residence_state,artist_residence_province,artist_residence_country,artist_birth_country,artist_biography,artist_biography_text,artist_photo_path,artist_website,status,genre, user_genres, last_update_date) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,25,?,?, now());";
            // array_push($args, $artistProfileId);
            array_push($args, $isUserArtist);
            array_push($args, $profileName);
            array_push($args, $pastProfileName);
            array_push($args, $artistFirstName);
            array_push($args, $artistLastName);
            array_push($args, $artistEmailAddress);
            array_push($args, $artistLivingStatus);
            array_push($args, '0000-00-00');
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
            try {
                error_log("Inserting New Artist Profile - " . $artistFirstName . " " . $artistLastName);
                $statement = $conn->prepare($sql);
                $statement->execute($args);
                $last_id = $conn->lastInsertId();
                $json['Record Id'] = $last_id;
                $_SESSION["artist_profile_id"] = $last_id;
                $json['Status'] = "SUCCESS - Inserted Id $last_id";
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
                error_log("Error adding artist : " . $e->getMessage());
            }
        } else {
            // when we update the artist profile, for each parameter passed in, create
            // the update statement, and also set the corresponding value in the session.

            $sql = "UPDATE artist_profile SET ";

            if (array_key_exists('is_user_artist', $decoded_params)) {
                $sql .= " is_user_artist = ?, ";
                array_push($args, $isUserArtist);
                $_SESSION["is_user_artist"] = $isUserArtist;
            }
            if (array_key_exists('profile_name', $decoded_params)) {
                $sql .= " profile_name = ?, ";
                array_push($args, $profileName);
                $_SESSION["profile_name"] = $profileName;
            }
            if (array_key_exists('past_profile_name', $decoded_params)) {
                $sql .= " past_profile_name = ?, ";
                array_push($args, $pastProfileName);
                $_SESSION["past_profile_name"] = $pastProfileName;
            }
            if (array_key_exists('artist_first_name', $decoded_params)) {
                $sql .= " artist_first_name = ?, ";
                array_push($args, $artistFirstName);
                $_SESSION["artist_first_name"] = $artistFirstName;
            }
            if (array_key_exists('artist_last_name', $decoded_params)) {
                $sql .= " artist_last_name = ?, ";
                array_push($args, $artistLastName);
                $_SESSION["artist_last_name"] = $artistLastName;
            }

            // email is required, but it is not mandatory for creating artist profiles.  If we didn't get a value
            // for that, we need to create a dummy value.

            if (array_key_exists('artist_email_address', $decoded_params)) {
                if (isNullOrEmpty($artistEmailAddress)) {
                    $str = rand();
                    $artistEmailAddress = 'dummyemail@' . md5($str) . sha1($str);
                    $_SESSION['artist_email_address'] = $artistEmailAddress;
                }
                $sql .= " artist_email_address = ?, ";
                array_push($args, $artistEmailAddress);
                $_SESSION["artist_email_address"] = $artistEmailAddress;
            }

            if (array_key_exists('artist_status', $decoded_params)) {
                $sql .= " artist_living_status = ?, ";
                array_push($args, $artistLivingStatus);
                $_SESSION["artist_status"] = $artistLivingStatus;
            }
            if (array_key_exists('year_of_birth', $decoded_params)) {
                $sql .= " artist_yob = ?, ";
                array_push($args, $artistYob);
                $_SESSION["year_of_birth"] = $artistYob;
            }
            if (array_key_exists('date_of_death', $decoded_params) && ($decoded_params['date_of_death'] !== null && $decoded_params['date_of_death'] !== "")) {
                $sql .= " artist_dod = ?, ";
                array_push($args, $artistDod);
                $_SESSION["date_of_death"] = $artistDod;
            }
            if (array_key_exists('artist_genre', $decoded_params)) {
                $sql .= " artist_genre = ?, ";
                array_push($args, implode(",", $artistGenre));
                $_SESSION["artist_genre"] = implode(",", $artistGenre);
            }
            if (array_key_exists('ethnicity', $decoded_params)) {
                $sql .= " artist_ethnicity = ?, ";
                array_push($args, $artistEthnicity);
                $_SESSION["ethnicity"] = $artistEthnicity;
            }
            if (array_key_exists('gender', $decoded_params)) {
                $sql .= " artist_gender = ?, ";
                array_push($args, $artistGender);
                $_SESSION["gender"] = $artistGender;
            }
            if (array_key_exists('gender_other', $decoded_params)) {
                $sql .= " gender_other = ?, ";
                array_push($args, $genderOther);
                $_SESSION["gender_other"] = $genderOther;
            }
            if (array_key_exists('other_artist_text_input', $decoded_params)) {
                $sql .= " genre_other = ?, ";
                array_push($args, $genreOther);
                $_SESSION["other_artist_text_input"] = $genreOther;
            }
            if (array_key_exists('ethnicity_other', $decoded_params)) {
                $sql .= " ethnicity_other = ?, ";
                array_push($args, $ethnicityOther);
                $_SESSION["ethnicity_other"] = $ethnicityOther;
            }
            if (array_key_exists('city_residence', $decoded_params)) {
                $sql .= " artist_residence_city = ?, ";
                array_push($args, $artistResidenceCity);
                $_SESSION["city_residence"] = $artistResidenceCity;
            }
            if (array_key_exists('state_residence', $decoded_params)) {
                $sql .= " artist_residence_state = ?, ";
                array_push($args, $artistResidenceState);
                $_SESSION["state_residence"] = $artistResidenceState;
            }
            if (array_key_exists('state_province', $decoded_params)) {
                $sql .= " artist_residence_province = ?, ";
                array_push($args, $artistResidenceProvince);
                $_SESSION["state_province"] = $artistResidenceProvince;
            }
            if (array_key_exists('country_residence', $decoded_params)) {
                $sql .= " artist_residence_country = ?, ";
                array_push($args, $artistResidenceCountry);
                $_SESSION['country_residence'] = $artistResidenceCountry;
            }
            if (array_key_exists('country_birth', $decoded_params)) {
                $sql .= " artist_birth_country = ?, ";
                array_push($args, $artistBirthCountry);
                $_SESSION['country_birth'] = $artistBirthCountry;
            }
            if (array_key_exists('artistbiography', $decoded_params)) {
                $sql .= " artist_biography = ?, ";
                array_push($args, $artistBiography);
                $_SESSION['biography_file_path'] = $artistBiography;
            }
            if (array_key_exists('artistbiographytext', $decoded_params)) {
                $sql .= " artist_biography_text = ?, ";
                array_push($args, $artistBiographyText);
                $_SESSION['biography_text'] = $artistBiographyText;
            }
            if (array_key_exists('artistphotopath', $decoded_params)) {
                $sql .= " artist_photo_path= ?, ";
                array_push($args, $artistPhotoPath);
                $_SESSION['photo_file_path'] = $artistPhotoPath;
            }
            if (array_key_exists('artistwebsite', $decoded_params)) {
                $sql .= " artist_website = ?, ";
                array_push($args, $artistWebsite);
            }
            if (array_key_exists('genre', $decoded_params)) {
                $sql .= " genre = ?, ";
                array_push($args, implode(",", $newGenre));
                $_SESSION['genre'] = implode(",", $newGenre);
            }

            if (array_key_exists('status', $decoded_params)) {
                $sql .= " status = greatest(status, ?), ";
                array_push($args, $status);
            }

            if (array_key_exists('user_genres', $decoded_params)) {
                $sql .= " user_genres = ?, ";
                array_push($args, $userGenres);
            }

            if (array_key_exists('completed_date', $decoded_params)) {
                $sql .= " completed_date = now(), ";
            }

            $sql .= " last_update_date = now(), ";


            // trim off the trailing comma and space
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE artist_profile_id = ?;";
            array_push($args, $artistProfileId);

            $count = 0;

            $json["SQL"] = $sql;
            //$json["session"] =   print_r($_SESSION);
            try {
                error_log("Updating Artist Profile - " . $artistFirstName . " " . $artistLastName);
                $statement = $conn->prepare($sql);
                $statement->execute($args);

                $count = $statement->rowCount();
                if ($count > 0) {
                    $json['Status'] = "SUCCESS - Updated $count Rows for user " . $artistProfileId;
                } else {
                    // note - if the values passed in are the same as what is in the database, this will also return 0 rows updated.
                    // finding that out was a few hours of my life I'll never get back.
                    $json['Status'] = "ERROR - Updated 0 Rows for user " . $artistProfileId . "- Check for Valid Ids : " . implode(",", $statement->errorInfo());
                    //$statement->debugDumpParams();
                    //echo implode("|", $args);
                }
            } catch (Exception $e) {
                $json['Exception'] = "Error updaing profile " . $e->getMessage();
            }

            try {
                // if this is the user's own profile, and they updated the name or email, we need to keep the user
                // profile in sync with the artist profile.
                if ($_SESSION['contribution_type'] == "own" && $count > 0 && (!isNullOrEmpty($artistEmailAddress) || !isNullOrEmpty($artistFirstName) || !isNullOrEmpty($artistLastName))) {
                    $sql = "UPDATE user_profile SET user_first_name = ?, user_last_name = ?, user_email_address = ? where user_id = ?;";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$artistFirstName, $artistLastName, $artistEmailAddress, $userId]);
                    if ($count > 0) {
                        $json['Status2'] = "SUCCESS - Updated $count Rows for user " . $userId;
                    } else {
                        $json['Status2'] = "ERROR - Updated 0 user Rows for user " . $userId . "- Check for Valid Ids ";
                    }
                    $_SESSION["user_firstname"] = $artistFirstName;
                    $_SESSION["user_lastname"] = $artistLastName;
                    $_SESSION["user_email_address"] = $artistEmailAddress;
                }
            } catch (Exception $e) {
                $json['Exception'] = "Error syncing profile " . $e->getMessage();
            }

            try {
                // update the education entries.  These don't have a unique primary key, so we're just doing a wipe and replace
                if (!empty($university) || !empty($institutionName)) {
                    $_SESSION["university"] = $university;
                    $_SESSION["degree"] = $artistDegree;
                    $_SESSION["major"] = $artistMajor;
                    $_SESSION["institution_name"] = $institutionName;
                    $_SESSION["other_degree"] = $artistOtherDegree;

                    // first clean out the old records
                    $query = "DELETE FROM artist_education WHERE artist_profile_id=?";
                    $statement = $conn->prepare($query);
                    $statement->execute([$artistProfileId]);

                    foreach ($university as $key => $value) {
                        $query = "INSERT INTO artist_education (institution_name, major, degree, education_type, artist_profile_id) VALUES (?,?,?,'main',?)";
                        $statement = $conn->prepare($query);
                        $statement->execute([$value, $artistMajor[$key], $artistDegree[$key], $artistProfileId]);
                    }

                    foreach ($institutionName as $key => $value) {
                        $query = "INSERT INTO artist_education (institution_name, major, degree, education_type, artist_profile_id) VALUES (?,'',?,'other',?)";
                        $statement = $conn->prepare($query);
                        $statement->execute([$value, $artistOtherDegree[$key], $artistProfileId]);
                    }
                }
            } catch (Exception $e) {
                $json['Exception'] = "Error updating education " . $e->getMessage();
            }
            $json['Action'] = $action;
        }
    } elseif ($action == "deleteArtistProfile") {
        $sql = "DELETE FROM artist_profile WHERE artist_profile_id = ?";
        $args = array();
        array_push($args, $artistProfileId);
        if (!IsNullOrEmpty($artistProfileId)) {
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
                $json['Exception'] = $e->getMessage();
            }
        } else {
            $json['Status'] = "ERROR - Id is required";
        }
        $json['Action'] = $action;
    } elseif ($action == "getArtistProfile") {
        $args = array();
        $sql = "SELECT * FROM artist_profile";
        $first = true;
        if (!IsNullOrEmpty($artistProfileId)) {
            if ($first) {
                $sql .= " WHERE artist_profile_id = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_profile_id = ? ";
            }
            array_push($args, $artistProfileId);
        }
        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        if (!IsNullOrEmpty($newGenre)) {
            if ($first) {
                $sql .= " WHERE genre = ? ";
                $first = false;
            } else {
                $sql .= " AND genre = ? ";
            }
            array_push($args, $newGenre);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['artist_profile'][] = $row1;
        }
        if (array_key_exists('setLinealSession', $decoded_params)) {
            $artist_profile_query = "SELECT * FROM artist_profile WHERE is_user_artist='artist' and artist_email_address = ?";
            $statement = $conn->prepare($artist_profile_query);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute([$_SESSION['user_email_address']]);
            $artist_profile_id = $statement->fetchAll();
            if (isset($artist_profile_id[0])) {
                $_SESSION['artist_profile_id'] = $artist_profile_id[0]['artist_profile_id'];
                $_SESSION['status'] = $artist_profile_id[0]['STATUS'];
                $_SESSION["artist_first_name"] = $artist_profile_id[0]['artist_first_name'];
                $_SESSION["artist_last_name"] = $artist_profile_id[0]['artist_last_name'];
                $_SESSION["artist_email_address"] = $artist_profile_id[0]['artist_email_address'];
            }

            $_SESSION['lineal_first_name'] = $row1['artist_first_name'];
            $_SESSION['lineal_last_name'] = $row1['artist_last_name'];
            $_SESSION['lineal_added'] = false;
        }
        if (array_key_exists('check_lineal_other', $decoded_params)) {
            $artist_profile_query = "SELECT * FROM artist_profile WHERE is_user_artist='artist' and artist_email_address = ?";
            $statement = $conn->prepare($artist_profile_query);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute([$json['artist_profile'][0]['profile_name']]);
            $parent_artist_profile = $statement->fetchAll();
            // print_r($parent_artist_profile);
            if (isset($parent_artist_profile[0])) {
                $json['artist_profile'][0]['other_profile'] = true;
                $json['artist_profile'][0]['lineal_profile'] = false;
                $json['artist_profile'][0]['parent_artist_profile_id'] = $parent_artist_profile[0]['artist_profile_id'];
                $json['artist_profile'][0]['parent_artist_profile_first_name'] = $parent_artist_profile[0]['artist_first_name'];
                $json['artist_profile'][0]['parent_artist_profile_last_name'] = $parent_artist_profile[0]['artist_last_name'];
                $json['artist_profile'][0]["parent_artist_profile_email_address"] = $parent_artist_profile[0]['artist_email_address'];
            } else {
                $json['artist_profile'][0]['other_profile'] = false;
                $json['artist_profile'][0]['lineal_profile'] = true;
            }
            // print_r($json);
        }
    } elseif ($action == "getArtistProfileForNetwork") {
        $args = array();
        $sql = "SELECT distinct ap.* from artist_profile ap, artist_education ae WHERE ap.artist_profile_id = ae.artist_profile_id";
        $first = false;
        if (!IsNullOrEmpty($artistProfileId)) {
            if ($first) {
                $sql .= " WHERE ap.artist_profile_id = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_profile_id = ? ";
            }
            array_push($args, $artistProfileId);
        }
        if (!IsNullOrEmpty($institutionName)) {
            if ($first) {
                $sql .= " WHERE ae.institution_name = ? ";
                $first = false;
            } else {
                $sql .= " AND ae.institution_name = ? ";
            }
            array_push($args, $institutionName);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE ap.artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE ap.artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE ap.artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE ap.gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ap.ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE ap.artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE ap.artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND ap.artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($institutionName)) {
            if ($first) {
                $sql .= " WHERE ae.institution_name = ? ";
                $first = false;
            } else {
                $sql .= " AND ae.institution_name = ? ";
            }
            array_push($args, $institutionName);
        }
        if (!IsNullOrEmpty($artistDegree)) {
            if ($first) {
                $sql .= " WHERE ae.degree = ? ";
                $first = false;
            } else {
                $sql .= " AND ae.degree = ? ";
            }
            array_push($args, $artistDegree);
        }
        if (!IsNullOrEmpty($artistMajor)) {
            if ($first) {
                $sql .= " WHERE ae.major = ? ";
                $first = false;
            } else {
                $sql .= " AND ae.major = ? ";
            }
            array_push($args, $artistMajor);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['artist_profile'][] = $row1;
        }
    } elseif ($action == "getArtistNames") {
        $args = array();
        $sql = "SELECT distinct artist_profile_id, artist_first_name, artist_last_name, artist_photo_path from artist_profile";
        if (!IsNullOrEmpty($source_page)) {
            $sql .= " WHERE STATUS = 100 ";
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['artist_name'][] = $row1;
        }
    } elseif ($action == "getUniversityNames") {
        $args = array();
        $sql = "SELECT distinct institution_name from artist_education";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['university'][] = $row1;
        }
    } elseif ($action == "getCityNames") {
        $args = array();
        $sql = "SELECT distinct artist_residence_city from artist_profile";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['city_names'][] = $row1;
        }
    } elseif ($action == "getStateNames") {
        $args = array();
        $sql = "SELECT distinct artist_residence_state from artist_profile";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['state_names'][] = $row1;
        }
    } elseif ($action == "getCountryNames") {
        $args = array();
        $sql = "SELECT distinct artist_residence_country from artist_profile";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['country_names'][] = $row1;
        }
    } elseif ($action == "getMajor") {
        $args = array();
        $sql = "SELECT distinct major from artist_education";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['major_names'][] = $row1;
        }
    } elseif ($action == "getArtistGenre") {
        $args = array();
        $sql = "SELECT distinct artist_genre from artist_profile";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['artist_genres'][] = $row1;
        }
    } elseif ($action == "getDegree") {
        $args = array();
        $sql = "SELECT distinct degree from artist_education";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['degree_names'][] = $row1;
        }
    } elseif ($action == "getEthnicity") {
        $args = array();
        $sql = "SELECT distinct artist_ethnicity from artist_profile";
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['ethnicity_names'][] = $row1;
        }
    } elseif ($action == "getCompleteArtistProfile") {
        $args = array();
        // Charul Test
        $loggedin_user = $_SESSION['user_id'];
        $loggedin_useremail = $_SESSION['user_email_address'];
        //CHARUL Testing Logged in Users Artist profile id from Session
        // $loggedin_artist_profile_id = $_SESSION['artist_profile_id'];
        // print_r($loggedin_artist_profile_id);
        // print_r($loggedin_useremail);
        // $loggedin_artistprofileid = "SELECT artist_profile_id FROM artist_profile where artist_email_address = $loggedin_useremail";
        // print_r($sql);
        // print("Session details of logged in user");
        // print_r($_SESSION);

        // CHARUL TESTING: Updating session with complete Artist PRofile Details
        $user_email_address = $_SESSION["user_email_address"];

        // require 'connect.php';

        // $conn = getDbConnection();

        // fetch the logged in user's profile record
        $query_myProfile = "SELECT * FROM artist_profile WHERE artist_email_address = '$user_email_address'";
        $statement = $conn->prepare($query_myProfile);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute([$user_email_address]);
        $count_myProfile = $statement->rowCount();
        $result_myProfile = $statement->fetchAll();

        // fetch any profiles that they have created for other artists
        $query_anotherArtist = "SELECT * FROM artist_profile WHERE profile_name= ? && artist_email_address != ? ";
        $statement2 = $conn->prepare($query_anotherArtist);
        $statement2->setFetchMode(PDO::FETCH_ASSOC);
        $statement2->execute([$user_email_address, $user_email_address]);
        $count_anotherArtist = $statement2->rowCount();
        $result_anotherArtist = $statement2->fetchAll();

        // fetch any profiles created for other artists that were later
        // taken over by those other artists
        $query_checkEnable = "SELECT * FROM artist_profile WHERE past_profile_name = ?";
        $statement3 = $conn->prepare($query_checkEnable);
        $statement3->setFetchMode(PDO::FETCH_ASSOC);
        $statement3->execute([$user_email_address]);
        $count_checkEnable = $statement3->rowCount();
        $result_checkEnable = $statement3->fetchAll();
        // CHARUL TESTING: Updating session with complete Artist PRofile Details


        $loggedin_artist_profile_id = $result_myProfile[0]['artist_profile_id'];
        //echo "bubbles"
        // $sql = "SELECT * FROM artist_profile where (artist_email_address = '$loggedin_useremail') OR artist_profile_id in (SELECT artist_profile_id_2 from artist_relation where artist_email_id_1 = '$loggedin_useremail')";
        $sql = "SELECT * FROM artist_profile where (artist_profile_id = '$loggedin_artist_profile_id') OR artist_profile_id in (SELECT artist_profile_id_2 from artist_relation where artist_profile_id_1 = '$loggedin_artist_profile_id')";
        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }
        if (!IsNullOrEmpty($artistProfileId)) {
            if ($first) {
                $sql .= " WHERE artist_profile_id = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_profile_id = ? ";
            }
            array_push($args, $artistProfileId);
        }
        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
        }
    } elseif ($action == "setArtistProfileSession") {
        // print_r("blah", $decoded_params);
        $_SESSION['artist_profile_id'] = $decoded_params['artist_profile_information']['artist_profile_id'];

        $_SESSION['artist_first_name'] = $decoded_params['artist_profile_information']['artist_first_name'];
        $_SESSION['artist_last_name'] = $decoded_params['artist_profile_information']['artist_last_name'];
        $_SESSION['status'] = '25';
        $_SESSION['is_user_artist'] = 'other';
        $_SESSION['contribution_type'] = 'other';
        $_SESSION["timeline_flow"] = "edit";

        $json['Status'] = "SUCCESS";
    } elseif ($action == "updateArtistOwner") {
        $args = array();
        $decoded_params = $decoded_params['artist_profile_information'];

        $sql = "UPDATE artist_profile SET ";
        if (array_key_exists('profile_name', $decoded_params)) {
            $sql .= " profile_name = '" . $decoded_params['profile_name'] . "',";
        }
        if (array_key_exists('past_profile_name', $decoded_params)) {
            $sql .= " past_profile_name = '" . $decoded_params['past_profile_name'] . "',";
        }
        if (array_key_exists('artist_email_address', $decoded_params)) {
            $sql .= " artist_email_address = '" . $decoded_params['artist_email_address'] . "',";
        }
        if (array_key_exists('is_user_artist', $decoded_params)) {
            $sql .= " is_user_artist = '" . $decoded_params['is_user_artist'] . "',";
        }
        if (array_key_exists('STATUS', $decoded_params)) {
            $sql .= " STATUS = '" . $decoded_params['STATUS'] . "',";
        }
        $sql .= " last_update_date = now() WHERE artist_profile_id = ?;";
        array_push($args, $decoded_params['artist_profile_id']);

        $statement = $conn->prepare($sql);
        $statement->execute($args);
        $json['Status'] = "SUCCESS";
    } // CHARUL TESTING for Extension of network
    elseif ($action == "getSelectedArtistProfile") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_profile_id']; // THis is an Array containing only one element
        // echo "Ajax Call for Netwrk expansion testing";
        // echo "Passed parameter i.e Artist profile id is:";
        // echo $artistProfileId[0];

        // echo $id_received[0];
        $profile_id_received = $artistProfileId[0];
        // echo " Single element of array using artistProfileID variable is:";
        // echo $profile_id_received;
        // echo "Looping and Printing from array";
        // echo $id_received;
        // foreach ($artistProfileId as $value){ //Looping through the arry and printing profile ids received as parameters of Ajax call
        //   print $value;
        // }
        $sql = "SELECT * FROM artist_profile where artist_profile_id in (select artist_profile_id_2 from artist_relation where artist_profile_id_1 = '$profile_id_received')";
        // Charul: Implementing same as in getcompleteartistprofile
        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }
        // if (!IsNullOrEmpty($profile_id_received)) {
        //     if ($first) {
        //         $sql .= " WHERE artist_profile_id = ? ";
        //         $first = false;
        //     } else {
        //         $sql .= " AND artist_profile_id = ? ";
        //     }
        //     array_push($args, $artistProfileId);
        // }
        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    }
    // CHARUL TESTING for Extension of network

    // SD Charul Testing Default Visualization Network
    elseif ($action == "getDefaultVisualization") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_profile_id']; // THis is an Array containing only one element
        $id_received_2 = $decoded_params['artist_profile_id_1'];
        $id_received_3 = $decoded_params['artist_profile_id_2'];
        $id_received_4 = $decoded_params['artist_profile_id_3'];
        // $id_received_5 = $decoded_params['artist_profile_id_4'];
        // $id_received_6 = $decoded_params['artist_profile_id_5'];
        // $id_received_7 = $decoded_params['artist_profile_id_6'];
        // $id_received_8 = $decoded_params['artist_profile_id_7'];
        // echo "Ajax Call for Netwrk expansion testing";
        // echo "Passed parameter i.e Artist profile id is:";
        // echo $artistProfileId[0];

        // echo $id_received[0];
        $profile_id_received = $artistProfileId[0];
        // echo " Single element of array using artistProfileID variable is:";
        // echo $profile_id_received;
        // echo "Looping and Printing from array";
        // echo $id_received;
        // foreach ($artistProfileId as $value){ //Looping through the arry and printing profile ids received as parameters of Ajax call
        //   print $value;
        // }
        // $sql = "SELECT * FROM artist_profile where (artist_profile_id = '$loggedin_artist_profile_id') OR artist_profile_id in (SELECT artist_profile_id_2 from artist_relation where artist_profile_id_1 = '$loggedin_artist_profile_id')";

        $sql = "SELECT * FROM artist_profile where artist_profile_id in ('$id_received', '$id_received_2', '$id_received_3', '$id_received_4') OR artist_profile_id in (SELECT artist_profile_id_2 from artist_relation where artist_profile_id_1 in ('$id_received', '$id_received_2', '$id_received_3', '$id_received_4'))";

        // Charul: Implementing same as in getcompleteartistprofile
        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }
        // if (!IsNullOrEmpty($profile_id_received)) {
        //     if ($first) {
        //         $sql .= " WHERE artist_profile_id = ? ";
        //         $first = false;
        //     } else {
        //         $sql .= " AND artist_profile_id = ? ";
        //     }
        //     array_push($args, $artistProfileId);
        // }
        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    }
    // SD Charul Testing Default Visualization Network

    // CD Charul Testing search by Name
    elseif ($action == "getArtistProfileByName") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_profile_id']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where (artist_profile_id = '$id_received') OR artist_profile_id in (SELECT artist_profile_id_2 from artist_relation where artist_profile_id_1 = '$id_received')";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    }

    // CD Charul Testing search by Name

    // RR Charul Testing Search by Country
    elseif ($action == "getArtistProfilesByCountry") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where (artist_residence_country = '$id_received')";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    }

    // RR Charul Testing Search by Country

    // RKD Charul Testing Search by State
    elseif ($action == "getArtistProfilesByState") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where (artist_residence_state = '$id_received')";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    } // RKD Charul Testing Search by State
    elseif ($action == "getArtistProfilesByCity") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where (artist_residence_city = '$id_received')";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    } // RKD Charul Testing Search by State
    elseif ($action == "getArtistProfilesByEthnicity") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where (artist_ethnicity = '$id_received')";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    } elseif ($action == "getArtistProfilesByArtistType") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where artist_genre LIKE '%$id_received%'";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    } elseif ($action == "getArtistProfilesByGenre") {
        // code...
        $args = array();
        $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element

        $sql = "SELECT * FROM artist_profile where genre LIKE '%$id_received%'";


        $first = true;
        if (!IsNullOrEmpty($source_page)) {
            if ($first) {
                $sql .= " WHERE STATUS = 100 ";
                $first = false;
            } else {
                $sql .= " AND STATUS = 100 ";
            }
        }

        if (!IsNullOrEmpty($isUserArtist)) {
            if ($first) {
                $sql .= " WHERE is_user_artist = ? ";
                $first = false;
            } else {
                $sql .= " AND is_user_artist = ? ";
            }
            array_push($args, $isUserArtist);
        }
        if (!IsNullOrEmpty($profileName)) {
            if ($first) {
                $sql .= " WHERE profile_name = ? ";
                $first = false;
            } else {
                $sql .= " AND profile_name = ? ";
            }
            array_push($args, $profileName);
        }
        if (!IsNullOrEmpty($artistFirstName)) {
            if ($first) {
                $sql .= " WHERE artist_first_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_first_name = ? ";
            }
            array_push($args, $artistFirstName);
        }
        if (!IsNullOrEmpty($artistLastName)) {
            if ($first) {
                $sql .= " WHERE artist_last_name = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_last_name = ? ";
            }
            array_push($args, $artistLastName);
        }
        if (!IsNullOrEmpty($artistEmailAddress)) {
            if ($first) {
                $sql .= " WHERE artist_email_address = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_email_address = ? ";
            }
            array_push($args, $artistEmailAddress);
        }
        if (!IsNullOrEmpty($artistLivingStatus)) {
            if ($first) {
                $sql .= " WHERE artist_living_status = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_living_status = ? ";
            }
            array_push($args, $artistLivingStatus);
        }
        if (!IsNullOrEmpty($artistYob)) {
            if ($first) {
                $sql .= " WHERE artist_yob = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_yob = ? ";
            }
            array_push($args, $artistYob);
        }
        if (!IsNullOrEmpty($artistDod)) {
            if ($first) {
                $sql .= " WHERE artist_dod = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_dod = ? ";
            }
            array_push($args, $artistDod);
        }
        if (!IsNullOrEmpty($artistGenre)) {
            if ($first) {
                $sql .= " WHERE artist_genre = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_genre = ? ";
            }
            array_push($args, $artistGenre);
        }
        if (!IsNullOrEmpty($artistEthnicity)) {
            if ($first) {
                $sql .= " WHERE artist_ethnicity = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_ethnicity = ? ";
            }
            array_push($args, $artistEthnicity);
        }
        if (!IsNullOrEmpty($artistGender)) {
            if ($first) {
                $sql .= " WHERE artist_gender = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_gender = ? ";
            }
            array_push($args, $artistGender);
        }
        if (!IsNullOrEmpty($genderOther)) {
            if ($first) {
                $sql .= " WHERE gender_other = ? ";
                $first = false;
            } else {
                $sql .= " AND gender_other = ? ";
            }
            array_push($args, $genderOther);
        }
        if (!IsNullOrEmpty($genreOther)) {
            if ($first) {
                $sql .= " WHERE genre_other = ? ";
                $first = false;
            } else {
                $sql .= " AND genre_other = ? ";
            }
            array_push($args, $genreOther);
        }
        if (!IsNullOrEmpty($ethnicityOther)) {
            if ($first) {
                $sql .= " WHERE ethnicity_other = ? ";
                $first = false;
            } else {
                $sql .= " AND ethnicity_other = ? ";
            }
            array_push($args, $ethnicityOther);
        }
        if (!IsNullOrEmpty($artistResidenceCity)) {
            if ($first) {
                $sql .= " WHERE artist_residence_city = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_city = ? ";
            }
            array_push($args, $artistResidenceCity);
        }
        if (!IsNullOrEmpty($artistResidenceState)) {
            if ($first) {
                $sql .= " WHERE artist_residence_state = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_state = ? ";
            }
            array_push($args, $artistResidenceState);
        }
        if (!IsNullOrEmpty($artistResidenceProvince)) {
            if ($first) {
                $sql .= " WHERE artist_residence_province = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_province = ? ";
            }
            array_push($args, $artistResidenceProvince);
        }
        if (!IsNullOrEmpty($artistResidenceCountry)) {
            if ($first) {
                $sql .= " WHERE artist_residence_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_residence_country = ? ";
            }
            array_push($args, $artistResidenceCountry);
        }
        if (!IsNullOrEmpty($artistBirthCountry)) {
            if ($first) {
                $sql .= " WHERE artist_birth_country = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_birth_country = ? ";
            }
            array_push($args, $artistBirthCountry);
        }
        if (!IsNullOrEmpty($artistBiography)) {
            if ($first) {
                $sql .= " WHERE artist_biography = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography = ? ";
            }
            array_push($args, $artistBiography);
        }
        if (!IsNullOrEmpty($artistBiographyText)) {
            if ($first) {
                $sql .= " WHERE artist_biography_text = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_biography_text = ? ";
            }
            array_push($args, $artistBiographyText);
        }
        if (!IsNullOrEmpty($artistPhotoPath)) {
            if ($first) {
                $sql .= " WHERE artist_photo_path = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_photo_path = ? ";
            }
            array_push($args, $artistPhotoPath);
        }
        if (!IsNullOrEmpty($artistWebsite)) {
            if ($first) {
                $sql .= " WHERE artist_website = ? ";
                $first = false;
            } else {
                $sql .= " AND artist_website = ? ";
            }
            array_push($args, $artistWebsite);
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }

        $conn2 = getDbConnection();
        foreach ($result as $row1) {
            $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_genres'] = $sql;
            try {
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['genres'][] = $row2;
            }


            $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_works'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['works'][] = $row2;
            }
            $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_education'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_education'][] = $row2;
            }
            $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = " . $row1['artist_profile_id'];
            $json['SQL artist_relation'] = $sql;
            try {
                //$conn2 = getDbConnection();
                $statement2 = $conn2->prepare($sql);
                $statement2->setFetchMode(PDO::FETCH_ASSOC);
                $statement2->execute();
                $result2 = $statement2->fetchAll();
            } catch (Exception $e) {
                $json['Exception'] = $e->getMessage();
            }
            foreach ($result2 as $row2) {
                $row1['artist_relation'][] = $row2;
            }
            $json['artist_profile'][] = $row1;
            // $json['Status'] = "SUCCESS";
        }

        // Charul: Implementing Same as in getcompleteartistprofile
    }

    //The following 3 sql accesses are not completely right, and need to be fixed: that is why they are commented out
    // elseif ($action == "getArtistProfilesByUniversity") {
    //   // code...
    //   $args = array();
    //   $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element
    //
    //   $sql = "SELECT * FROM artist_education where (institution_name = '$id_received')";
    //
    //
    //   $first = true;
    //   if (!IsNullOrEmpty($source_page)) {
    //       if ($first) {
    //           $sql .= " WHERE STATUS = 100 ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND STATUS = 100 ";
    //       }
    //   }
    //
    //   if (!IsNullOrEmpty($isUserArtist)) {
    //       if ($first) {
    //           $sql .= " WHERE is_user_artist = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND is_user_artist = ? ";
    //       }
    //       array_push($args, $isUserArtist);
    //   }
    //   if (!IsNullOrEmpty($profileName)) {
    //       if ($first) {
    //           $sql .= " WHERE profile_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND profile_name = ? ";
    //       }
    //       array_push($args, $profileName);
    //   }
    //   if (!IsNullOrEmpty($artistFirstName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_first_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_first_name = ? ";
    //       }
    //       array_push($args, $artistFirstName);
    //   }
    //   if (!IsNullOrEmpty($artistLastName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_last_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_last_name = ? ";
    //       }
    //       array_push($args, $artistLastName);
    //   }
    //   if (!IsNullOrEmpty($artistEmailAddress)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_email_address = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_email_address = ? ";
    //       }
    //       array_push($args, $artistEmailAddress);
    //   }
    //   if (!IsNullOrEmpty($artistLivingStatus)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_living_status = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_living_status = ? ";
    //       }
    //       array_push($args, $artistLivingStatus);
    //   }
    //   if (!IsNullOrEmpty($artistYob)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_yob = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_yob = ? ";
    //       }
    //       array_push($args, $artistYob);
    //   }
    //   if (!IsNullOrEmpty($artistDod)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_dod = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_dod = ? ";
    //       }
    //       array_push($args, $artistDod);
    //   }
    //   if (!IsNullOrEmpty($artistGenre)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_genre = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_genre = ? ";
    //       }
    //       array_push($args, $artistGenre);
    //   }
    //   if (!IsNullOrEmpty($artistEthnicity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_ethnicity = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_ethnicity = ? ";
    //       }
    //       array_push($args, $artistEthnicity);
    //   }
    //   if (!IsNullOrEmpty($artistGender)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_gender = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_gender = ? ";
    //       }
    //       array_push($args, $artistGender);
    //   }
    //   if (!IsNullOrEmpty($genderOther)) {
    //       if ($first) {
    //           $sql .= " WHERE gender_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND gender_other = ? ";
    //       }
    //       array_push($args, $genderOther);
    //   }
    //   if (!IsNullOrEmpty($genreOther)) {
    //       if ($first) {
    //           $sql .= " WHERE genre_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND genre_other = ? ";
    //       }
    //       array_push($args, $genreOther);
    //   }
    //   if (!IsNullOrEmpty($ethnicityOther)) {
    //       if ($first) {
    //           $sql .= " WHERE ethnicity_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND ethnicity_other = ? ";
    //       }
    //       array_push($args, $ethnicityOther);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_city = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_city = ? ";
    //       }
    //       array_push($args, $artistResidenceCity);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceState)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_state = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_state = ? ";
    //       }
    //       array_push($args, $artistResidenceState);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceProvince)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_province = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_province = ? ";
    //       }
    //       array_push($args, $artistResidenceProvince);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_country = ? ";
    //       }
    //       array_push($args, $artistResidenceCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBirthCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_birth_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_birth_country = ? ";
    //       }
    //       array_push($args, $artistBirthCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBiography)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography = ? ";
    //       }
    //       array_push($args, $artistBiography);
    //   }
    //   if (!IsNullOrEmpty($artistBiographyText)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography_text = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography_text = ? ";
    //       }
    //       array_push($args, $artistBiographyText);
    //   }
    //   if (!IsNullOrEmpty($artistPhotoPath)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_photo_path = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_photo_path = ? ";
    //       }
    //       array_push($args, $artistPhotoPath);
    //   }
    //   if (!IsNullOrEmpty($artistWebsite)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_website = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_website = ? ";
    //       }
    //       array_push($args, $artistWebsite);
    //   }
    //   $json['SQL'] = $sql;
    //   try {
    //       $statement = $conn->prepare($sql);
    //       $statement->setFetchMode(PDO::FETCH_ASSOC);
    //       $statement->execute($args);
    //       $result = $statement->fetchAll();
    //   } catch (Exception $e) {
    //       $json['Exception'] =  $e->getMessage();
    //   }
    //
    //   $conn2 = getDbConnection();
    //   foreach ($result as $row1) {
    //       $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_genres'] = $sql;
    //       try {
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['genres'][] = $row2;
    //       }
    //
    //
    //       $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_works'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['works'][] = $row2;
    //       }
    //       $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_education'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_education'][] = $row2;
    //       }
    //       $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_relation'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_relation'][] = $row2;
    //       }
    //       $json['artist_profile'][] = $row1;
    //       // $json['Status'] = "SUCCESS";
    //   }
    //
    //   // Charul: Implementing Same as in getcompleteartistprofile
    // }
    //
    // elseif ($action == "getArtistProfilesByDegree") {
    //   // code...
    //   $args = array();
    //   $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element
    //
    //   $sql = "SELECT * FROM artist_education where (degree = '$id_received')";
    //
    //
    //   $first = true;
    //   if (!IsNullOrEmpty($source_page)) {
    //       if ($first) {
    //           $sql .= " WHERE STATUS = 100 ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND STATUS = 100 ";
    //       }
    //   }
    //
    //   if (!IsNullOrEmpty($isUserArtist)) {
    //       if ($first) {
    //           $sql .= " WHERE is_user_artist = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND is_user_artist = ? ";
    //       }
    //       array_push($args, $isUserArtist);
    //   }
    //   if (!IsNullOrEmpty($profileName)) {
    //       if ($first) {
    //           $sql .= " WHERE profile_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND profile_name = ? ";
    //       }
    //       array_push($args, $profileName);
    //   }
    //   if (!IsNullOrEmpty($artistFirstName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_first_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_first_name = ? ";
    //       }
    //       array_push($args, $artistFirstName);
    //   }
    //   if (!IsNullOrEmpty($artistLastName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_last_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_last_name = ? ";
    //       }
    //       array_push($args, $artistLastName);
    //   }
    //   if (!IsNullOrEmpty($artistEmailAddress)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_email_address = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_email_address = ? ";
    //       }
    //       array_push($args, $artistEmailAddress);
    //   }
    //   if (!IsNullOrEmpty($artistLivingStatus)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_living_status = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_living_status = ? ";
    //       }
    //       array_push($args, $artistLivingStatus);
    //   }
    //   if (!IsNullOrEmpty($artistYob)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_yob = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_yob = ? ";
    //       }
    //       array_push($args, $artistYob);
    //   }
    //   if (!IsNullOrEmpty($artistDod)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_dod = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_dod = ? ";
    //       }
    //       array_push($args, $artistDod);
    //   }
    //   if (!IsNullOrEmpty($artistGenre)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_genre = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_genre = ? ";
    //       }
    //       array_push($args, $artistGenre);
    //   }
    //   if (!IsNullOrEmpty($artistEthnicity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_ethnicity = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_ethnicity = ? ";
    //       }
    //       array_push($args, $artistEthnicity);
    //   }
    //   if (!IsNullOrEmpty($artistGender)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_gender = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_gender = ? ";
    //       }
    //       array_push($args, $artistGender);
    //   }
    //   if (!IsNullOrEmpty($genderOther)) {
    //       if ($first) {
    //           $sql .= " WHERE gender_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND gender_other = ? ";
    //       }
    //       array_push($args, $genderOther);
    //   }
    //   if (!IsNullOrEmpty($genreOther)) {
    //       if ($first) {
    //           $sql .= " WHERE genre_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND genre_other = ? ";
    //       }
    //       array_push($args, $genreOther);
    //   }
    //   if (!IsNullOrEmpty($ethnicityOther)) {
    //       if ($first) {
    //           $sql .= " WHERE ethnicity_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND ethnicity_other = ? ";
    //       }
    //       array_push($args, $ethnicityOther);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_city = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_city = ? ";
    //       }
    //       array_push($args, $artistResidenceCity);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceState)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_state = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_state = ? ";
    //       }
    //       array_push($args, $artistResidenceState);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceProvince)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_province = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_province = ? ";
    //       }
    //       array_push($args, $artistResidenceProvince);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_country = ? ";
    //       }
    //       array_push($args, $artistResidenceCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBirthCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_birth_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_birth_country = ? ";
    //       }
    //       array_push($args, $artistBirthCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBiography)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography = ? ";
    //       }
    //       array_push($args, $artistBiography);
    //   }
    //   if (!IsNullOrEmpty($artistBiographyText)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography_text = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography_text = ? ";
    //       }
    //       array_push($args, $artistBiographyText);
    //   }
    //   if (!IsNullOrEmpty($artistPhotoPath)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_photo_path = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_photo_path = ? ";
    //       }
    //       array_push($args, $artistPhotoPath);
    //   }
    //   if (!IsNullOrEmpty($artistWebsite)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_website = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_website = ? ";
    //       }
    //       array_push($args, $artistWebsite);
    //   }
    //   $json['SQL'] = $sql;
    //   try {
    //       $statement = $conn->prepare($sql);
    //       $statement->setFetchMode(PDO::FETCH_ASSOC);
    //       $statement->execute($args);
    //       $result = $statement->fetchAll();
    //   } catch (Exception $e) {
    //       $json['Exception'] =  $e->getMessage();
    //   }
    //
    //   $conn2 = getDbConnection();
    //   foreach ($result as $row1) {
    //       $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_genres'] = $sql;
    //       try {
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['genres'][] = $row2;
    //       }
    //
    //
    //       $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_works'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['works'][] = $row2;
    //       }
    //       $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_education'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_education'][] = $row2;
    //       }
    //       $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_relation'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_relation'][] = $row2;
    //       }
    //       $json['artist_profile'][] = $row1;
    //       // $json['Status'] = "SUCCESS";
    //   }
    //
    //   // Charul: Implementing Same as in getcompleteartistprofile
    // }
    //
    // elseif ($action == "getArtistProfilesByMajor") {
    //   // code...
    //   $args = array();
    //   $id_received = $decoded_params['artist_attribute']; // THis is an Array containing only one element
    //
    //   $sql = "SELECT * FROM artist_education where (major = '$id_received')";
    //
    //
    //   $first = true;
    //   if (!IsNullOrEmpty($source_page)) {
    //       if ($first) {
    //           $sql .= " WHERE STATUS = 100 ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND STATUS = 100 ";
    //       }
    //   }
    //
    //   if (!IsNullOrEmpty($isUserArtist)) {
    //       if ($first) {
    //           $sql .= " WHERE is_user_artist = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND is_user_artist = ? ";
    //       }
    //       array_push($args, $isUserArtist);
    //   }
    //   if (!IsNullOrEmpty($profileName)) {
    //       if ($first) {
    //           $sql .= " WHERE profile_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND profile_name = ? ";
    //       }
    //       array_push($args, $profileName);
    //   }
    //   if (!IsNullOrEmpty($artistFirstName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_first_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_first_name = ? ";
    //       }
    //       array_push($args, $artistFirstName);
    //   }
    //   if (!IsNullOrEmpty($artistLastName)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_last_name = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_last_name = ? ";
    //       }
    //       array_push($args, $artistLastName);
    //   }
    //   if (!IsNullOrEmpty($artistEmailAddress)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_email_address = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_email_address = ? ";
    //       }
    //       array_push($args, $artistEmailAddress);
    //   }
    //   if (!IsNullOrEmpty($artistLivingStatus)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_living_status = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_living_status = ? ";
    //       }
    //       array_push($args, $artistLivingStatus);
    //   }
    //   if (!IsNullOrEmpty($artistYob)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_yob = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_yob = ? ";
    //       }
    //       array_push($args, $artistYob);
    //   }
    //   if (!IsNullOrEmpty($artistDod)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_dod = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_dod = ? ";
    //       }
    //       array_push($args, $artistDod);
    //   }
    //   if (!IsNullOrEmpty($artistGenre)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_genre = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_genre = ? ";
    //       }
    //       array_push($args, $artistGenre);
    //   }
    //   if (!IsNullOrEmpty($artistEthnicity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_ethnicity = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_ethnicity = ? ";
    //       }
    //       array_push($args, $artistEthnicity);
    //   }
    //   if (!IsNullOrEmpty($artistGender)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_gender = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_gender = ? ";
    //       }
    //       array_push($args, $artistGender);
    //   }
    //   if (!IsNullOrEmpty($genderOther)) {
    //       if ($first) {
    //           $sql .= " WHERE gender_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND gender_other = ? ";
    //       }
    //       array_push($args, $genderOther);
    //   }
    //   if (!IsNullOrEmpty($genreOther)) {
    //       if ($first) {
    //           $sql .= " WHERE genre_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND genre_other = ? ";
    //       }
    //       array_push($args, $genreOther);
    //   }
    //   if (!IsNullOrEmpty($ethnicityOther)) {
    //       if ($first) {
    //           $sql .= " WHERE ethnicity_other = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND ethnicity_other = ? ";
    //       }
    //       array_push($args, $ethnicityOther);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCity)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_city = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_city = ? ";
    //       }
    //       array_push($args, $artistResidenceCity);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceState)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_state = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_state = ? ";
    //       }
    //       array_push($args, $artistResidenceState);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceProvince)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_province = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_province = ? ";
    //       }
    //       array_push($args, $artistResidenceProvince);
    //   }
    //   if (!IsNullOrEmpty($artistResidenceCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_residence_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_residence_country = ? ";
    //       }
    //       array_push($args, $artistResidenceCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBirthCountry)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_birth_country = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_birth_country = ? ";
    //       }
    //       array_push($args, $artistBirthCountry);
    //   }
    //   if (!IsNullOrEmpty($artistBiography)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography = ? ";
    //       }
    //       array_push($args, $artistBiography);
    //   }
    //   if (!IsNullOrEmpty($artistBiographyText)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_biography_text = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_biography_text = ? ";
    //       }
    //       array_push($args, $artistBiographyText);
    //   }
    //   if (!IsNullOrEmpty($artistPhotoPath)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_photo_path = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_photo_path = ? ";
    //       }
    //       array_push($args, $artistPhotoPath);
    //   }
    //   if (!IsNullOrEmpty($artistWebsite)) {
    //       if ($first) {
    //           $sql .= " WHERE artist_website = ? ";
    //           $first = false;
    //       } else {
    //           $sql .= " AND artist_website = ? ";
    //       }
    //       array_push($args, $artistWebsite);
    //   }
    //   $json['SQL'] = $sql;
    //   try {
    //       $statement = $conn->prepare($sql);
    //       $statement->setFetchMode(PDO::FETCH_ASSOC);
    //       $statement->execute($args);
    //       $result = $statement->fetchAll();
    //   } catch (Exception $e) {
    //       $json['Exception'] =  $e->getMessage();
    //   }
    //
    //   $conn2 = getDbConnection();
    //   foreach ($result as $row1) {
    //       $sql = "SELECT genres.* FROM artist_profile, artist_genres, genres WHERE artist_profile.artist_profile_id = artist_genres.artist_profile_id AND artist_genres.genre_id = genres.genre_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_genres'] = $sql;
    //       try {
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['genres'][] = $row2;
    //       }
    //
    //
    //       $sql = "SELECT works.* ,artist_works.involvement FROM artist_profile, artist_works, works WHERE artist_profile.artist_profile_id = artist_works.artist_profile_id AND artist_works.work_id = works.work_id AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_works'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['works'][] = $row2;
    //       }
    //       $sql = "SELECT artist_education.* FROM artist_profile, artist_education WHERE artist_profile.artist_profile_id = artist_education.artist_profile_id AND artist_education.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_education'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_education'][] = $row2;
    //       }
    //       $sql = "SELECT artist_relation.* FROM artist_profile, artist_relation WHERE artist_profile.artist_profile_id = artist_relation.artist_profile_id_1 AND artist_profile.artist_profile_id = ".$row1['artist_profile_id'];
    //       $json['SQL artist_relation'] = $sql;
    //       try {
    //           //$conn2 = getDbConnection();
    //           $statement2 = $conn2->prepare($sql);
    //           $statement2->setFetchMode(PDO::FETCH_ASSOC);
    //           $statement2->execute();
    //           $result2 = $statement2->fetchAll();
    //       } catch (Exception $e) {
    //           $json['Exception'] =  $e->getMessage();
    //       }
    //       foreach ($result2 as $row2) {
    //           $row1['artist_relation'][] = $row2;
    //       }
    //       $json['artist_profile'][] = $row1;
    //       // $json['Status'] = "SUCCESS";
    //   }
    //
    //   // Charul: Implementing Same as in getcompleteartistprofile
    // }

    elseif ($action == "getArtistsOnly") {
        $args = array();
        $sql = "SELECT distinct artist_profile_id, is_user_artist from artist_profile";
        if (!IsNullOrEmpty($source_page)) {
            $sql .= " WHERE STATUS = 100 ";
        }
        $json['SQL'] = $sql;
        try {
            $statement = $conn->prepare($sql);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute($args);
            $result = $statement->fetchAll();
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
        foreach ($result as $row1) {
            $json['artists'][] = $row1;
        }
    } else if ($action == "filterSearchByPid") {


        $selectedPids = arrayToSql($decoded_params["artist_profile_id"]);

        unset($decoded_params["artist_profile_id"]);
        unset($decoded_params["action"]);

        $condtionsSql = applyConditions($decoded_params);

        $condtionsSql2 = "";
        if (strlen($condtionsSql) > 0) {
            $condtionsSql2 = "AND " . $condtionsSql;
            $condtionsSql = "WHERE " . $condtionsSql;
        }
        $FrontEndProfileData = "artist_gender,artist_genre,artist_first_name,artist_last_name,artist_residence_city,artist_residence_country,artist_residence_state,is_user_artist";

        $all_node = "SELECT artist_profile_id_2 AS nid FROM artist_profile
									INNER JOIN artist_relation
									ON artist_profile_id_1=artist_profile_id
									WHERE artist_profile_id IN $selectedPids 
									UNION 
									SELECT artist_profile_id_1 AS nid  FROM artist_profile
									INNER JOIN artist_relation
									ON artist_profile_id_2=artist_profile_id
									WHERE artist_profile_id IN $selectedPids 
									UNION 
									SELECT artist_profile_id AS nid FROM artist_profile
									WHERE artist_profile_id IN $selectedPids";

        $sql = "SELECT nid,artist_relation.relation_id, artist_relation,artist_profile_id_1,artist_profile_id_2, $FrontEndProfileData FROM(
                    SELECT * FROM(
                                SELECT nid,artist_profile_id_2 AS mate,relation_id FROM ($all_node) AS all_node
                                INNER JOIN artist_relation
                                ON nid=artist_profile_id_1
                                UNION 
                                SELECT nid,artist_profile_id_1 AS mate,relation_id FROM ($all_node) AS all_node
                                INNER JOIN artist_relation
                                ON nid=artist_profile_id_2) AS r
                    WHERE mate IN (SELECT artist_profile_id FROM artist_profile  $condtionsSql) AND nid IN (SELECT artist_profile_id FROM artist_profile  $condtionsSql) AND mate IN ($all_node)				
                    UNION 
                    SELECT artist_profile_id,NULL AS mate, NULL AS relation_id  FROM artist_profile WHERE artist_profile_id IN ($all_node)  $condtionsSql2 
                    ) AS result 
                INNER JOIN artist_profile ON nid=artist_profile_id 
                LEFT JOIN artist_relation ON artist_relation.relation_id=result.relation_id";

        // $conn = getDbConnection();
        //$json['SQL'] = $sql;
        try {
            $result = $conn->query($sql);
            $json["result"] = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
    } elseif ($action == "filterSearchForALL") {
        unset($decoded_params["artist_profile_id"]);
        unset($decoded_params["action"]);

        $condtionsSql = applyConditions($decoded_params);
        if (strlen($condtionsSql) > 0) {
            $condtionsSql = "WHERE " . $condtionsSql;
        }
        $all_node = "SELECT artist_profile_id_2 AS mate 
                    FROM artist_profile 
                    INNER JOIN artist_relation
                    ON artist_profile_id=artist_profile_id_1 
                    $condtionsSql
                    UNION
                    SELECT artist_profile_id_1
                    FROM artist_profile 
                    INNER JOIN artist_relation
                    ON artist_profile_id=artist_profile_id_2
                    $condtionsSql
                    UNION 
                    SELECT artist_profile_id  
                    FROM artist_profile
                    $condtionsSql";
        $mainNodeCount = "SELECT COUNT(*) AS nodeCount FROM ( SELECT artist_profile_id FROM artist_profile $condtionsSql) AS C";
        $sql = "SELECT * FROM(
             SELECT mate AS nid,artist_relation.* FROM ($all_node
			 ) AS B LEFT JOIN artist_relation
			 ON (artist_profile_id_1=mate AND artist_profile_id_2 IN ($all_node )  ) 
										OR (artist_profile_id_2=mate AND artist_profile_id_1 IN ($all_node)  ) OR (artist_relation.relation_id IS NULL) ) AS result 
		
            INNER JOIN artist_profile ON nid=artist_profile_id
            LEFT JOIN network_cache ON pid=artist_profile_id
			";





        try {
            $result = $conn->query($sql);
            $json["result"] = $result->fetchAll(PDO::FETCH_ASSOC);
            $json['mainNodeCount'] = ($conn->query($mainNodeCount))->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
    } else if ($action == "centerSearchById") {
        $selectedPids = arrayToSql($decoded_params["artist_profile_id"]);
        unset($decoded_params["artist_profile_id"]);
        unset($decoded_params["action"]);
        $condtionsSql = applyConditions($decoded_params);
        $condtionsSql2 = "";
        if (strlen($condtionsSql) > 0) {
            $condtionsSql2 = "AND " . $condtionsSql;
            $condtionsSql = "WHERE " . $condtionsSql;
        }
        $all_node = " SELECT artist_profile_id_2 AS mate 
                    FROM artist_profile 
                    INNER JOIN artist_relation
                    ON artist_profile_id=artist_profile_id_1 
                    WHERE artist_profile_id IN $selectedPids $condtionsSql2
                    UNION
                    SELECT artist_profile_id_1
                    FROM artist_profile 
                    INNER JOIN artist_relation
                    ON artist_profile_id=artist_profile_id_2
                    WHERE artist_profile_id IN $selectedPids $condtionsSql2
                    UNION 
                    SELECT artist_profile_id   FROM artist_profile
                    WHERE artist_profile_id IN $selectedPids $condtionsSql2";
        $mainNodeCount = "SELECT COUNT(*) AS nodeCount FROM (SELECT artist_profile_id  FROM artist_profile
                                                WHERE artist_profile_id IN $selectedPids $condtionsSql2) AS C";

        $sql = "SELECT * FROM(
            SELECT mate AS nid,artist_relation.relation_id FROM ($all_node

			 ) AS B INNER JOIN artist_relation
			 ON (artist_profile_id_1=mate AND artist_profile_id_2 IN ($all_node )  )
										OR (artist_profile_id_2=mate AND artist_profile_id_1 IN ($all_node)  )
	   UNION
       SELECT artist_profile_id AS nid,null  FROM artist_profile
       WHERE artist_profile_id IN $selectedPids $condtionsSql2) AS result INNER JOIN artist_profile ON nid=artist_profile_id
       LEFT JOIN artist_relation ON artist_relation.relation_id=result.relation_id
       LEFT JOIN network_cache ON nid=pid";
        //$conn = getDbConnection();
        try {
            $result = $conn->query($sql);
            $json["result"] = $result->fetchAll(PDO::FETCH_ASSOC);
            $json['mainNodeCount'] = ($conn->query($mainNodeCount))->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $json['Exception'] = $e->getMessage();
        }
    } else {
        $json['Exception'] = "Unrecognized Action ";
    }
} else {
    $json['Exception'] = "Invalid JSON on Inbound Request";
}
echo json_encode($json);
closeConnections();
/*
 * Helper function for searching network data
 *
 * */
function applyConditions($conditions)
{
    if (count($conditions) == 0) {
        return "";
    }
    $sql_conditions = "";
    foreach ($conditions as $key => $values) {
        if (count($values) != 0) {
            switch ($key) {
                case "artist_genre";
                    $sql_conditions = stringMatch("artist_genre", $values, $sql_conditions);
                    break;
                case "artist_residence_city":
                    $sql_conditions = stringMatch("artist_residence_city", $values, $sql_conditions);
                    break;
                case "artist_residence_country":
                    $sql_conditions = stringMatch("artist_residence_country", $values, $sql_conditions);
                    break;
                case "genre":
                    $sql_conditions = StringCommaNumberMatch("genre", $values, $sql_conditions);
                    break;
                case "artist_residence_country":
                    $sql_arr = arrayToSql($values);
                    $sql_conditions = $sql_conditions . "(artist_residence_country IN {$sql_arr}) AND";
                    break;
                case "artist_ethnicity":
                    $sql_arr = arrayToSql($values);
                    $sql_conditions = $sql_conditions . "(artist_ethnicity IN {$sql_arr}) AND";
                    break;
                case "artist_residence_state":
                    $sql_conditions = stringMatch("artist_residence_state", $values, $sql_conditions);
                    break;
                case "artist_gender":
                    $sql_arr = arrayToSql($values);
                    $sql_conditions = $sql_conditions . "(artist_gender IN {$sql_arr}) AND";
                    break;
            }
        }
    }
    //remove last and
    return substr($sql_conditions, 0, -3);
}

function StringCommaNumberMatch($key, $values, $sql_conditions)
{
    $sql_conditions = $sql_conditions . "({$key} REGEXP ',$values[0],|^$values[0],|,$values[0]$ |^$values[0]$'";
    $len = count($values);
    for ($i = 1; $i < $len; $i++) {
        $sql_conditions = $sql_conditions . "{$key} REGEXP ',$values[0],|^$values[0],|,$values[0]$ |^$values[0]$' ";
    }
    return $sql_conditions . ") AND";
}

function stringMatch($key, $values, $sql_conditions)
{
    $sql_conditions = $sql_conditions . "({$key} LIKE '%{$values[0]}%' ";
    $len = count($values);
    for ($i = 1; $i < $len; $i++) {
        $sql_conditions = $sql_conditions . " OR {$key} LIKE '%{$values[$i]}%' ";
    }
    return $sql_conditions . ") AND";
}

function arrayToSql(array $array)
{
    $sqlArray = json_encode($array);
    $sqlArray = str_replace('[', '(', $sqlArray);
    $sqlArray = str_replace(']', ')', $sqlArray);
    return $sqlArray;
}
