<?php
include 'util.php';

my_session_start();

require 'connect.php';
include 'connection_open.php';
$conn = getDbConnection();

$platform = $_POST['platform'];
$artist_profile_id = $_SESSION["artist_profile_id"];

// check if social media already exists
$query = "SELECT * FROM artist_social
WHERE artist_profile_id='$artist_profile_id' AND social_platform='$platform'";
$result = mysqli_query($dbc, $query)
or die('Error querying database.: '  .mysqli_error($dbc));
$count=mysqli_num_rows($result);

// if profile exists then remove social media profile
if ($count!=0) {
    // facebook
    if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["is_facebook_linked"]) and $_SESSION["is_facebook_linked"] and $platform == 'Facebook') {
        $query = "DELETE FROM artist_social
                    WHERE artist_profile_id=? AND social_platform=?";
        $statement = $conn->prepare($query);
        $statement->execute([$artist_profile_id, $platform]);
        $_SESSION["is_facebook_linked"] = False;
    }
    // Instagram
    else if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["is_instagram_linked"]) and $_SESSION["is_instagram_linked"] and $platform == 'Instagram') {
        $query = "DELETE FROM artist_social
                    WHERE artist_profile_id=? AND social_platform=?";
        $statement = $conn->prepare($query);
        $statement->execute([$artist_profile_id, $platform]);
        $_SESSION["is_instagram_linked"] = False;
    }
}

closeConnections();
