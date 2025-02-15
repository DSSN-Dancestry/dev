<?php
include 'util.php';
my_session_start();
$showBugMenu = true;
include 'menu.php';
require 'utils.php';
checkAdmin();

if (isset($_SESSION["user_email_address"])) {
    include 'connection_open.php';
    $user_email_id = $_SESSION["user_email_address"];
    $query = "SELECT * FROM bugs where status='0' ORDER BY uploaded_on DESC";
    $event_profile = mysqli_query($dbc, $query) or die('Error querying database.: '  .mysqli_error($dbc)); ?>

    <div align="center" class="mrt70i">
    <h4>Resolved Bugs.</h4>
    </div>

    <div class='row column menu-centered'>
    <table id ='usetTable' class='display'>
        <thead>
            <tr>
                <th class="tac">ID</th>
                <th class="tac">Name</th>
                <th class="tac">Email ID</th>
                <th class="tac">Issue</th>
                <th class="tac">Category</th>
                <th class="tac">Severity</th>
                <th class="tac">Assigned To</th>
                <th class="tac">Reported On</th>
            </tr>
        </thead>
    <tbody>
    <?php
    while ($row = mysqli_fetch_array($event_profile)) {
        $id=$row['id'];
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . $row['user_email_id'] . "</td>";
        echo "<td><a href = 'issue.php?id=$id'>" . $row['issue_title'] . "</a></td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "<td>" . $row['severity'] . "</td>";
        echo "<td>" . $row['assigned_to'] . "</td>";
        echo "<td>" . date("F d, Y h:i:s A", strtotime($row['uploaded_on'])) . "</td>";
        //echo "<td>  <a href='notify_event.php'class='button'>Notify User</a>   </td>";
        echo "</tr>";
    }
    echo "</tbody> </div> </table>";
    mysqli_close($dbc);
} else {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
}

echo "<br>";
include 'footer.php';
echo "<br>";
?>
<title>Resolved Bug Reports | Dancestry</title>
<script type="text/javascript">
    $(document).ready( function () {
        $('#usetTable').DataTable();
    });
</script>