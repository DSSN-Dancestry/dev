<?php
    include 'connection_open.php';
    $query_get_profile = "select * from artist_profile where artist_email_address = ''";
    $result_get_profile = mysqli_query($dbc,$query_get_profile) or die('Error querying database.: ' . mysqli_error());

    while ($resultant_get_profile = mysqli_fetch_array($result_get_profile)) {
        $newemail = 'dummyemail@'. $resultant_get_profile['artist_first_name']. $resultant_get_profile['artist_last_name'];
        $newemail = preg_replace('/\s+/', '', $newemail);
        $newemail = preg_replace('/[^A-Za-z0-9@\-]/', '', $newemail);
        // Artist profile
        $query_artist_profile = "update artist_profile
        set artist_email_address = '".$newemail."' where artist_profile_id = ".$resultant_get_profile['artist_profile_id'];
        $result_artist_profile = mysqli_query($dbc,$query_artist_profile) or die('Error querying database.: ' . mysqli_error($dbc));

        // Artist relation
        $query_artist_relation = "update artist_relation
        set artist_email_id_2 = '".$newemail."' where artist_profile_id_2 = ".$resultant_get_profile['artist_profile_id'];
        $result_artist_relation = mysqli_query($dbc,$query_artist_relation) or die('Error querying database.: ' . mysqli_error($dbc));
    }
    include 'connection_close.php';
?>