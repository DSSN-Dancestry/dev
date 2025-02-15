<?php
include 'util.php';

my_session_start();

require 'connect.php';
include 'connection_open.php';
$conn = getDbConnection();

$platform = $_POST['platform'];
$link_url = $_POST['link_url'];
$artist_profile_id = $_SESSION["artist_profile_id"];

// check if social media already exists
$query = "SELECT * FROM artist_social
WHERE artist_profile_id='$artist_profile_id' AND social_platform='$platform'";
$result = mysqli_query($dbc, $query)
or die('Error querying database.: '  .mysqli_error($dbc));
$count=mysqli_num_rows($result);

// if profile doesnot exist then add social media profile else update it
if ($count==0) {
    if (isset($_SESSION["artist_profile_id"])) {
        $query = "INSERT INTO artist_social (artist_profile_id, social_platform, url) VALUES (? ,?, ?)";
        $statement = $conn->prepare($query);
        $statement->execute([$artist_profile_id, $platform, $link_url]);
    }
}
else{
    if (isset($_SESSION["artist_profile_id"])) {
        $query = "UPDATE artist_social
                    SET url = ?
                    WHERE artist_profile_id=? AND social_platform=?";
        $statement = $conn->prepare($query);
        $statement->execute([$link_url, $artist_profile_id, $platform]);
    }
}

if ($platform == 'Facebook'){
    $_SESSION["is_facebook_linked"] = True;
}
else if ($platform == 'Instagram'){
    $_SESSION["is_instagram_linked"] = True;
}
closeConnections();
