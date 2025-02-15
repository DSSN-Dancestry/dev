<?php
include 'util.php';
my_session_start();

// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
    header('Location: login.php');
    exit;
}

$showEventMenu = true;
include 'menu.php';

?>
<html>
<head>
    <title>Events | Dancestry</title>
    <script src="submit_database_request.js"></script>
    <script src="js/dateTimeUtils.js"></script>
</head>


<?php
    $user_email_id = $_SESSION["user_email_address"];
    include 'connection_open.php';

    //$query = "SELECT * FROM event_planner WHERE user_email_id = '$user_email_id' and event_startdate >= CURDATE() order by event_startdate;";
    //$event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc));

    echo "<div class='row column menu-centered' style='margin-top:55px;'>
    <table class='display' id='eventID'>
    <thead>
    <tr>
    <th style='text-align:center'>Event Name</th>
    <th style='text-align:center'>Location</th>
    <th style='text-align:center'>Date</th>
    <th style='text-align:center'>Time</th>
    <th style='text-align:center'></th>
    <th style='text-align:center'>Last Notified</th>
    </tr>
    </thead>
    <tbody id='tbody'>";

    /*
    while ($row = mysqli_fetch_array($event_profile)) {
        $id=$row['event_id'];
        $ename=$row['event_name'];
        echo "<tr>";

        echo "<td> <a href='event_details.php?id=$id'>" . $row['event_name'] . "</a></td>";
        echo "<td>" . $row['event_location'] . "</td>";
        echo "<td>" . date("F d, Y", strtotime($row['event_startdate'])) . "</td>";
        $time_in_12_hour_format  = date("g:i a", strtotime($row['event_time']));
        echo "<td>" . $time_in_12_hour_format . "</td>";

        echo "<td>  <a href='notify_event.php?id=$id' class='button' >Notify Connections</a>   </td>";
        $date='Never Notified';
        if ($row['last_notified']!=null) {
            $date=date("F d, Y h:i a", strtotime($row['last_notified']));
        }
        echo "<td>" . $date . "</td>";
        echo "</tr>";
    }
*/
    echo "</tbody> </div> </table>";
    echo "</br>";



?>
<script type="text/javascript">

    // on page load, get the current events for the user
    $(document).ready(function() {
        submitJson(null, 'eventcontroller.php',
        {
          "action":"getEventPlanner",
          "useremailid":"<?php echo $user_email_id ?>",
          "current":true
        },
        loadTable);
    });

    // when the events return from the async call, load and format them
    function loadTable(maindta){
      let html = "";
      console.log("in load table "+maindta);
      if (maindta && maindta['event_planner']){

          console.log("Events are" + maindta['event_planner']);
          maindta['event_planner'].forEach(item => {

          html +=  "<tr>";

          html +=   "<td> <a href='event_details.php?id="+item.event_id+"'>" + item.event_name + "</a></td>";
          html +=   "<td>" + item.event_location + "</td>";
          html += "<td style='text-align:center'>" + dateConvert(item.event_startdate) + "</td>";

          html +=   "<td>" + timeConvert(item.event_time) + "</td>";

          html +=   "<td>  <a href='notify_event.php?id=$id' class='button' >Notify Connections</a>   </td>";
          let date='Never Notified';
          if (item.last_notified!=null) {
              date=item.last_notified;
          }
          html +=   "<td>" + date + "</td>";
          html +=   "</tr>";
          $("#tbody").html(html);
        });
      }

      // call datatables to organize the event results
      $('#eventID').DataTable({
      "order": [[ 2, "desc" ]],
      "pagingType": "simple_numbers",
      "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]]
      });
    }

    function sqlToJsDate(sqlDate){

    //sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss.ms")

    var sqlDateArr1 = sqlDate.split("-");

    //format of sqlDateArr1[] = ['yyyy','mm','dd hh:mm:ms']

    var sYear = sqlDateArr1[0];

    var sMonth = (Number(sqlDateArr1[1]) - 1).toString();

    var sqlDateArr2 = sqlDateArr1[2].split(" ");

    //format of sqlDateArr2[] = ['dd', 'hh:mm:ss.ms']

    var sDay = sqlDateArr2[0];

    var sqlDateArr3 = sqlDateArr2[1].split(":");

    //format of sqlDateArr3[] = ['hh','mm','ss.ms']

    var sHour = sqlDateArr3[0];

    var sMinute = sqlDateArr3[1];

    var sqlDateArr4 = sqlDateArr3[2].split(".");

    //format of sqlDateArr4[] = ['ss','ms']
    var sSecond = sqlDateArr4[0];
    var sMillisecond = sqlDateArr4[1];
    return new Date(sYear,sMonth,sDay,sHour,sMinute,sSecond,sMillisecond);

  }

  </script>



<?php
include 'footer.php';
?>
