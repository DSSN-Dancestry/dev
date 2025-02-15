<?php include 'user_profile_popup.php'; ?>

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
<script>
  function closeModal() {
    $('#modal').css("display", "none");
    $('#modal-overlay').css("display", "none");

    if ($("#change_close_action").val() == "true") {
      window.location.href = $("#close_action_url").val();
    }
  };
</script>

<!-- modal -->
<div class="modal-overlay" id="modal-overlay"></div>
<div class="modal" id="modal">
  <div class="modal-guts">
    <div class="profile_container">
      <div class="row">
        <div style="height: 4%">
          <div class="large-12 column mrb10" id="prof_space_label"></div>
          <button class="close-button" id="close-button" style="position:fixed;right:35px;top:20px" onclick="closeModal();">X</button>
        </div>

        <div class="large-8 column">
          <div style="height: 65%">
            <div class="row" id="text_space">
            </div>
          </div>
          <div style="height: 4%">
            <div class="row" id="actions-section">
            </div>
          </div>
          <input type="hidden" name="change_close_action" id="change_close_action" />
          <input type="hidden" name="close_action_url" id="close_action_url" />
        </div>
        <div class="large-4 column profile-details-class" id="prof_space"></div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var page_notification_text = '';
  var code = '';
  var match_button_text = '';
  var none_button_text = '';
  var none_button_class = '';
  var buttonCode = '';
  var artist_profile_information = '';
  $(document).ready(function() {
    // By default click for a first profile
    $(".profileName").first().click();
  });

  function loadModal(resp) {
    // For text on top of the modal
    // 1 - Artist Profile
    if (source_page == 'add_user_profile') {
      // For my profile
      if ($("#is_user_artist").val() == 'artist') {
        page_notification_text = "It looks like someone may have already started a profile for you in Dancestry. Please review the list of potential matches below and see if one of them is you. If it is, we will transfer ownership of that profile to you so you can update and maintain your own information. If none of them are you, you can begin creating your profile.";
      }
      // For other artist
      else {
        page_notification_text = "The artist that you are adding may already exist in the Dancestry platform. Please review the existing artist profiles below to see if one of them matches the artist you would like to add.";
      }
    }
    // 2 - Add Lineage
    else {
      page_notification_text = "This artist may already exist in the Dancestry platform. Please review the existing artist profiles below to see if one of them matches the artist you would like to add.";
    }
    $('#prof_space_label').html(page_notification_text);

    // Table list
    code = '';
    code += '<div class="row">';
    code += '   <div class="large-9 column">';
    code += '       <table id="artist_lineals" class="display" style="width:100%;margin-left:8px;margin-right:2px;background-color:#eee;">';
    resp.forEach(function(item) {
      code += '       <tr class="profileName cursorp normalbgr" id="' + item.artist_profile_id + '"><td>';
      code += '           <div class="row">';
      code += '               <div class="small-8 column normalbgr">';

      code += '<div id=linkemail' + item.artist_profile_id + " style='display:none'>" + "" + item.artist_email_address + "</div>";

      code += "<div id=linkwebsite" + item.artist_profile_id + " style='display:none'>" + "" + item.artist_website + "</div>";


      code += '                   <p>' + item.artist_first_name + ' ' + item.artist_last_name + '<br>';
      code += '       Date of Birth:' + item.artist_dob + '<br>';
      // if(item.artist_email_address!="" && item.artist_email_address.indexOf('dummyemail@') === -1){
      // // code += '   Email:'+item.artist_email_address+'<br>';
      // }

      code += '                   </p>';
      code += '               </div>';
      code += '           </div>';
      code += '       </td></tr>';
    });
    code += '       </table>    ';
    code += '   </div>';

    // For text of yes/no buttons
    // 1 - Artist Profile
    if (source_page == 'add_user_profile') {
      none_button_class = 'secondary success button add_profile_yes';
      if ($("#is_user_artist").val() == 'artist') {
        match_button_text = 'This is me';
        none_button_text = 'None of the above are me. Begin creating my profile.';
        match_button_class = 'secondary success button mrt10 meProfile';
      } else {
        match_button_text = 'Match';
        none_button_text = 'None of the above match. Begin creating their profile.';
        match_button_class = 'secondary success button mrt10 matchProfile';
      }
    }
    // 2 - Add Lineage
    else {
      none_button_class = 'secondary success button noneofthem';
      match_button_text = 'Match';
      none_button_text = 'None of the above';
      match_button_class = 'secondary success button linkProfile mrt10 w120p';
    }

    // For yes buttons (Buttons next to profiles)
    code += '   <div class="large-3 column">';
    code += '       <table>';
    code += '           <tbody class="brz">';
    resp.forEach(function(item) {
      code += '           <tr class="bgn"><td>';
      code += '               <input type="button" id="' + item.artist_profile_id + '" class="' + match_button_class + '" value="' + match_button_text + '"/>';
      code += '           </tr></td>';
    });
    code += '           </tbody>';
    code += '       </table>';
    code += '   </div>';
    code += '</div>';
    // End table list
    $('#text_space').html(code);

    // For No buttons (Bottom left-center)
    buttonCode = '<div class="large-12 column tac"><input type="button" value="' + none_button_text + '" class="' + none_button_class + '"/></div>';
    $('#actions-section').html(buttonCode);

    // Display modal
    $('#prof_space').html("");
    $("#modal").css("display", "block");
    $("#modal-overlay").css("display", "block");
    $("#change_close_action").val("false");
    $("#close_action_url").val("");

    // By default click for a first profile
    $(".profileName").first().click();
  }

  // when a user clicks on a name in the "possible matches" list, load their profile
  // and display it in the right hand side of the popup
  $(document).on('click', '.profileName', function() {
    var artist_profile_id = $(this).attr('id');
    // $(".normalbgr").parent().removeClass('bgrlgr')
    // $(this).parent().parent().addClass('bgrlgr')


    $(".normalbgr").removeClass('bgrlgr')
    $(this).addClass('bgrlgr')
    var payloadForAristForm = {
      "action": "getFullProfile",
      "artist_profile_id": artist_profile_id
    };
    getUserProfile(payloadForAristForm);
    console.log('source_page is ' + source_page);
  });

  // When the user clicks on Link profile button
  $(document).on('click', '.linkProfile', function() {
    console.log("in the link profile");
    $("#modal").css("display", "none");
    $("#modal-overlay").css("display", "none");
    var artist_profile_id = $(this).attr('id');
    console.log(artist_profile_id);
    console.log('linkemail' + artist_profile_id);
    console.log(document.getElementById('linkemail' + artist_profile_id));
    var email = document.getElementById('linkemail' + artist_profile_id).innerHTML;
    console.log(artist_profile_id);
    if (email.indexOf('dummyemail@') !== -1) {
      document.getElementById('lineal_email_address').value = email;
      document.getElementById('email_display').value = "";
    } else {
      document.getElementById('email_display').value = email;
      document.getElementById('lineal_email_address').value = email;
    }
    console.log(artist_profile_id);
    var website = document.getElementById('linkwebsite' + artist_profile_id).innerHTML;
    var form_website = document.getElementById("lineal_website");
    form_website.value = website;
    // console.log("add");
    addingArtist();

    document.getElementById('lineal_email_address').value = "";
    document.getElementById('email_display').value = "";
    document.getElementById("lineal_website").value = "";

  });



  function MyProfileModal(artist_profile_information) {
    $('#prof_space_label').html('');
    $('#text_space').html('');
    $('#prof_space').css('display', 'none');
    $('#actions-section').html('');

    // change close action
    $("#change_close_action").val("true");
    $("#close_action_url").val("profiles.php");

    // For text
    page_notification_text = 'You are now the owner of the profile for ' + artist_profile_information.artist_first_name + ' ' + artist_profile_information.artist_last_name + '.';
    $('#prof_space_label').html(page_notification_text);

    // For Buttons
    buttonCode = '<div class="large-12 column tac mrt30">';
    buttonCode += ' <input type="button" value="Return to Profiles" class="secondary success button returnToProfiles"/>';
    buttonCode += '</div>';
    $('#text_space').html(buttonCode);
  }

  function OtherProfileOtherArtistModal(artist_profile_information) {
    $('#prof_space_label').html('');
    $('#text_space').html('');
    $('#prof_space').css('display', 'none');
    $('#actions-section').html('');

    // change close action
    $("#change_close_action").val("true");
    $("#close_action_url").val("profiles.php");

    // For text
    page_notification_text = artist_profile_information.parent_artist_profile_first_name + ' ' + artist_profile_information.parent_artist_profile_last_name + ' has already created a profile for this artist. Please <a href="help.php">contact the system administrator</a> if you’d like to edit, contribute to, or take over ownership of this artist’s profile.';
    $('#prof_space_label').html(page_notification_text);

    // For Buttons
    buttonCode = '<div class="large-6 column tac mrt30">';
    buttonCode += ' <input type="button" value="Contact the Administrator" class="secondary success button contactAdmin"/>';
    buttonCode += '</div>';
    buttonCode += '<div class="large-6 column tac mrt30">';
    buttonCode += ' <input type="button" value="Return to Profiles" class="secondary success button returnToProfiles"/>';
    buttonCode += '</div>';
    $('#text_space').html(buttonCode);
  }

  function openArtistProfile(artist_profile_information) {
    $.ajax({
      url: "artistcontroller.php",
      type: 'POST',
      data: JSON.stringify({
        "action": 'setArtistProfileSession',
        "artist_profile_information": artist_profile_information
      }),
      success: function(response) {
        console.log("openArtistProfile");
        window.location.href = 'add_artist_profile.php';
      }
    });
  }

  function updateArtistOwner(update_artist_owner, action = '', artist_profile_information = false) {
    $.ajax({
      url: "artistcontroller.php",
      type: 'POST',
      data: JSON.stringify({
        "action": 'updateArtistOwner',
        "artist_profile_information": update_artist_owner
      }),
      success: function(response) {
        if (action == 'openArtistProfile') {
          // Takes the user to  Artist’s Profile Information Page with that artist’s name in the name boxes.
          openArtistProfile(artist_profile_information);
        }
      }
    });
  }

  // send email to previous owner
  function sendEmailPreviousOwner(artist_profile_information) {
    $.ajax({
      url: "user_owner_mail.php",
      type: 'POST',
      data: {
        'artist_profile_information': artist_profile_information
      },
      success: function(response) {}
    });
  }

  // Get artist information
  function updateArtistInformation(artist_profile_id, source_click) {
    $.ajax({
      url: "artistcontroller.php",
      type: 'POST',
      data: JSON.stringify({
        "action": 'getArtistProfile',
        "artist_profile_id": artist_profile_id,
        "check_lineal_other": true
      }),
      success: function(response) {
        console.log('getArtistProfile response');
        console.log(response);

        artist_profile_information = response.artist_profile[0];

        if (source_click == 'meProfile') {
          if (artist_profile_information.other_profile) {
            console.log('other_profile');

            // update profile name
            // update past profile name
            var update_artist_owner = {};
            update_artist_owner.artist_profile_id = artist_profile_information.artist_profile_id;
            update_artist_owner.profile_name = $("#profile_name").val();
            update_artist_owner.artist_email_address = $("#profile_name").val();
            update_artist_owner.past_profile_name = artist_profile_information.profile_name;
            update_artist_owner.is_user_artist = 'artist';
            if (artist_profile_information.STATUS == '0') {
              update_artist_owner.STATUS = '25';
            }
            updateArtistOwner(update_artist_owner);

            // send email to past profile name (previous profile name)
            sendEmailPreviousOwner(artist_profile_information);
          } else {
            console.log('lineal_profile');

            // update profile name
            var update_artist_owner = {};
            update_artist_owner.artist_profile_id = artist_profile_information.artist_profile_id;
            update_artist_owner.profile_name = $("#profile_name").val();
            update_artist_owner.artist_email_address = $("#profile_name").val();
            update_artist_owner.STATUS = '25';
            updateArtistOwner(update_artist_owner);
          }
          MyProfileModal(artist_profile_information);
        } else if (source_click == 'matchProfile') {
          if (artist_profile_information.other_profile) {
            console.log('other_profile');
            OtherProfileOtherArtistModal(artist_profile_information);
          } else {
            console.log('lineal_profile');
            // update profile name
            var update_artist_owner = {};
            update_artist_owner.artist_profile_id = artist_profile_information.artist_profile_id;
            update_artist_owner.profile_name = $("#profile_name").val();
            update_artist_owner.STATUS = '25';
            updateArtistOwner(update_artist_owner, 'openArtistProfile', artist_profile_information);
          }
        }
      }
    });
  }

  // When the user clicks on This is me button
  $(document).on('click', '.meProfile', function() {
    artist_profile_id = $(this).attr('id');
    updateArtistInformation(artist_profile_id, 'meProfile');
  });

  // When the user clicks on Match button
  $(document).on('click', '.matchProfile', function() {
    artist_profile_id = $(this).attr('id');
    updateArtistInformation(artist_profile_id, 'matchProfile');
  });

  function updateLoadModal(artist_profile_information) {
    $('#prof_space_label').html('');
    $('#text_space').html('');
    $('#prof_space').css('display', 'none');
    $('#actions-section').html('');

    // For text
    page_notification_text = '<b>' + artist_profile_information.parent_artist_profile_first_name + ' ' + artist_profile_information.parent_artist_profile_last_name + '</b> has already created a profile for this artist. Please <a href="help.php">contact the system administrator</a> if you’d like to edit, contribute to, or take over ownership of this artist’s profile.';
    $('#prof_space_label').html(page_notification_text);

    // For Buttons
    buttonCode = '<div class="large-6 column tac">';
    buttonCode += ' <input type="button" value="Contact the Administrator" class="secondary success button contactAdmin"/>';
    buttonCode += '</div>';

    buttonCode += '<div class="large-6 column tac">';
    buttonCode += ' <input type="button" value="Return to Profiles" class="secondary success button returnToProfiles"/>';
    buttonCode += '</div>';
    $('#text_space').html(buttonCode);
  }

  $(document).on('click', '.contactAdmin', function() {
    console.log('contact admin');
    window.location.href = "help.php";
  });

  $(document).on('click', '.returnToProfiles', function() {
    console.log('return to profiles');
    window.location.href = "profiles.php";
  });

  $(document).on('click', '.matchAddLineage', function() {
    console.log('match add lineage');
    window.location.href = "add_lineage.php?source=artist_profile";
  });

  // $(document).on('click','.matchAddLineage',function(){
  //   console.log('match add lineage');
  //   window.location.href = "add_lineage.php?source=artist_profile";
  // });
</script>