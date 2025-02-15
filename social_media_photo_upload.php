<?php
include 'util.php';

my_session_start();

require 'connect.php';
$conn = getDbConnection();

$photo_url = $_POST['photo_url'];

if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["user_email_address"])) {
    $artist_profile_id = $_SESSION["artist_profile_id"];
    $user_email_address = $_SESSION["user_email_address"];

    // push the photo to db
    $query = "UPDATE artist_profile
                SET artist_photo_path = ?
                WHERE artist_profile_id=?";
    $statement = $conn->prepare($query);
    $statement->execute([$photo_url, $artist_profile_id]);
}

closeConnections();
