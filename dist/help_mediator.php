<?php
include 'util.php';
include 'connection_open.php';

$statusMsg = '';

$name=$_POST['name'];
$visitor_email=$_POST['mail'];
$content=$_POST['content'];
$title = $_POST['title'];

// File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $visitor_email . '-' . $title . '-' .$fileName;;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);


$imageName= $visitor_email . '-' . $title . '-' .$fileName;

if(isset($_POST["submit"]) || !empty($_FILES["file"]["name"])){

    if ($_FILES["file"]["size"]> 1000000){
        echo '<script type="text/javascript">
                  alert("File Uploaded Successfully");
                  window.location.href = "help.php";
                  </script>';
    }

    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg', 'bmp','JPG','PNG','JPEG','BMP');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = $dbc->query("INSERT into images (file_name, uploaded_on, user_email_id, user_name, user_comment, issue_title)
             VALUES ('".$imageName."', NOW(), '".$visitor_email."', '".$name."', '".$content."', '".$title."')");
            if($insert){
              $message = "The file ".$fileName. " has been uploaded successfully.";
              echo '<script type="text/javascript">
              alert("File Uploaded Successfully");
              window.location.href = "index.php";
              </script>';
                //$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            echo '<script type="text/javascript">
                  alert("File Size greater than 2MB");
                  window.location.href = "help.php";
                  </script>';
        }
    }else{
        $insert = $dbc->query("INSERT into images (uploaded_on, user_email_id, user_name, user_comment, issue_title)
             VALUES (NOW(), '".$visitor_email."', '".$name."', '".$content."', '".$title."')");
            if($insert){
              $message = "The query has been uploaded successfully.";
              echo '<script type="text/javascript">
              alert("File Uploaded Successfully");
              window.location.href = "index.php";
              </script>';
                //$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            }
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
echo $statusMsg;
?>

