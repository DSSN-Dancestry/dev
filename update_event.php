<?php
include 'util.php';
my_session_start();
$showEventMenu = true;

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
    $user_email_address=$_SESSION["user_email_address"];
    $user_firstname=$_SESSION["user_firstname"];
    $user_lastname=$_SESSION["user_lastname"];
    $user_name=$user_firstname." ".$user_lastname;
    $id = $_GET['id'];

    include 'connection_open.php';
    $query = "SELECT * FROM event_planner WHERE event_id=$id;";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc));
    $row = mysqli_fetch_array($event_profile);
} else {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
}
?>
<html>
<head>
  <title>Update Existing Event Page</title>
  <style>
    .footer{
      margin-top: 2.9%;
    }
  </style>
</head>
<div class="container">
  <div style="text-align:center">
    <h4>Update your upcoming Event</h4>
  </div>

  <div class="row">
      <form method ="post" action="update_event_mediator.php?id=<?php echo $id ?>" id="addevent">
        <input type="hidden" name="user_email_address" value="<?=$user_email_address;?>" />

        <label for="event_name">Event Name <font color="red"> *</font></label>
        <input type="text" id="event_name" name="event_name" value="<?=$row['event_name'];?>" required>

        <label for="event_location"> Location <font color="red"> *</font></label>
        <input type="text" id="event_location" name="event_location" value="<?=$row['event_location'];?>" required >

        <label for="event_description"> Description <font color="red"> *</font></label>
        <input type="text" id="event_description" name="event_description" value="<?=$row['event_description'];?>" required >

        <label for="event_startdate"> Date <font color="red"> *</font></label>
        <input type="date" id="event_startdate" name="event_startdate" value="<?=$row['event_startdate'];?>" required >

        <label for="event_time"> Time <font color="red"> *</font></label>
        <input type="time" id="event_time" name="event_time" value="<?=$row['event_time'];?>" required>

        <input type="submit" name="submit" value="Submit" class="button"> &nbsp; &nbsp; &nbsp;
        <input type="button" name="cancel" value="Cancel" class="button" onClick="document.location.href='event.php'">
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
