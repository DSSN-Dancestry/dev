<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'util.php';
require "utils.php";
my_session_start();
checkAdmin();
$showAdminMenu = true;
include 'menu.php';
include 'user_profile_popup.php';
require 'connect.php';


$query = "SELECT * FROM admin_features;";

$conn = getDbConnection();
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$result = $statement->fetchAll();

$result = json_encode($result);

$_SESSION = json_encode($_SESSION);


?>

<script>
  var session_result = <?php echo $_SESSION ?>;
</script>

<title>Feature Management | Dancestry</title>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>



<script src="js/platform.js"></script>
<script type="text/javascript" src="js/browserCheck.js"></script>



<script>
  window.onload = function() {
    strict_check();
  }
</script>
<div id="adnub_display_div" class="mrt10i row">
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





<style type="text/css">
  #mySidenav_div {
    overflow-y: scroll;
    height: 100%;
    overflow-x: hidden;
    text-align: center;
  }

  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 50;
    display: none;
    background: rgba(0, 0, 0, 0.6);
  }

  .modal-guts {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    padding: 20px 50px 0px 20px;
    border: 5px solid #ddd;
  }

  .modal .close-button {
    position: absolute;

    /* don't need to go crazy with z-index here, just sits over .modal-guts */
    z-index: 1;

    top: 10px;

    /* needs to look OK with or without scrollbar */
    right: 20px;

    border: 0;
    background: #006400;
    color: white;
    padding: 5px 10px;
    font-size: 1.3rem;
  }

  .profile-details-class {
    display: none;
    border: 5px solid #ddd;
    margin-bottom: 5px;
    padding: 10px;
    width: 100%;
    height: 100%;
  }

  .lineage_table_text {
    text-align: left;
    margin-left: 2px;
    color: #2199e8;
    text-decoration: none;
    line-height: inherit;
    cursor: pointer;
  }

  .modal {
    /* This way it could be display flex or grid or whatever also. */
    display: none;

    /* Probably need media queries here */
    width: 70%;
    max-width: 100%;
    height: 70%;
    max-height: 100%;
    position: fixed;
    z-index: 100;
    left: 50%;
    top: 50%;

    /* Use this for centering if unknown width/height */
    transform: translate(-50%, -50%);
    background: white;
    box-shadow: 0 0 60px 10px rgba(0, 0, 0, 0.9);
  }

  .bgrlgr {
    background-color: lightgreen !important;
    margin-left: -10px !important;
    margin-right: 0px !important;
  }


  .cursorp {
    cursor: pointer;
  }

  .bgn {
    background: none !important;
  }

  .brz {
    border: 0 !important;
  }

  .mrt10 {
    margin-top: 10px;
  }

  .mrt30 {
    margin-top: 30px;
  }

  .tac {
    text-align: center;
  }

  .w120p {
    width: 120px;
  }

  .mrb10 {
    margin-bottom: 10px;
  }
</style>





<div class="row">
  <div class="medium-12">
    <section>
      <form id="delete_user_form" name="delete_user_form" method="post" action="delete_user_mediator.php" enctype="multipart/form-data">
        <fieldset>
          <legend><strong>
              <h3>Feature Management</h3>
          </legend></strong>
          <div class="row">
            <div class="small-12 column">

              <table id="featTable" class="display compact">

                <thead>
                  <tr>
                    <th style="width: 35%;">Feature</th>
                    <th style="width: 20%;">Disable / Enable</th>
                    <th>Last Updated By</th>
                    <th style="width: 20%;">Last Updated On</th>
                  </tr>
                </thead>

                <tbody>

                  <script>
                    var result = <?php echo $result ?>;

                    console.log(result);

                    for (var i = 0; i < result.length; i++) {
                      document.write("<tr><td>");
                      document.write(result[i]["feature_name"]);
                      document.write("</td>");

                      document.write('<td><label class="switch"><input id="feature_');
                      document.write(result[i]["feature_id"]);
                      document.write('" type="checkbox"><span class="slider round"></span></label></td>');

                      document.write("<td>");
                      document.write(result[i]["feature_updated_by"]);
                      document.write("</td>");

                      document.write("<td>");
                      var x = new Date(result[i]["feature_updated_date"]);
                      x.setDate(x.getDate() + 1);
                      console.log(x);
                      document.write(x.toLocaleDateString());
                      document.write("</td>");

                      var y = "#feature_" + result[i]["feature_id"];

                      if (result[i]["feature_enabled"] == 1) {
                        $(y).prop('checked', true).attr('checked', 'checked');;
                        $(y).parent().toggleClass('active');
                      } else {
                        $(y).prop('checked', false).removeAttr('checked');
                      };

                    }
                  </script>

                </tbody>

              </table>

            </div>
          </div>
        </fieldset>
      </form>
    </section>
  </div>
</div>

<div class="row">
  <button class="primary button" type="button" name="home" id="home" style="float: right;" onclick="saveChanges();">
    <span> Save Changes </span>
  </button>
</div>


<div class="footer" style="margin-top:4.5%">
  <?php
  include 'footer.php';
  ?>
</div>




<script>
  function saveChanges() {
    for (var i = 0; i < result.length; i++) {

      console.log(i);

      var feat_id = result[i]["feature_id"];
      var x = "#feature_" + feat_id;

      if (result[i]["feature_enabled"]==1) {
        if (!$(x).is(":checked")) {
          console.log("offed");

          $.ajax({
            type: 'POST',
            url: 'feature_mediator.php',
            data: {
              "feat_id": feat_id,
              "feat_val": 0,
              "feat_user": session_result["user_firstname"] + " " + session_result["user_lastname"]
            },
            success: function(data) {
              // window.location.reload();
            },
            error: function(xhr, status, error) {
              console.log(xhr);
              console.log("error spotted");
            }
          });

        }
      } else {
        if ($(x).is(":checked")) {
          console.log("onned");

          $.ajax({
            type: 'POST',
            url: 'feature_mediator.php',
            data: {
              "feat_id": feat_id,
              "feat_val": 1,
              "feat_user": session_result["user_firstname"] + " " + session_result["user_lastname"]
            },
            success: function(data) {
              // window.location.reload();
            },
            error: function(xhr, status, error) {
              console.log(xhr);
              console.log("error spotted");
            }
          });
        }
      }
    }

    alert("Changes Saved");
    window.location.reload();
  }
</script>










<script>
  $(document).ready(function() {
    $('#featTable').DataTable({
      paging: false,
      order: [
        [0, 'asc']
      ],
      searching: false,
    });
  });
</script>






<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 32px;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .switch .slider {
    position: absolute;
    cursor: pointer;
    top: -10;
    left: 0;
    right: 0;
    bottom: 0;
    height: 24px;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .switch .slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .switch .slider.round {
    border-radius: 34px;
  }

  .switch .slider.round.round:before {
    border-radius: 50%;
  }

  .switch.active .slider {
    background-color: #2196F3;
  }

  .switch.active .slider {
    -webkit-box-shadow: 0 0 1px #2196F3;
    -moz-box-shadow: 0 0 1px #2196F3;
    -ms-box-shadow: 0 0 1px #2196F3;
  }

  .switch.active .slider:before {
    -webkit-transform: translateX(24px);
    -ms-transform: translateX(24px);
    transform: translateX(24px);
  }
</style>



<script>
  $(function() {
    $('.switch input').on("click", function() {
      $(this).parent().toggleClass('active');
    });
  });
</script>



<!-- <script>
  jQuery('.button.save_order.button-primary').click(function() {

sessionStorage.setItem('save_order',true);

});

jQuery( function () {
if ( sessionStorage.getItem('save_order') ) {
    alert( "Hello world" );
    sessionStorage.removeItem('save_order');
}
});
</script> -->