<?php
include 'connection_open.php';
include 'util.php';
my_session_start();
$showEventMenu = true;

include 'menu.php';

if (isset($_SESSION["user_email_address"])) {
    $user_firstname=$_SESSION["user_firstname"];
    $user_lastname=$_SESSION["user_lastname"];
    $user_name=$user_firstname." ".$user_lastname;
    $user_email_id = $_SESSION["user_email_address"];
    $query = "select * from event_planner where event_startdate >= CURDATE() and user_email_id in (select artist_email_id_2 from artist_relation where artist_email_id_1= '$user_email_id'  and artist_email_id_2 != '') order by event_startdate";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc));

    echo "<html>
        <head>
        <title>My Connection's Events | Dancestry</title>
        </head>
    </html> ";


    echo "<div class='row column menu-centered'>
    <table class='display' id='connection_eventsID'>
    <thead>
    <tr>
    <th style='text-align:center'>Connection Name</th>
    <th style='text-align:center'>Connection Email ID</th>
    <th style='text-align:center'>Event Name</th>
    <th style='text-align:center'>Location</th>
    <th style='text-align:center'>Date</th>
    <th style='text-align:center'>Time</th>
    </tr>
    </thead>
    <tbody>";

    while ($row = mysqli_fetch_array($event_profile)) {
        $id=$row['event_id'];
        $ename=$row['event_name'];
        echo "<tr>";

        $eid=$row['user_email_id'];
        $query2 = "select * from user_profile where user_email_address='$eid'";
        $event_profile2 = mysqli_query($dbc, $query2) or die('Error querying database.: '  .mysqli_error($dbc));
        $row2 = mysqli_fetch_array($event_profile2);

        $a=$row2['user_first_name'];
        $b=$row2['user_last_name'];
        echo "<td>" .$a ."&nbsp". $b  . "</td>";
        echo "<td>" . $eid . "</td>";
        echo "<td>" . $row['event_name'] . "</td>";
        echo "<td>" . $row['event_location'] . "</td>";
        echo "<td>" . date("F d, Y", strtotime($row['event_startdate'])) . "</td>";
        $time_in_12_hour_format  = date("g:i a", strtotime($row['event_time']));
        echo "<td>" . $time_in_12_hour_format . "</td>";
        echo "</tr>";
    }
    echo "</tbody> </div> </table>";
    echo "</br>";
    mysqli_close($dbc);
} else {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
}


include 'footer.php';
?>
<script type="text/javascript">
      $(document).ready( function () {
        $('#connection_eventsID').DataTable({
        "order": [[ 4, "desc" ]],
        "pagingType": "simple_numbers",
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]]
        });
        });

  </script>
