<?php
include 'util.php';
my_session_start();
$showEventMenu = true;

include 'menu.php';
$event_name=$_GET['eventname'];
?>
<html>
<head>
  <title>Notification Successful Page</title>
  <style>
    .footer{
      margin-top: 2.9%;
    }
  </style>

</head>
<div class="container">
  <div style="text-align:center">
    <h4>You have successfully notified your connections about <?php echo"$event_name"; ?> event.</h4>
  </div>
</div>

<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
</div>
</html>
