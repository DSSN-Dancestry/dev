<?php
include 'util.php';
include 'connection_open.php';

$statusMsg = '';

$name = $_POST['name'];
$visitor_email = $_POST['mail'];
$content = $_POST['content'];
$title = $_POST['title'];


// File upload path
$targetDir = "upload/";
$fileName = basename($_FILES["file"]["name"]);
$filesize = $_FILES["file"]["size"];
// $targetFilePath = $targetDir . $visitor_email . '-' . $title . '-' . $fileName;;
$targetFilePath = "upload/bugs_upload_data/" . $fileName;


$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);


$imageName = $visitor_email . '-' . $title . '-' . $fileName;

if (isset($_POST["submit"])) {

    // if ($_FILES["file"]["size"] < 2097152 && $_FILES["file"]["size"] > 1) {
    //     echo '<script type="text/javascript">
    //               alert("File Uploaded Successfully");
    //               </script>';
    // }

    // Allow certain file formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'bmp', 'JPG', 'PNG', 'JPEG', 'BMP');
    if (in_array($fileType, $allowTypes)) {
        // Upload file to server

        move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {

            $maxsize = 2 * 1024 * 1024;
            if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

            // Insert image file name into database
            $insert = $dbc->query("INSERT into bugs (file_name, uploaded_on, user_email_id, user_name, user_comment, issue_title)
             VALUES ('" . $imageName . "', NOW(), '" . $visitor_email . "', '" . $name . "', '" . addslashes($content) . "', '" . addslashes($title) . "')");
            if ($insert) {
                $message = "The file " . $fileName . " has been uploaded successfully.";
                echo '<script type="text/javascript">window.location.href = "thanks.php";</script>';
            } else {
                $statusMsg = "File upload failed, please try again.";
            }
        } else {
            $x = $_FILES["file"]["tmp_name"];
            echo "<script type='text/javascript'>
                alert('File Size greater than 2MB$x');
                alert('File 2MB$targetFilePath');
                var url = 'help.php';
                url += '?name=$name';
                url += '&email=$visitor_email';
                url += '&title=$title';                
                url += '&content=$content';
                
                window.location.href = url;
                </script>";
        }
    } else {

        $insert = $dbc->query("INSERT into bugs (uploaded_on, user_email_id, user_name, user_comment, issue_title)
        VALUES (NOW(), '" . $visitor_email . "', '" . $name . "', '" . addslashes($content) . "', '" . addslashes($title) . "')");

        if ($insert && $dbc->affected_rows > 0) {
            $message = "The query has been uploaded successfully.";
        } else {
            $message = "Query execution failed: " . $dbc->error;
        }

        echo '<script type="text/javascript">window.location.href = "thanks.php";</script>';
    }
}

// Display status message
echo $statusMsg;
