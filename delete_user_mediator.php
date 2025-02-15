<?php
    include 'util.php';
    my_session_start();
    

    if (isset($_POST['artist_profile_delete'])) {
        $artist_profile_id = $_POST['artist_profile_delete'];
        include 'connection_open.php';
        // $query_user_profile = "DELETE FROM user_profile WHERE user_email_address in (select artist_email_address from artist_profile where artist_profile_id='$artist_profile_id')";
        $query_artist_profile = "DELETE FROM artist_profile WHERE artist_profile_id='$artist_profile_id'";
        $query_artist_relation = "DELETE FROM artist_relation WHERE artist_profile_id_1='$artist_profile_id' or artist_profile_id_2='$artist_profile_id'";
        $query_artist_education = "DELETE FROM artist_education WHERE artist_profile_id='$artist_profile_id'";

        // $result_user_profile = mysqli_query($dbc,$query_user_profile) or die('Error querying database.: '  .mysqli_error($dbc));
        $result_artist_profile = mysqli_query($dbc, $query_artist_profile) or die('Error querying database.: '  .mysqli_error($dbc));
        $result_artist_relation = mysqli_query($dbc, $query_artist_relation) or die('Error querying database.: '  .mysqli_error($dbc));
        $result_artist_education = mysqli_query($dbc, $query_artist_education) or die('Error querying database.: '  .mysqli_error($dbc));

        include 'connection_close.php';
        $location = "delete_user.php";
    }
    echo("<script>location.href='$location'</script>");?>
?>
