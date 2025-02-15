<?php
require 'util.php';
require 'utils.php';
my_session_start();
checkAdmin();
?>
<html>
<title>Admin Console | Dancestry</title>
<script src="js/platform.js"></script>
    <script type="text/javascript" src="js/browserCheck.js"></script>
	<script>window.onload=function(){ strict_check();}</script>
<body>
<?php
        include 'menu.php';
?>
<div id="adnub_display_div" class="mrt10i">
    <div id="tab_bar_row" class="row tab" style="min-width: 100%;">
        <button class="tablinks small-1 columns" style="float: left;width: 20%;" id="event"
                onclick="window.location.href = 'delete_user.php';">Maintain Users
        </button>
        <button class="tablinks small-1 columns" style="float: left;width: 20%;" id="add_event"
                onclick="window.location.href = 'maintain_genres.php';">Maintain Genres
        </button>
        <button class="tablinks small-1 columns" style="float: left;width: 20%;" id="feature_event"
                onclick="window.location.href = 'feature_management.php';">Feature Management
        </button>
        <button class="tablinks small-1 columns" style="float: left;width: 20%;"
                onclick="window.location.href = 'maintain_network.php';">Update Network Cache
        </button>
        <button class="tablinks small-1 columns" style="float: left;width: 20%;"
                onclick="window.location.href = 'maintain_logs.php';">Admin Logs
        </button>
    </div>
</div>
<body>
<?php
        include 'footer.php';
?>
</html>













