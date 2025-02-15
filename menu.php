<!--
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
-->
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Asap">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/lineage_style.css">
<link rel="stylesheet" href="css/home.css">
<link rel="stylesheet" href="css/foundation-datepicker.css">
<link rel="stylesheet" href="css/foundation-datepicker.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" >
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" >
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.js"></script>
<script src="js/foundation-datepicker.js"></script>

<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
<!-- 
<style>
#tab_bar_row { margin: auto; width: 100%; margin-top:10px; margin-bottom:15px;  color:black;}
a#event_tag{ color: #1FCB19; }
.mrt10i{margin-top: 10px !important;}
.mrt70i{margin-top: 70px !important;}
.tac{text-align: center !important;}
thead{display: contents;}

</style> -->
<!--
</head>

<body>
-->

<nav id="navbar">
<div id="brand">
	<a href="index.php" id="home">
		<!-- <img src="data/images/dancestry_logo_horizontal_larger.png" alt="Dancestry logo" class="logo"> -->
		<!-- <img src="data/images/dancestry_logo_long.png" alt="Dancestry logo" class="logo"> -->
		<img src="data/images/logo.jpg" alt="Dancestry logo" id="navbar-logo-image" class="logo">
		<img src="data/images/dancestry_logotext_screenshot.png" alt="Dancestry logo" class="logo">
	</a>
</div>
<div>
	<ul class="vertical medium-horizontal menu">
		<!-- <li><a 
			style="border-top-width: 10px; border-top-style: solid;
			href="index.php" id="home">Home</a></li> -->
		<li><a href="index.php" id="home">Home</a></li>
		<li><a href="profiles.php" id="contri_lineage">Contribute</a></li>
		<li><a href="lineage_index.php">Network</a></li>
		<?php
		if (isset($_SESSION["user_type"])){
			echo '<li><a href="event.php">Events </a></li>';
		}
		?>
	</ul>
</div>
<div id="menu-login">
	<ul class="vertical medium-horizontal menu">
		<li class="login">
			<?php
			if (isset($_SESSION["user_email_address"])) {
				?>
				<span id="login-greeting"><?php
							if (isset($_SESSION["user_firstname"])) {
								echo "Hi, ".$_SESSION['user_firstname']." | ";
								// echo " | ".$_SESSION["user_email_address"];
								//echo " ".$_SESSION['user_lastname'];
								//echo " | Role: ".$_SESSION["user_type"];
							}?><a href='logout.php' id="login">Logout</a> </span>
				<?php
			} else {
				?>
				<span id="login"><a href='login.php'>Login</a></span>
			<?php
			}
			?>
		</li>
	</ul>
</div>
</nav>
<div id="admin-navbar">
<ul class="vertical medium-horizontal menu">
	<?php
	// if the current user is an administrator, add those options to the menu
	if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin") {
		?>
			<li><a href="phone_appointment_list.php" id="phone_appointment">See Phone Appointments</a></li>
			<li><a href="bug_report_list.php" id="bug_report">See Bug Reports</a></li>
			<li><a href="adminconsole.php" id="delete_user">Admin Console</a></li>

		<?php
	}
	?>
</ul>
</div>

<div id="opacity-cover" onclick="closeNav()"></div>

<div id="side-navbar" class="side-navbar" style="overflow-y:scroll;">
<div id="side-navbar-container">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<div>
		<ul class="vertical medium-horizontal menu">
			<li class="login">
				<?php
				if (isset($_SESSION["user_email_address"])) {
					?>
					<span id="side-navbar-profile">
						<span id="side-navbar-welcome-message">
							<?php
								if (isset($_SESSION["user_firstname"])) {
									echo "Hi, ".$_SESSION['user_firstname'];
									// echo " | ".$_SESSION["user_email_address"];
									//echo " ".$_SESSION['user_lastname'];
									//echo " | Role: ".$_SESSION["user_type"];
								}?> 
						</span>
						<a href='logout.php' id="login">Logout</a>
					</span>
					<?php
				} else {
					?>
					<a href='login.php'>Login</a>
				<?php
				}
				?>
			</li>
		</ul>
		<hr id="login-hr">
	</div>
	<div>
		<ul class="vertical menu">
			<li><a href="index.php" id="home">Home</a></li>
			<li><a href="profiles.php" id="contri_lineage">Contribute</a></li>
			<li><a href="lineage_index.php">Network</a></li>
			<?php
			if (isset($_SESSION["user_type"])){
				echo '<li><a href="event.php">Events </a></li>';
			}
			?>
			<?php
			// if the current user is an administrator, add those options to the menu
			if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin") {
				?>
		</ul>
	</div>
	<div>
		<ul class="vertical menu" id="admin-side-navbar">
					<li><a href="phone_appointment_list.php" id="phone_appointment">See Phone Appointments</a></li>
					<li><a href="bug_report_list.php" id="bug_report">See Bug Reports</a></li>
					<li><a href="adminconsole.php" id="delete_user">Admin Console</a></li>

				<?php
			}
			?>
		</ul>
	</div>
		<div class="mobile-footer">
		<ul class="vertical menu">
			<li><a href="index.php">Home</a></li>
			<li><a href="faq.php">FAQ</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="help.php">Report an Issue</a></li>
		</ul>
		<ul class="menu">
			<li><a target="_blank" href="https://www.facebook.com/profile.php?id=100088193205256"><i class="fi-social-facebook social-media-icon"></i></a></li>
			<li><a href="coming_soon.php"><i class="fi-social-twitter social-media-icon"></i></a></li>
			<li><a target="_blank" href="https://www.instagram.com/dancestryglobal/"><i class="fi-social-instagram social-media-icon"></i></a></li>
		</ul>
	</div>
</div>
</div>
<div id="mobile-menu">
<div style="max-height:69px;">
	<div id="mobile-logo-total" onclick="window.location.href = 'index.php'">
		<img src="data/images/logo.jpg" alt="Dancestry Logo" id="mobile-logo">
		<img src="data/images/dancestry_logotext_screenshot.png" alt="Dancestry logo" id="mobile-logotext">
	</div>
	<span onclick="openNav()" id="hamburger">&#9776;</span>
</div>
</div>



<?php
if (isset($showEventMenu) && $showEventMenu) {
	?>
	<div id="tab_bar_row" class="row tab column menu-centered">
		<button class="tablinks small-2 columns" style="float: left;width: 25%;" id="event" onclick="window.location.href = 'event.php';">My Events</button>
		<button class="tablinks small-2 columns" style="float: left;width: 25%;" id="add_event" onclick="window.location.href = 'add_event.php';">Add Event</button>
		<button class="tablinks small-3 columns" style="float: left;width: 25%;" id="past_event"onclick="window.location.href = 'past_event.php';">My Past Events</button>
		<button class="tablinks small" style="float: left;width: 25%;" id="connection_events" onclick="window.location.href = 'connection_events.php';">My Connections's Events</button>
	</div>
<?php
}
if (isset($showBugMenu) && $showBugMenu) {
	?>
	<div id="network_display_div" class="mrt10i">
		<div id="tab_bar_row" class="row tab">
			<button class="tablinks small-2 columns" style="float: left;width: 50%;" id="event" onclick="window.location.href = 'bug_report_list_done.php';">View Resolved Bugs</button>
			<button class="tablinks small-2 columns" style="float: left;width: 50%;" id="add_event" onclick="window.location.href = 'bug_report_list.php';">View Unresolved Bugs</button>
		</div>
	</div>
<?php
}
if (isset($showArtistMenu) && $showArtistMenu) {
	?>
	<div id="network_display_div" class="mrt10i">
		<div id="tab_bar_row" class="row tab">
			<button class="tablinks small-2 columns profile" style="float: left;width: 50%;" id="event" onclick="window.location.href = 'profiles.php';">My Profile</button>
			<button class="tablinks small-2 columns other_artist_profile" style="float: left;width: 50%;" id="add_event" onclick="window.location.href = 'other_artist_profiles.php';">Other Artist Profiles</button>
		</div>
	</div>
<?php
}
?>

<!--
</body>
-->
<script>
$(document).ready(function(){
$(function(){
	var urlMapping = {
		profile: '/profiles.php',
		other_artist_profile: '/other_artist_profiles.php'
	};
	Object.keys(urlMapping).forEach((key)=>{
		if(window.location.href.includes(urlMapping[key])){
			$('.'+key).addClass('active');
		}
	});
	$('a').each(function(){
		if ($(this).prop('href') == window.location.href) {
			$(this).addClass('active');
		}
	});
});
});
</script>

<div id="nav-spacer">&nbsp;</div>


<?php
// if the current user is an administrator, add those options to the menu
if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin") {
?>
<div id="admin-nav-spacer">&nbsp;</div>
<?php
}
?>

<script src="menu.js" type="text/javascript"></script>
<!--
</html>
-->