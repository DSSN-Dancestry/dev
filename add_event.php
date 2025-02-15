<?php
include 'util.php';
my_session_start();
if (!isset($_SESSION["user_email_address"])) {
  header('Location: login.php');
  exit;
}
$showEventMenu = true;

include 'menu.php';



    $user_email_address=$_SESSION["user_email_address"];
    $user_firstname=$_SESSION["user_firstname"];
    $user_lastname=$_SESSION["user_lastname"];
    $user_name=$user_firstname." ".$user_lastname;

?>
<html>
<head>
<title>Add Event | Dancestry</title>
  <style>
    .footer{
      margin-top: 2.9%;
    }
  </style>
</head>
<div class="container">
  <div style="text-align:center">
    <h4>Add your upcoming Event</h4>
  </div>

  <div class="row">
      <form method ="post" action="add_event_mediator.php" id="addevent">
      <div class="row"><p align="right"><font size="2" color="red"><I>* marked fields are mandatory.</I></font></div>
        <input type="hidden" name="user_email_address" value="<?=$user_email_address;?>" />

        <label for="event_name">Event Name <font color="red"> *</font></label>
        <input type="text" id="event_name" name="event_name" required>

        <label for="event_location"> Location <font color="red"> *</font></label>
        <input type="text" id="event_location" name="event_location" required >

        <label for="event_description"> Description <font color="red"> *</font></label>
        <input type="text" id="event_description" name="event_description"  required >

        <label for="event_startdate"> Date <font color="red"> *</font></label>
        <input type="date" id="event_startdate" name="event_startdate" placeholder="Event Start Date" required >

        <label for="event_time"> Time <font color="red"> *</font></label>
        <input type="time" id="event_time" name="event_time" required>

        <input type="submit" name="submit" value="Submit" class="button"> &nbsp; &nbsp; &nbsp;
        <input type="reset" name="reset" value="Clear Form" class="button">
      </form>
    </div>
</div>
<script>
$("#addevent").trigger('reset');
document.getElementById('event_startdate').min = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().split("T")[0];
</script>
<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
</div>
</html>
