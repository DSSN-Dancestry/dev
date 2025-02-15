<?php
include 'util.php';
my_session_start();

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
  $user_email_address=$_SESSION["user_email_address"];
  $user_firstname=$_SESSION["user_firstname"];
  $user_lastname=$_SESSION["user_lastname"];
  $user_name=$user_firstname." ".$user_lastname;
} 
else {
  $user_name="";
  $user_email_address="";
}
if (isset($_GET["title"])) {
  $title = $_GET['title'];
}
else{
  $title = '';
}
if (isset($_GET["content"])) {
  $content = $_GET['content'];
}
else{
  $content = '';
}
?>
<html>
<head>
  <title>Report an issue | Dancestry</title>
	<script src="js/platform.js"></script>
    <script type="text/javascript" src="js/browserCheck.js"></script>
	<script>window.onload=function(){ strict_check();}</script>  
  <style>
    .footer{
      margin-top: 2.9%;
    }
  </style>

</head>
<div class="container" style="padding-left:30px;padding-right:30px;">
  <div style="text-align:center">
    <h4>Please tell us about the issue you are having</h4>
  </div>
  <div class="row"><p align="right"><font size="2" color="red"><I>* marked fields are mandatory.</I></font></div>
  <div class="row">
      <form action="help_mediator.php" method="post" enctype="multipart/form-data">
        <label for="fname">Name<font size="3" color="red">*</font></label>
        <input type="text" id="name" name="name" placeholder="Your name" value="<?php echo $user_name?>" required>
        <label for="lname">Email<font size="3" color="red">*</font></label>
        <input type="Email" id="mail" name="mail" placeholder="Your Email-ID" value="<?php echo $user_email_address?>" required>
        <label for="issuetitle">What is the issue about?<font size="3" color="red">*</font></label>
        <input type="text" id="title" name="title" placeholder="Heading" required value="<?php echo $title?>">
        <label for="subject">Description<font size="3" color="red">*</font></label>
        <textarea id="subject" name="content" placeholder="Please start writing here" style="height:110px" required><?php echo htmlspecialchars($content); ?></textarea>
        <label for="upload">Upload Screenshot <font size="1" color="red">(Max Size: 2MB)</font></label><br>
        <input type="file" name="file" accept=".jpg, .png, .jpeg, .bmp, .JPG, .PNG, .JPEG, .BMP"/>
        <input type="submit" name="submit" value="Submit" class="button" style="float: right;">

      </form>
    </div>

</div>

<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
</div>
</html>
