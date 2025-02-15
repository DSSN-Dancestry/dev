<?php
include 'util.php';
require "utils.php";
my_session_start();
checkAdmin();
$showAdminMenu = true;
include 'menu.php';
include 'user_profile_popup.php';
?>
<title>Maintain Users | Dancestry</title>
<link href="css/global.css" rel="stylesheet">
<script src="js/platform.js"></script>
<script type="text/javascript" src="js/browserCheck.js"></script>
<script>
  window.onload = function() {
    strict_check();
  }
</script>
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



<style type="text/css">
  #usetTable_length {
    width: 170px;
  }

  #usetTable_length label {
    display: flex;
    justify-content: space-between;
  }

  #usetTable_length select {
    width: 50px;
  }

  #usetTable_filter label {
    text-align: left;
  }

  #usetTable_filter input {
    margin-left: 0px;
  }

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


.cursorp{
  cursor: pointer;
}
.bgn{
  background: none !important;
}
.brz{
  border: 0 !important;
}
.mrt10{
  margin-top: 10px;
}
.mrt30{
  margin-top: 30px;
}
.tac{
  text-align: center;
}
.w120p{
  width: 120px;
}
.mrb10{
  margin-bottom: 10px;
}

.popup-table, .popup-table-relation {
  display: none; 
  position: fixed; 
  z-index: 1; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4); 
}


.popup-table-content {
  background-color: #fefefe;
  margin: 15% auto; 
  padding: 20px;
  border: 1px solid #888;
  width: 80%; 
}

.close, .close-relation {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close-relation:hover,
.close-relation:focus,
.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<div class="popup-table" id="popup-table" style="display:none">
  <div class="popup-table-content" id="popup-tab-content">
    <span class="close">&times;</span>
    <p><b>Please select a single record as the Master Record.</b></p>
    <div id='merge_note'><p><b>Clicking the Merge button will merge all records into the Master Record.</b></p></div>
    <table id ='usetTablePopup' class='display'>
      <thead>
          <tr>
              <th width="200">Artist Name</th>
              <th width="200">Created By</th>
              <th width="200">Artist Email Address</th>
          </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
    <button class='alert hollow button' type='button' onclick=mergeData();>
    <span>Merge</span>
    </button>

    <button class='alert hollow button' id='mergeClose' type='button'>
    <span>Cancel</span>
    </button>
  </div>
</div>

<div class="popup-table-relation" id="popup-table-relation" style="display:none">
  <div class="popup-table-content" id="popup-tab-content">
    <span class="close-relation">&times;</span>
    <div id='relation_note'><p><b>The User has the following relations. Deleting the user details will also remove their relations.<br>Are you sure you want to delete the user details?</b></p></div>
    <table id ='usetTableRelationPopup' class='display'>
      <thead>
          <tr>
              <th width="200">First Artist Name</th>
              <th width="200">Second Artist Name</th>
              <th width="200">Artist Relation</th>
          </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <button class='alert hollow button' type='button' onclick=deleteUserRelation();>
      <span>Delete Anyways</span>
    </button>
    <button class='alert hollow button' id='popupClose' type='button'>
      <span>Cancel</span>
    </button>
  </div>
</div>

<div class="row">
  <div class="medium-12">
    <section>
      <!-- <form id="profiles_form" name="profiles_form" method="post" action="profiles_mediator.php" enctype="multipart/form-data"> -->
      <form id="delete_user_form" name="delete_user_form" method="post" action="delete_user_mediator.php" enctype="multipart/form-data">
				<fieldset>
					<legend><strong><h3>Users Present in System</h3></legend></strong>
          <legend><b>Please select the records to be merged.</b></legend>
					<div class="row">
            <div class="loader1" id="maintain-user-loader" style="position:relative;left:50%;"></div>
						<div class="small-12 column" id="maintain-user-div" style="display:none">
              <table id ='usetTable' class='display'>
                <thead>
                  <tr>
                    <th class='admin_td'>Artist Name</th>
                    <th class='admin_td'>Created By</th>
                    <th class='admin_td'>Artist Email Address</th>
                    <th class='admin_td'></th>
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
<div class="modal-overlay" id="modal-overlay"></div>
<div class="modal" id="modal">
  <div class="modal-guts">
    <div class="profile_container">
      <div class="row">
        <div style="height: 4%">
          <div class="large-12 column mrb10" id="prof_space_label"></div>
        </div>
        <div class="large-8 column">
          <input type="hidden" name="change_close_action" id="change_close_action" />
          <input type="hidden" name="close_action_url" id="close_action_url" />
        </div>
        <div class="large-4 column profile-details-class" id="prof_space"></div>
        <button class="close-button" id="close-button" onclick="closeModal();">X</button>
      </div>
    </div>
  </div>
</div>
<div class="footer" style="margin-top:4.5%">
  <?php
  include 'footer.php';
  ?>
</div>
<script>
  function closeModal() {
    $('#modal').css("display", "none");
    $('#modal-overlay').css("display", "none");

    if ($("#change_close_action").val() == "true") {
      window.location.href = $("#close_action_url").val();
    }
  };
</script>
<script>
  var table;
  var ckies = [];
  var delRels = [];
  var delArtistId = "";
  var delArtistLogID = "";

  function addArtistProfileLogs(operation){
		$.ajax({
			url:"logcontroller.php",
			type:'POST',
			data:JSON.stringify({
				"action":"addUserLogs",
				"data":{'user': '<?php echo($_SESSION['user_id']);?>', 'oper': operation, 'det': delArtistLogID}
			}),
			success:function(){
        delArtistLogID = "";
			}
		})
	}

	function confirmDelete(id){
    
    var popup = document.getElementById("popup-table-relation");
    var span = document.getElementsByClassName("close-relation")[0]
    span.onclick = function() {
      popup.style.display = "none";
      delArtistLogID = "";
    }

    var btn = document.getElementById("popupClose");
    btn.onclick = function() {
      popup.style.display = "none";
      delArtistLogID = ""
    }

    isUsed(id).then(response =>  {
        console.log("RESPONSE IS "+response);
        if (response){
          tableRelation = $('#usetTableRelationPopup').DataTable();
          fetch("artistrelationcontroller.php", {
                method: "post",
                body: JSON.stringify({action: "getArtistRelation", relation_Ids:response})
          })
          .then(res => res.json())
          .then(
            result => {
                    tableRelation.clear();
                    for (let i = 0; i < result['artist_relation'].length; i++){
                      
                      let relation = result['artist_relation'][i];

                      tableRelation.row.add([relation.artist_name_1, relation.artist_name_2, relation.artist_relation]).draw(false) ;
                    }
                    delArtistLogID = id;
              },
              error => {
                  alert("error! Fetching Relations.");
              }
          ).then(
            check => {
              $("#popup-table-relation").attr('style', 'display:block');
              delRels = response;
              delArtistId = id;
            }
          )
          return;
        }


      var c = confirm("Warning: You are about to delete this entire profile! Click 'OK' to delete.");
      if (c) {

        fetch("artistcontroller.php", {
            method: "post",
            headers: {
              'Content-Type': 'application/json',
            },
            mode: "cors",
            body: JSON.stringify({
              action: "deleteArtistProfile",
              artist_profile_id: "" + id
            })
          })
          .then(res => res.json())
          .then(
            result => {
              //console.log("cool beans");
              loadUsers();
              delArtistLogID = id;
            addArtistProfileLogs("Deleted");
          },
            error => {
              alert("error " + error);
            }
          );
      }
    });
  }

    function isUsed (id) {
      return fetch("artistrelationcontroller.php", {
          method: "post",
          headers: {
            'Content-Type': 'application/json',
          },
          mode:"cors",
          body: JSON.stringify({action: "getArtistRelation", artist_profile_id_2:""+id, artist_profile_id_1:""+id, query_relation:"OR" })
  
      })
      .then(res => res.json())
      .then(
        result => {
          if (result['artist_relation']) {
                var delID = [];
                for(var i = 0; i < result['artist_relation'].length; i++){
                    delID[i] = result['artist_relation'][i]['relation_id'];
                }
            return delID;
          } else {
            return false;
          }
        },
        error => {
          alert("error " + error);
        }
      );
  }


    function loadUsers(callback){

    fetch("artistcontroller.php", {
        method: "post",
        body: JSON.stringify({
          action: "getArtistProfile"
        })
      })
      .then(res => res.json())
      .then(
        result => {

                  table.clear();
                  for (let i = 0; i < result['artist_profile'].length; i++){

                    if (result['artist_profile'][i]["is_deleted"] == 'false'){

                      let profile = result['artist_profile'][i];

                      let chkbox = "<td><input type='checkbox' class='mrgids' value="+ profile.artist_profile_id +" />&nbsp;</td>";
                      let artistName = profile.artist_first_name+"- "+profile.artist_last_name;


                      let viewButton = "<button class='button mrt10'' type='button' name='view_btn' id=" + profile.artist_profile_id + " onclick='viewArtistProfile("+profile.artist_profile_id+");'>";
                        viewButton += "<span>View</span>";
                        viewButton += "</button>";

                      let editButton = "<button class='primary button mrt10' type='submit' formaction='profiles_mediator.php' name='artist_profile_edit' value="+profile.artist_profile_id+">";
                        editButton += "<span>Edit</span>";
                        editButton += "</button>";

                        let deletebutton = "<button class='alert button mrt10' type='button' name='genre_id' id=" + profile.artist_profile_id + " onclick='confirmDelete("+profile.artist_profile_id+");'>";
                        deletebutton += "<span>Delete</span>";
                        deletebutton += "</button>";

                      let email = "";
                      if (profile.artist_email_address && profile.artist_email_address.startsWith("dummy")) {
                          email = "System Generated"
                      } else {
                          email = profile.artist_email_address;
                      }

                      table.row.add([chkbox+' '+profile.artist_first_name+' '+profile.artist_last_name, profile.profile_name, email, viewButton + ' ' + editButton + ' ' + deletebutton]).draw(false) ;

                    }
                }
            },
            error => {  
                alert("error! delete_user");
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

  function viewArtistProfile(artist_profile_id) {

  var payloadForArtistForm = {
                            "action": "getFullProfile",
                            "artist_profile_id":artist_profile_id,
                            "add_log":"True"
                          };
  getUserProfile(payloadForArtistForm);

  $('#prof_space').html("");
  $("#modal").css("display", "block");
  $("#modal-overlay").css("display", "block");
  $("#change_close_action").val("false");
  $("#close_action_url").val("");
}

function showData(){
  var popup = document.getElementById("popup-table");
  var span = document.getElementsByClassName("close")[0]
  span.onclick = function() {
    popup.style.display = "none";
  }

  var btn = document.getElementById("mergeClose");
  btn.onclick = function() {
    popup.style.display = "none";
  }
  if(ckies.length < 2){
    alert("Please select more than 1 users to merge the records");
  }else{
    tableMerge = $('#usetTablePopup').DataTable();
    fetch("artistcontroller.php", {
          method: "post",
          body: JSON.stringify({action: "getArtistProfile", "artist_profile_ids":ckies})
    })
    .then(res => res.json())
    .then(
      result => {
              tableMerge.clear();

              for (let i = 0; i < result['artist_profile'].length; i++){

                let profile = result['artist_profile'][i];

                if (ckies.includes(String(profile.artist_profile_id))){

                  let radioBtn = "<input value="+ profile.artist_profile_id +" id='radioBtn' name='master_radio' type='radio' />";

                  let email = "";
                  if (profile.artist_email_address && profile.artist_email_address.startsWith("dummy")) {
                      email = "System Generated"
                  } else {
                      email = profile.artist_email_address;
                  }
                  delArtistLogID += profile.artist_profile_id+","
                  tableMerge.row.add([radioBtn+' '+profile.artist_first_name+' '+profile.artist_last_name, profile.profile_name, email]).draw(false) ;
              }
            }
        },
        error => {
            alert("error! delete_user");
        }
    ).then(
      check => {
        $("#popup-table").attr('style', 'display:block');
      }
    )
  }
}

function mergeData(){
  var masterID = $('#radioBtn:checked').val();
  const index = ckies.indexOf(masterID);
  ckies.splice(index, 1);
  ids = ckies;
  ckies = [];
  $(".mrgids", $('#usetTable').DataTable().rows().nodes()).each(function () { 
    $(this).prop("checked", false);
  });
  if(masterID != undefined){
    fetch("merge_artist.php", {
          method: "post",
          body: JSON.stringify({action: "mergeArtistDetails", "artist_profile_ids":ids, "master_id":masterID})
    })
    .then(res => res.json())
    .then(
      result => {
        console.log(result);
        console.log("Merge Successful.");
        loadUsers();
        addArtistProfileLogs("Merged");
        alert("Records Merge Successfully.");
        document.getElementById("popup-table").style.display = "none";
      },
      error => {
        console.log(error);
        console.log("Unable to merge artists details.");
        alert("Unable to merge artists details.");
      }
    )
  }else{
    alert("Please Select a Master Record to merge!");
  }
}

function deleteUserRelation(){
  if(delArtistId != "" && delRels.length > 0){

    fetch("artistrelationcontroller.php", {
        method: "post",
        headers: {
          'Content-Type': 'application/json',
        },
        mode:"cors",
        body: JSON.stringify({action: "deleteArtistRelation", relation_Ids:delRels })
    })
    .then(res => res.json())
    .then(
        result => {
          fetch("artistcontroller.php", {
              method: "post",
              headers: {
                'Content-Type': 'application/json',
              },
              mode:"cors",
              body: JSON.stringify({action: "deleteArtistProfile", artist_profile_id:""+delArtistId })
          })
          .then(res => res.json())
          .then(
              result => {
                alert("User Deleted Successfully");
                loadUsers();
                addArtistProfileLogs("Deleted");
              },
              error => {
                  alert("error "+error);
              }
          )
          .then(
            check => {
              document.getElementById("popup-table-relation").style.display = "none";
              delRels = [];
              delArtistId = "";
            }
          );
        },
        error => {
            alert("error "+error);
        }
    );
  }
}

$(document).ready( function () {
  $("#event").css("background-color","#ddd");
  table = $('#usetTable').DataTable( {
    "dom": 'l<"mrgbtn">ftipr'
  });
  loadUsers(function callbackFunction() {
        $(".mrgbtn").css({"height": "70px", "width": "50%", "display": "inline-block"});
        $(".mrgbtn").html("<button class='alert hollow button' type='button' name='merge_button' id='merge_button' style='margin-top:25px;margin-left:20px' onclick='showData();'> <span>Select</span> </button>");
        $('#maintain-user-loader').attr('style', 'display:none');
        $('#maintain-user-div').removeAttr("style");
        $("#usetTable").on('change',"input[type='checkbox']",function(e){
          if(this.checked) {
              ckies.push(this.value);
          }else{
              const index = ckies.indexOf(this.value);
              ckies.splice(index, 1);
          }
        });
  });
});
</script>