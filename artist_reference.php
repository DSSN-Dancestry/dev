<?php
require_once 'util.php';

my_session_start();

if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["user_email_address"])) {
    $artist_profile_id = $_SESSION["artist_profile_id"];
    $user_email_address = $_SESSION["user_email_address"];
}
if (isset($_POST["artist_reference"])) {
    require 'connect.php';
    $conn = getDbConnection();

    $reference_details = $_POST["artist_reference"];

    $query = "UPDATE artist_profile
		SET reference_details = ?
		WHERE artist_profile_id=?";
    try {
        $statement = $conn->prepare($query);
        $statement->execute([$reference_details,$_SESSION["artist_profile_id"] ]);
        $count = $statement->rowCount();
        $_SESSION["reference_details"] = $reference_details;

        echo " &nbsp; &nbsp; &nbsp; Your Artist Reference has been saved.";
    } catch (Exception $e) {
        echo  "Error updaing profile ".$e->getMessage();
    }
    closeConnections();
}
