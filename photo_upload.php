<?php
include 'util.php';

my_session_start();

require 'connect.php';
$conn = getDbConnection();

if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["user_email_address"])) {
    $artist_profile_id = $_SESSION["artist_profile_id"];
    //echo $artist_profile_id;
    $user_email_address = $_SESSION["user_email_address"];
    //echo $user_email_address;
}

// check if add or delete
if (isset($_FILES["image_file_add"]["type"])) {
    $validextensions = array("jpeg", "jpg", "png", "JPEG", "JPG", "PNG");
    $temporary = explode(".", $_FILES["image_file_add"]["name"]);
    error_log($_FILES["image_file_add"]["name"]);
    $file_extension = end($temporary);

    if ((($_FILES["image_file_add"]["type"] == "image/png") || ($_FILES["image_file_add"]["type"] == "image/jpg") || ($_FILES["image_file_add"]["type"] == "image/jpeg"))
        && ($_FILES["image_file_add"]["size"] < 10194304) //Approx. 10MB files can be uploaded.

        && in_array($file_extension, $validextensions)
    ) {
        if ($_FILES["image_file_add"]["error"] > 0) {
            echo "Return Code: " . $_FILES["image_file_add"]["error"] . "<br/><br/>";
        } else {
            if (file_exists("upload/photo_upload_data/" . $_FILES["image_file_add"]["name"])) {
                echo $_FILES["image_file_add"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
            } else {
                $sourcePath = $_FILES['image_file_add']['tmp_name'];
                $timestamp = time(); // Storing source path of the file in a variable
                $targetPath = "upload/photo_upload_data/" . $timestamp . $_FILES['image_file_add']['name']; // Target path where file is to be stored
                // echo("<script>console.log('Target: ".$targetPath."');</script>");
                // echo("<script>console.log('Source: ".$sourcePath."');</script>");
                $_SESSION["photo_file_path"] = $targetPath;

                if (move_uploaded_file($sourcePath, $targetPath)) { // Moving Uploaded file
                    $query = "UPDATE artist_profile
                                        SET artist_photo_path = ?
                                        WHERE artist_profile_id=?";

                    try {
                        $statement = $conn->prepare($query);
                        $statement->execute([$targetPath, $_SESSION["artist_profile_id"]]);
                        $count = $statement->rowCount();
                        #echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";              
                        echo $targetPath;
                    } catch (Exception $e) {
                        echo  "Error uploading profile photo " . $e->getMessage();
                    }
                } else {
                    echo "<span id='invalid'>**Some problem occurred please try again later***<span>";
                }
            }
        }
    } else {
        echo "<span id='invalid'>***Invalid file Size or Type*** TYPE " . $_FILES["image_file_add"]["type"] . " SIZE " . $_FILES["image_file_add"]["size"] . " EXTENSION " . $file_extension . " name " . $_FILES["image_file_add"]["name"] . print_r($_FILES) . "<span>";
    }
} else {
    $query = "UPDATE artist_profile
                        SET artist_photo_path = ''
                        WHERE artist_profile_id=?";
    try {
        $_SESSION["photo_file_path"] = "";
        $statement = $conn->prepare($query);
        $statement->execute([$_SESSION["artist_profile_id"]]);
        $count = $statement->rowCount();
        echo "<span id='success'>Image Removed Successfully...!!</span><br/>";
    } catch (Exception $e) {
        echo  "Error removing profile photo " . $e->getMessage();
    }
}
closeConnections();
