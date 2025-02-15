<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Dancestry</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Asap">
	<link rel="stylesheet" href="css/global.css">
	<link rel="stylesheet" href="css/home.css">
	<link rel="stylesheet" href="css/foundation-datepicker.css">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="css/foundation-datepicker.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" >
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" >
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.js"></script>
	<script src="js/foundation-datepicker.js"></script>
	<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

	<link rel="stylesheet" href="css/lineage_styles.css" type="text/css" />

	<style>
	#login{padding-left:80%; font-size:16px; }
	.menu >li{color:white;}
	#tab_bar_row { margin: auto; width: 100%; margin-left: 0%; height:100%; color:black;}
	a#event_tag{ color: #1FCB19; }
	</style>
</head>

<body>
	<header class="row column text-center">
			<img src="data/images/logo_dcl_2.png" alt="Dancestry logo">
	</header>
	<p id="login"> Hi,<?php echo$_SESSION['user_firstname'] ?> &nbsp;  <a href='logout.php'>Logout</a> </p>

	<nav class="row column menu-centered" id="navbar">
		<ul class="vertical medium-horizontal menu" id="menu">
			<li><a href="index.php" id="home">Home</a></li>
			<li><a href="login.php" id="contri_lineage">Contribute Your Lineage</a></li>
			<li><a href="lineage_index.php">Explore the Network</a></li>
			<li><a href="event.php">Events</a></li>
			<li><a href="phone_appointment_list.php" id="phone_appointment">See Phone Appointments</a></li>
            <li><a href="bug_report_list.php" id="bug_report">See Bug Reports</a></li>
            <li><a href="delete_user.php" id="delete_user">Delete a User</a></li>

		</ul>
        <div id="network_display_div">
		<div id="tab_bar_row" class="row tab">
		<button class="tablinks small-2 columns" style="float: left;width: 50%;" id="event" onclick="window.location.href = 'bug_report_list_done.php';">View Resolved Bugs</button>
		<button class="tablinks small-2 columns" style="float: left;width: 50%;" id="add_event" onclick="window.location.href = 'bug_report_list.php';">View Unresolved Bugs</button>
		</div>
		</div>
	</nav>

</body>
<br>
<br>
<br>

<script>
$(document).ready(function(){
    $(function(){
        $('a').each(function(){
            if ($(this).prop('href') == window.location.href) {
				$(this).addClass('active');
            }
        });
    });
});
</script>
</html>
