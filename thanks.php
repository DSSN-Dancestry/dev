<?php
include 'util.php';
my_session_start();

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
    $user_email_address=$_SESSION["user_email_address"];
    $user_firstname=$_SESSION["user_firstname"];
    $user_lastname=$_SESSION["user_lastname"];
    $user_name=$user_firstname." ".$user_lastname;
} else {
    $user_name="";
    $user_email_address="";
}
?>
<html>
<head>
  <title>Help Page</title>
  <style>
    .footer{
      margin-top: 2.9%;
    }
  </style>

</head>
<div class="container">
  <div style="text-align:center">
    <h4>Thank you for your feedback. Someone from our team will get back to you shortly.</h4>
  </div>


</div>

<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
</div>
</html>
