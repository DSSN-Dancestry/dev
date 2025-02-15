<?php
include 'util.php';
include 'connection_open.php';
my_session_start();
$statusMsg = '';

$comment=$_POST['comment'];
$category=$_POST['category'];
$severity=$_POST['severity'];
$id = $_GET['id'];
//echo $category;
   

if (!empty($comment)){
    $insert = $dbc->query("INSERT into bug_comments (id, uploaded_on, comment)
    VALUES ('".$id."', NOW(), '".addslashes($comment)."')");

    $user = $_SESSION["user_email_address"];
    //echo "$user";
    $insert = $dbc->query("update bugs set assigned_to='".$user."' where id='".$id."'");
    
}


    // Insert image file name into database
    
    
    $insert = $dbc->query("update bugs set category='".$category."', severity='".$severity."' where id='".$id."'");
    


if($insert){
    //$message = "Comment added";
    //echo $message;
    echo "<script type='text/javascript'> 
    var url = 'issue.php';
    url += '?id=$id';
    window.location.href = url;
    </script>";
}else{
    $message = "Comment not added!";
    echo "<script type='text/javascript'>alert('$message'); 
    var url = 'issue.php';
    url += '?id=$id';
    window.location.href = url;
    </script>";
    //echo $message;
} 



// Display status message

?>

