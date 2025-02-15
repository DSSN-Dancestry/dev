<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="css/global.css" rel="stylesheet">
<title>Completed Phone Appointments | Dancestry</title>
</head>
<?php
include 'util.php';
require 'utils.php';
my_session_start();
checkAdmin();
    include 'menu.php';

    if (isset($_SESSION["user_email_address"])) {
        $user_email_address = $_SESSION["user_email_address"];

        include 'connection_open.php';

        $query = "SELECT * FROM phone_appointments where status='Done'";


        $result = mysqli_query($dbc, $query)
        or die('Error querying database.: ' . mysqli_error());
        echo "<div class = 'row'>";
        $count = mysqli_num_rows($result);
        echo "<table style='width:100%;'align='center'><tr><td>";
        echo "<div align='center'><a href='phone_appointment_list_done.php'><button class='admin_button'>View Completed Phone Appointments</button></div>";
        echo"</td><td>";
        echo "<div align='center'><a href='phone_appointment_list.php'><button class='admin_button'>View Pending Phone Appointments</button></div>";
        echo "</td></tr></table>";

        echo "<div class='table-responsive column'><table style='height: auto;' align='center'>";
        echo "<tr><th>id</th><th>First Name</th><th>Last Name</th><th>Contact</th><th>Notes</th></tr>";

        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $contact = $row['contact'];
            $note = $row['note'];
            echo "<tr><td style='width: 50px;'>".$id."</td><td class='admin_td'>".$first_name."</td><td>".$last_name."</td><td class='admin_td'>".$contact."</td><td class='admin_td'>".$note."</td></tr>";
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
        if(url.search("phone_appointment_list_done.php"))
        {
            var phone_appointment = document.getElementById("phone_appointment");
            $(phone_appointment).addClass('active');
        }
});
    function confirmDone(){

        //var c = confirm("Warning: You are about to confirm this appointment schedule profile!");
        //return c;
        location.reload();
    }
    </script>
</html>
<?php
  include 'footer.php';
?>
