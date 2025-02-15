<?php

  include 'util.php';
    my_session_start();

    // check that the user is logged in - if not, redirect to login.
    if (!isset($_SESSION["user_email_address"])) {
        header('Location: login.php');
        exit;
    }

  require 'connect.php';



  // this page routes a number of actions related to creating and maintaining
  // profiles.  The action done will depend on which of the post fields
  // is set in the request.

  if (isset($_POST['artist_profile_delete'])) {

      // we're deleting an artist profile.  The unique profile
      // id is passed in the artist_profile_delete param
      $artist_profile_id = $_POST['artist_profile_delete'];

      // TODO : This should be moved to an API call
      $conn = getDbConnection();

      $sql = "DELETE FROM artist_profile WHERE artist_profile_id = ?";

      try {
          $statement = $conn->prepare($sql);
          $statement->execute([$artist_profile_id]);
          $count = $statement->rowCount();
      } catch (Exception $e) {
          error_log($e);
      }

      closeConnections();

      // once we've deleted the profile, route back to the main profiles page.
      $location = "profiles.php";
  } elseif (isset($_POST['artist_profile_add'])) {

      // this is called when a user is entering a profile for an artist
      // other than themselves.  It is called from the "Add Artist"
      // button on the profile.php page

      $_SESSION["user_email_address"] = $_POST['artist_profile_add']; // address of logged in user
      $_SESSION["artist_profile_add"] = "add";
      $_SESSION["timeline_flow"] = "artist_add";
      $_SESSION["artist_profile_id"] = "";
      $_SESSION["contribution_type"] = "other";
      $_SESSION["is_user_artist"] = "other";

      $location = "add_artist_profile.php";

      // clear out all of the profile related session variables, since we
      // are creating a new profile
      unset($_SESSION["artist_first_name"]);
      unset($_SESSION["artist_last_name"]);
      unset($_SESSION["artist_email_address"]);
      unset($_SESSION["artist_status"]);
      unset($_SESSION["date_of_birth"]);
      unset($_SESSION["year_of_birth"]);
      unset($_SESSION["date_of_death"]);
      unset($_SESSION["artist_genre"]);
      unset($_SESSION["other_artist_text_input"]);
      unset($_SESSION["gender"]);
      unset($_SESSION["gender_other"]);
      unset($_SESSION["ethnicity"]);
      unset($_SESSION["ethnicity_other"]);
      unset($_SESSION["city_residence"]);
      unset($_SESSION["country_residence"]);
      unset($_SESSION["state_residence"]);
      unset($_SESSION["state_province"]);
      unset($_SESSION["country_birth"]);
      unset($_SESSION["photo_file_path"]);
      unset($_SESSION["biography_file_path"]);
      unset($_SESSION["biography_text"]);
      unset($_SESSION["genre"]);
      unset($_SESSION["university"]);
      unset($_SESSION["major"]);
      unset($_SESSION["degree"]);
      unset($_SESSION["institution_name"]);
      unset($_SESSION["other_degree"]);
      unset($_SESSION["lineage_artist_first_name"]);
      unset($_SESSION["lineage_artist_last_name"]);
      unset($_SESSION["lineage_artist_email_address"]);
      unset($_SESSION["lineage_artist_website"]);
      unset($_SESSION["studied_start_date"]);
      unset($_SESSION["studied_end_date"]);
      unset($_SESSION["studied_duration_years"]);
      unset($_SESSION["studied_duration_months"]);
      unset($_SESSION["danced_start_date"]);
      unset($_SESSION["danced_end_date"]);
      unset($_SESSION["danced_duration_years"]);
      unset($_SESSION["danced_duration_months"]);
      unset($_SESSION["collaborated_start_date"]);
      unset($_SESSION["collaborated_end_date"]);
      unset($_SESSION["collaborated_duration_years"]);
      unset($_SESSION["collaborated_end_date"]);
      unset($_SESSION["influenced_by"]);
      unset($_SESSION["user_genres"]);
  } elseif (isset($_POST['artist_profile_edit']) || isset($_POST['artist_relation_add'])) {

      // This action is called when a user clicks the "Edit" button on
      // an existing profile - their own or one they have submitted for
      // someone else on the profiles.php page
      if (isset($_POST['artist_relation_add'])) {
          $_SESSION["artist_profile_id"] = $_POST['artist_relation_add'];
          $_SESSION["timeline_flow"] = "add_lineage";
      } else {
          $_SESSION["artist_profile_id"] = $_POST['artist_profile_edit'];
          $_SESSION["timeline_flow"] = "edit";
      }

      $_SESSION["artist_profile_edit"] = "edit";
      $location = "artist_database_retrieval.php";
  } elseif (isset($_POST['artist_profile_view'])) {

      // This action is called when a user clicks the "View" button on
      // an existing profile - their own or one they have submitted for
      // someone else on the profiles.php page
      // TODO : can still add relationships in when in view mode - fix

      $_SESSION["artist_profile_id"] = $_POST['artist_profile_view'];
      $_SESSION["artist_profile_view"] = "view";
      $location = "artist_database_retrieval.php";
      $_SESSION["timeline_flow"] = "view";
  } elseif (isset($_POST['user_contribute_lineage'])) {

      // this action is called when a user is creating their lineage for the
      // first time. It is sent from the "Get Started" button on the
      // profiles.php page.

      $_SESSION["artist_profile_add"] = "add";
      $_SESSION["is_user_artist"] = "own";
      $_SESSION["timeline_flow"] = "artist_add";
      $location = "add_artist_profile.php";
      $_SESSION["artist_profile_id"] = '';
      // $_SESSION["artist_profile_id"] = $_SESSION["user_email_address"];
      unset($_SESSION["artist_first_name"]);
      unset($_SESSION["artist_last_name"]);
      unset($_SESSION["artist_email_address"]);
      unset($_SESSION["artist_status"]);
      $_SESSION["contribution_type"] = "own";
      unset($_SESSION["date_of_birth"]);
      unset($_SESSION["year_of_birth"]);
      unset($_SESSION["date_of_death"]);
      unset($_SESSION["artist_genre"]);
      unset($_SESSION["other_artist_text_input"]);
      unset($_SESSION["gender"]);
      unset($_SESSION["gender_other"]);
      unset($_SESSION["ethnicity"]);
      unset($_SESSION["ethnicity_other"]);
      unset($_SESSION["city_residence"]);
      unset($_SESSION["country_residence"]);
      unset($_SESSION["state_residence"]);
      unset($_SESSION["state_province"]);
      unset($_SESSION["country_birth"]);
      unset($_SESSION["photo_file_path"]);
      unset($_SESSION["biography_file_path"]);
      unset($_SESSION["biography_text"]);
      unset($_SESSION["genre"]);

      unset($_SESSION["university"]);
      unset($_SESSION["major"]);
      unset($_SESSION["degree"]);
      unset($_SESSION["institution_name"]);
      unset($_SESSION["other_degree"]);

      unset($_SESSION["lineage_artist_first_name"]);
      unset($_SESSION["lineage_artist_last_name"]);
      unset($_SESSION["lineage_artist_email_address"]);
      unset($_SESSION["lineage_artist_website"]);
      unset($_SESSION["studied_start_date"]);
      unset($_SESSION["studied_end_date"]);
      unset($_SESSION["studied_duration_years"]);
      unset($_SESSION["studied_duration_months"]);
      unset($_SESSION["danced_start_date"]);
      unset($_SESSION["danced_end_date"]);
      unset($_SESSION["danced_duration_years"]);
      unset($_SESSION["danced_duration_months"]);
      unset($_SESSION["collaborated_start_date"]);
      unset($_SESSION["collaborated_end_date"]);
      unset($_SESSION["collaborated_duration_years"]);
      unset($_SESSION["collaborated_end_date"]);
      unset($_SESSION["influenced_by"]);
  }

    // after doing the setup for the appropriate action, forward
    // the user the relevant page
    echo("<script>location.href='$location'</script>");?>
?>
