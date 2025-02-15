<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="css/global.css" rel="stylesheet">
<title>Pending Phone Appointments | Dancestry</title>
</head>
<?php
include 'util.php';
include 'utils.php';
  my_session_start();
  checkAdmin();
    include 'menu.php';

    echo "<div class = 'row'>";

    if (isset($_SESSION["user_email_address"])) {
        $user_email_address = $_SESSION["user_email_address"];

        include 'connection_open.php';

        $query = "SELECT * FROM phone_appointments where status='Undone' order by submitted_date ASC";


        $result = mysqli_query($dbc, $query)
        or die('Error querying database.: ' . mysqli_error());

        $count = mysqli_num_rows($result);
        echo "<table style='width:100%;'align='center'><tr><td>";
        echo "<div align='center'><a href='phone_appointment_list_done.php'><button class='admin_button'>View Completed Phone Appointments</button></div>";
        echo"</td><td>";
        echo "<div align='center'><a href='phone_appointment_list.php'><button class='admin_button'>View Pending Phone Appointments</button></div>";
        echo "</td></tr></table>";

        echo "<div class='table-responsive column'><table style='height: auto;' align='center'>";
        echo "<tr><th>id</th><th>First Name</th><th>Last Name</th><th>Contact</th><th>Notes</th><th>Submission Date</th></tr>";

        while ($row = mysqli_fetch_array($result)) {
            $ID = $row['id'];
            $Firstname = $row['first_name'];
            $Lastname = $row['last_name'];
            $Contact = $row['contact'];
            $Note = $row['note'];
            $SubmissionDate=$row['submitted_date'];
            echo "<tr>
            <td style='width: 50px;'>".$ID."</td>
            <td class='admin_td'>".$Firstname."</td>
            <td class='admin_td'>".$Lastname."</td>
            <td class='admin_td'>".$Contact."</td>
            <td class='admin_td'>".$Note."</td>
            <td class='admin_td'>".$SubmissionDate."</td>
            <td>
            <button class='admin_mark_as_done' type='button'><a href='done.php?id=".$ID."'>Mark as Done</button></td>
            </tr>";
        }
        echo "</table></div>";
    } else {
        $location = "login.php";
        echo("<script>location.href='$location'</script>");
    }
    echo "</div>";

?>
<script>
$(window).bind("load", function() {
   // code here
   var activeElements= document.querySelectorAll(".active");
    activeElements[0].classList.remove('active');
    var url = window.location.href;
        if(url.search("phone_appointment_list.php"))
        {
            var phone_appointment = document.getElementById("phone_appointment");
            $(phone_appointment).addClass('active');
        }
});


   function confirmDone(){
        location.reload();
    }
</script>
</html>
<?php
  include 'footer.php';
?>
