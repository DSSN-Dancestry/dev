<?php
include 'util.php';

my_session_start();

require 'connect.php';
$conn = getDbConnection();

if (isset($_SESSION["artist_profile_id"]) and isset($_SESSION["user_email_address"])) {
    $artist_profile_id = $_SESSION["artist_profile_id"];
    $user_email_address = $_SESSION["user_email_address"];
}
if (isset($_POST["biography_text"])) {
    

    $biography_text = $_POST["biography_text"];
    $_SESSION["biography_text"] = $biography_text;

    $query = "UPDATE artist_profile
        SET artist_biography_text = ?
        WHERE artist_profile_id=?";

    try {
        $statement = $conn->prepare($query);
        $statement->execute([$biography_text,$_SESSION["artist_profile_id"] ]);
        $count = $statement->rowCount();

        echo "Your biography has been saved.";
    } catch (Exception $e) {
        echo  "Error updaing profile ".$e->getMessage();
    }
}
else if (isset($_FILES["bio_file_add"]["type"])) {
    $validextensions = array("pdf", "docx", "doc");
    $temporary = explode(".", $_FILES["bio_file_add"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["bio_file_add"]["type"] == "application/pdf") || ($_FILES["bio_file_add"]["type"] == "application/msword") || ($_FILES["bio_file_add"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"))
        && ($_FILES["bio_file_add"]["size"] < 4194304)//Approx. 100kb files can be uploaded.
        && in_array($file_extension, $validextensions)) {
        if ($_FILES["bio_file_add"]["error"] > 0) {
            echo "Return Code: " . $_FILES["bio_file_add"]["error"] . "<br/><br/>";
        } else {
            if (file_exists("upload/biography_upload_data/" . $_FILES["bio_file_add"]["name"])) {
                echo $_FILES["bio_file_add"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
            } else {
                $sourcePath = $_FILES['bio_file_add']['tmp_name']; // Storing source path of the file in a variable
                $timestamp = time();
                $targetPath = "upload/biography_upload_data/".$timestamp.$_FILES['bio_file_add']['name']; // Target path where file is to be stored
                $_SESSION["biography_file_path"] = $targetPath;
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    $query = "UPDATE artist_profile
                                        SET artist_biography = ?
                                        WHERE artist_profile_id=?";

                    try {
                        $statement = $conn->prepare($query);
                        $statement->execute([$targetPath,$_SESSION["artist_profile_id"] ]);
                        $count = $statement->rowCount();

                        echo "<span id='success'>Biography Uploaded Successfully...!!</span><br/>";
                    } catch (Exception $e) {
                        echo  "Error updaing profile ".$e->getMessage();
                    }
                }
                else {
                    echo "<span id='invalid'>**Please try again later***<span>";
                }
            }
        }
    } else {
        echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}
else if(isset($_POST['action']) && ($_POST['action'] == 'delete')){
    $query = "UPDATE artist_profile
                        SET ".$_POST['field']." = ''
                        WHERE artist_profile_id= '".$_SESSION["artist_profile_id"]."';";
    try {
        $_SESSION["biography_file_path"] = "";
        $statement = $conn->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();
        echo "<span id='success'>Document Removed Successfully...!!</span><br/>";
    } catch (Exception $e) {
        echo  "Error removing document ".$e->getMessage();
    }
}
closeConnections();