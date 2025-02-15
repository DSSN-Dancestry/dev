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
<script type="text/javascript">
  function cnf(ename,id)
      {
            if (confirm("Delete "+ename+" Event ?"))
                 location.href='delete_event.php?id='+id;
      }
</script>

  <title>Event Details Page</title>
  <style>
    .close
    {
    position: absolute;
    right: 20%;
    top: 30%px;
    width: 35px;
    height: 35px;
    opacity: 0.3;
    }
    .close:hover {
    opacity: 1;
    }
    .close:before, .close:after {
    position: absolute;
    left: 15px;
    content: ' ';
    height: 33px;
    width: 2px;
    background-color: #333;
    }
    .close:before {
    transform: rotate(45deg);
    }
   .close:after {
    transform: rotate(-45deg);
   }
   .footer{
      margin-top: 2.9%;
    }
  </style>
</head>

<div class="container">
  <div style="text-align:center">
    <h4>My Event</h4>
  </div>
  <div class="row">
			<div class="medium-8 column text-justify">
				<section>
					<h4>Event Name :  <?php echo $row['event_name'] ?> </h4>
          <h4>Location :    <?php echo $row['event_location'] ?></h4>
          <h4>Description : <?php echo $row['event_description'] ?></h4>
          <h4>Date :        <?php echo date("F d, Y", strtotime($row['event_startdate'])) ?></h4>
          <h4>Time :        <?php $time_in_12_hour_format  = date("g:i a", strtotime($row['event_time'])); echo $time_in_12_hour_format ?></h4>
        </section>
        <br>

                <section>
        <a href='update_event.php?id=<?php echo $id ?>' class='button'>Update Event</a>  &nbsp;&nbsp;&nbsp;
        <a onClick="cnf('<?php echo $row['event_name'] ?>', '<?php echo $id ?>');" class='button'>Delete Event</a>

        </section>
			</div>
      <a href="event.php" class="close"></a>
  </div>
</div>


<div class="footer">
  <?php
  include 'footer.php';
  ?>
  </div>
</html>
