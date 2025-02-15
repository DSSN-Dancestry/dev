<?php
include 'util.php';
my_session_start();
$showEventMenu = true;
if (!isset($_SESSION["user_email_address"])) {
  header('Location: login.php');
  exit;
}

include 'menu.php';




    include 'connection_open.php';
    $user_email_id = $_SESSION["user_email_address"];
    $query = "SELECT * FROM event_planner WHERE user_email_id = '$user_email_id' and event_startdate < CURDATE() order by event_startdate;";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc));

    


    echo "<div class='row column menu-centered'>
    <table class='display' id='past_eventID'>
    <thead>
    <tr>
    <th style='text-align:center'>Event Name</th>
    <th style='text-align:center'>Location</th>
    <th style='text-align:center'>Date</th>
    <th style='text-align:center'>Time</th>
    <th> </th>
    </tr>
    </thead>
    <tbody>";

    while ($row = mysqli_fetch_array($event_profile)) {
        $id=$row['event_id'];
        $ename=$row['event_name'];
        echo "<tr>";
        echo "<td>" . $row['event_name'] . "</td>";
        echo "<td>" . $row['event_location'] . "</td>";
        echo "<td>" . date("F d, Y", strtotime($row['event_startdate'])) . "</td>";
        $time_in_12_hour_format  = date("g:i a", strtotime($row['event_time']));
        echo "<td>" . $time_in_12_hour_format . "</td>"; ?>

    <td>  <a onClick="cnf('<?php echo $ename ?>', '<?php echo $id ?>');" class='button'>Delete Event</a>   </td>

    <?php
    echo "</tr>";
    }
    echo "</tbody> </div> </table>";
    echo "</br>";
    mysqli_close($dbc);


?>

<title>My Past Events | Dancestry</title>
<script type="text/javascript">
      $(document).ready( function () {
        $('#past_eventID').DataTable({
        "order": [[ 2, "desc" ]],
        "pagingType": "simple_numbers",
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]]
        });
        });
      function cnf(ename,id)
      {
            if (confirm("Delete "+ename+" Event ?"))
                 location.href='delete_event.php?id='+id;
      }
  </script>

<?php
include 'footer.php';
?>
