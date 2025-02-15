<?php
include 'util.php';
require 'utils.php';
my_session_start();
$showAdminMenu = true;
checkAdmin();
include 'menu.php';
?>
<title>Maintain Genres | Choreographic Lineage</title>
<script src="js/platform.js"></script>
    <script type="text/javascript" src="js/browserCheck.js"></script>
	<script>window.onload=function(){ strict_check();}</script>
<div id="adnub_display_div" class="mrt10i row" style="padding-left: 10px; padding-right: 10px;">
  <div id="tab_bar_row" class="row tab">
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="event" onclick="window.location.href = 'delete_user.php';">Maintain Users
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="add_event" onclick="window.location.href = 'maintain_genres.php';">Maintain Genres
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" id="feature_event" onclick="window.location.href = 'feature_management.php';">Feature Management
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_network.php';">Update Network Cache
    </button>
    <button class="tablinks small-2 columns admin_console_tab" style="float: left;width: 20%;" onclick="window.location.href = 'maintain_logs.php';">Admin Logs
    </button>

  </div>
</div>
<div class="row">
	<div class="medium-12">
		<section>
            <form id="delete_user_form" name="delete_user_form" method="post" action="delete_user_mediator.php" enctype="multipart/form-data">
				<fieldset>
                    <div class="row">
                        <div class="small-12 column" id="maintain-user-div">
                            <table id ='usetTable' class='display'>
                                <thead>
                                    <tr>
                                        <th width="200">User</th>
                                        <th width="200">Operation</th>
                                        <th width="200">Details</th>
                                        <th width="200">Date/Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
                </fieldset>
			</form>
        </section>
    </div>
</div>
<div class="footer" style="margin-top:4.5%">
    <?php
        include 'footer.php';
    ?>
</div>

<script type="text/javascript">

function loadLogs(callback){
    fetch("logcontroller.php", {
        method: "post",
        body: JSON.stringify({action: "getUserLogs"})
    })
    .then(res => res.json())
    .then(
        result => {
            table.clear();
            if(result['user_logs']){
                for (let i = 0; i < result['user_logs'].length; i++){
                    let logs = result['user_logs'][i];
                    table.row.add([logs.artist_name, logs.operation_name, logs.log_details, logs.date_time]).draw(false)
                }
            }
        },
        error => {
            alert("No Logs available.");
        }
    )
    .then(
        check => {
            if(typeof callback == "function"){
                callback();
            }
        }
    );
}

$(document).ready( function () {
    $("#log_event").css("background-color","#ddd");
    table = $('#usetTable').DataTable();
    loadLogs();
});
</script>