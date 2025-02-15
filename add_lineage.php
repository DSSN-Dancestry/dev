<?php
require 'util.php';
my_session_start();

// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
  header('Location: login.php');
  exit;
}
$_SESSION["timeline_stage"] = "lineage";
include 'menu.php';

if ($_SESSION["timeline_flow"] == "view") {
  echo "<script>var disabled_input=true;</script>";
} else {
  echo "<script>var disabled_input=false;</script>";
}

if (isset($_SESSION['artist_first_name'])) {
  $artist_fname = $_SESSION["artist_first_name"];
} else {
  $artist_fname = '';
}
if (isset($_SESSION['artist_last_name'])) {
  $artist_lname = $_SESSION["artist_last_name"];
} else {
  $artist_lname = '';
}
if (isset($_SESSION['artist_email_address'])) {
  $artist_email = $_SESSION["artist_email_address"];
} else {
  $artist_email = '';
}
if (isset($_SESSION['artist_first_name']) && isset($_SESSION['artist_last_name'])) {
  $artist_fullname = $artist_fname . ' ' . $artist_lname;
} else {
  $artist_fullname = '';
}
if (isset($_GET['source']) && $_GET['source'] == 'artist_profile' && isset($_SESSION['lineal_added']) && !$_SESSION['lineal_added']) {
  if (isset($_SESSION['lineal_first_name'])) {
    $lineal_first_name = $_SESSION["lineal_first_name"];
  } else {
    $lineal_first_name = '';
  }
  if (isset($_SESSION['lineal_last_name'])) {
    $lineal_last_name = $_SESSION["lineal_last_name"];
  } else {
    $lineal_last_name = '';
  }
  $_SESSION['lineal_added'] = true;
} else {
  $lineal_first_name = '';
  $lineal_last_name = '';
}

?>

<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="stylesheet" href="dist/vis-network.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/lineage_style.css" type="text/css" />
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <title>Add Lineage | Dancestry</title>
  <link href="css/progressbar.css" rel="stylesheet">
  <style type="text/css">
    #display_relations_length {
      width: 170px;
    }

    #display_relations_length label {
      display: flex;
      justify-content: space-between;
    }

    #display_relations_length select {
      width: 50px;
    }

    #display_relations_filter label {
      text-align: left;
    }

    #display_relations_filter input {
      margin-left: 0px;
    }

    #lineal_artist_details {
      display: flex;
    }

    #type_of_relationship {
      display: flex;
    }

    #contribute_lineage_button {
      text-align: right;
      margin: 0;
      padding: 0px;
      padding-top: 0px;
      width: unset;
      padding-right: 10px;
    }

    #previous,
    #save,
    #next1 {
      margin: 0px;
    }

    @media only screen and (max-width: 1000px) {
      #title_row {
        padding-left: 10px;
      }

      #lineal_artist_details {
        display: flex;
        flex-direction: column;
      }

      #type_of_relationship {
        flex-direction: column;
      }

      #prev_save_row {
        display: flex;
        padding-bottom: 10px;
      }

      #contribute_lineage_button {
        width: 100%;
        text-align: right;
        padding-top: 0px;
        padding-left: 5px;
      }

      #previous,
      #save,
      #next1 {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <?php include 'progressbar.php'; ?>
  <?php include 'add_artist_references.php'; ?>
  <input type="hidden" name="is_user_artist" id="is_user_artist" value="<?php echo (($_SESSION['contribution_type'] == "own") ? 'artist' : 'other')  ?>">
  <div id="title_row" class="row">
    <h2 style="display:inline; align:center;"><strong>ENTER ARTIST'S LINEAGE</strong></h2>
    <div class="add-reference-button" style="display: none;" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div>
    <h5 style="display:inline; float: right; color: #006400;"></h5>
    <div class="medium-9 row" style="margin: 0;">
      <p>
        <i>
          Lineal artists are the people with whom an artist has studied, danced, collaborated and have been influenced by.
        </i>
      </p>
    </div>
  </div>
  <?php include 'user_list.php'; ?>
  <form id="add_user_profile_form" name="add_user_profile_form" method="POST" style="margin-left:10px;" action="thank_you_contribution.php" enctype="multipart/form-data">
    <div class="row artist_lineage_container" id="artist_lineage_container">
      <div class="medium-12 column">
        <div id="searchbox_row" class="row">
          <section>
            <fieldset>
              <div class="row">
                <div class="column">
                  <p class="lineal_header"><strong>Details of Lineal Artist</strong></p>
                </div>
              </div>
              <div style="background:#ddd; border-radius:10px; padding:10px; margin-bottom:15px;">
                <div id="lineal_artist_details" class="row">
                  <div class="small-2 column" style="width: 100%;">
                    <label for="lineal_last_name">Last Name
                      <large style="color:red;font-weight: bold;"> *</large>
                      <input autocomplete="on" type="search" class="lineal_last_name" id="lineal_last_name" name="lineal_last_name" placeholder="Last Name" value="<?php echo $lineal_last_name ?>" />
                    </label>
                  </div>
                  <div class="small-2 column" id="lineal_first_name_div" style="width: 100%;">
                    <label for="lineal_first_name">First Name
                      <large style="color:red;font-weight: bold;"> *</large>
                      <input autocomplete="on" type="search" class="lineal_first_name" id="lineal_first_name" name="lineal_first_name" placeholder="First Name" value="<?php echo $lineal_first_name ?>" />
                    </label>
                  </div>
                  <div class="small-2 column" style="width: 100%;">
                    <label for="relation_genre">Dance Genre(s)
                      <br>
                      <select id="relation_genre" name='genre[]' class="multi-select-dd" style="background:white;" multiple="multiple">
                      </select>
                    </label>
                  </div>

                  <div id="newGenreDiv" class="small-12 column additional_genress_as_id">
                    <label for="relation_user_genres">Additional Genres&nbsp; &nbsp;<img src="img/help.png" class="h13p w13p cursorp" onclick="readGenreNote()"></img>
                    </label>
                    <input id="relation_user_genres" style="max-width: 575px;" name='relation_user_genres' type="text" placeholder="Genre1, Genre2">
                    </label>
                  </div>


                  <div class="small-2 column" style="width: 100%;">
                    <label for="lineal_email_address">Email Address <small></small>
                      <input autocomplete="off" type="email" class="email_display" id="email_display" name="email_display" placeholder="Email Address" />
                      <input autocomplete="off" type="hidden" value="dummyhiddenemail" class="lineal_email_address" id="lineal_email_address" />
                    </label>
                  </div>
                  <div class="small-2 column" style="width: 100%;">
                    <label for="lineal_website">Website <small></small>
                      <input autocomplete="off" type="url" class="lineal_website" id="lineal_website" name="lineal_website" placeholder="Website" />
                    </label>
                  </div>
                </div>
                <div class="row">
                  <div class="small-12 column">
                    <label>Type of Relationship (Check All that Apply):</label>
                  </div>
                </div>
                <div id="type_of_relationship" class="row">
                  <div class="small-3 column" style="width: 100%;">
                    <input type="checkbox" id="studied" name="studied" class="rel_studied" value="Studied Under">
                    <label for="studied">Studied Under</label>
                    <!-- <span title="Teachers with whom you have studied."> -->
                    <img src="img/help.png" class="h13p w13p cursorp" onclick="readDefinitions_2()" />
                    <!-- </span> -->
                  </div>
                  <div class="small-3 column" style="width: 100%;">
                    <input type="checkbox" id="danced" name="danced" class="rel_danced" value="Danced in the Work of">
                    <label for="danced">Danced in the Work of </label>
                    <!-- <span title="Choreographers whose works you have danced in."> -->
                    <img src="img/help.png" class="h13p w13p cursorp" onclick="readDefinitions_1()" />
                    <!-- </span> -->
                  </div>
                  <div class="small-3 column" style="width: 100%;">
                    <input type="checkbox" id="collaborated" name="collaborated" class="rel_collaborated" value="Collaborated With">
                    <label for="collaborated">Collaborated With </label>
                    <!-- <span title="Artists with whom you have collaborated."> -->
                    <img src="img/help.png" class="h13p w13p cursorp" onclick="readDefinitions_3()" />
                    <!-- </span> -->
                  </div>
                  <div class="small-3 column" style="width: 100%;">
                    <input type="checkbox" id="influenced" name="influenced" class="rel_influenced" value="Influenced By">
                    <label for="influenced">Influenced By </label>
                    <!-- <span title="People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors, theorists, etc.  You do not need to have a relationship with this person in order to list them as having an impact on  your work."> -->
                    <img src="img/help.png" class="h13p w13p cursorp" onclick="readDefinitions_4()" />
                    <!-- </span> -->
                  </div>
                </div>
                <div class="row">
                  <div class="column">
                    <div id="danced_options" style="display:none;">
                      <p>Please list the titles of the works you have danced in by this artist. (separate multiple titles with a comma)</p>
                      <input type="text" id="danced_titles" class="danced_titles" name="Danced_titles" placeholder="Dance titles">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row artist_button">
                <div class="large-12 columns" style="display:flex;align-items:center;justify-content:center">
                  <button class="secondary success button " style="background-color:#65ba79;" id="addingartist" type="button" onclick="addingArtist()">
                    <span>Save and Add another Artist</span>
                  </button>
                </div>
              </div>
              <div id='test'>
              </div>
              <div class="row">
                <div class="column">
                  <p class="lineal_header"><strong>Current Lineage</strong></p>
                </div>
              </div>
              <div class="row">
                <div class="medium-12 column" style="background:#DDD;border-radius:10px;padding :10px;margin-bottom:15px;width:98%;margin-left:13px;">
                  <table id="display_relations" class="display" style="width:100%;margin-left:auto;margin-right:auto;">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Website</th>
                        <th>Relation</th>
                        <th>Edit / Delete</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>

              <!-- definitions dialog -->
              <div id="extraControls" style="display: none;">
                <div id="dialog-2" style="font-weight: bold;height:700px;background-color:#E7FBE9" title="Definitions">
                  <div class="row" id="dialog_2_box_data">
                    <p>There are four types of lineal lines or <strong>Relationships:</strong></p>
                    <p>
                    <p style="font-weight: normal">1. <strong>DANCED IN THE WORK OF </strong> - Choreographers whose work you have danced in.</p>
                    <p style="font-weight: normal">2. <strong>STUDIED UNDER</strong> - Teachers under whom you have studied.</p>
                    <p style="font-weight: normal">3. <strong>COLLABORATED WITH</strong> - Artists with whom you have collaborated.</p>
                    <p style="font-weight: normal">4. <strong>INFLUENCED BY </strong> - People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors,
                      theorists, etc. You do not need to have a relationship with this person in order to list them as having an impact on your work.</p>
                    </p>
                  </div>
                  <button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions();">
                    <span>OK</span>
                  </button>
                </div>
              </div>


              <div id="extraControls" style="display: none;">
                <div id="dialog-3" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Definitions">
                  <div class="row" id="dialog_3_box_data">
                    <p style="font-weight: normal"><strong>DANCED IN THE WORK OF </strong> - Choreographers whose work you have danced in.</p>
                  </div>
                  <button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions_1();">
                    <span>OK</span>
                  </button>
                </div>
              </div>

              <div id="extraControls" style="display: none;">
                <div id="dialog-4" style="font-weight: bold;height:700px;background-color:#E7FBE9" title="Definitions">
                  <div class="row" id="dialog_4_box_data">
                    <p style="font-weight: normal"><strong>STUDIED UNDER</strong> - Teachers under whom you have studied</p>
                  </div>
                  <button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions_2();">
                    <span>OK</span>
                  </button>
                </div>
              </div>



              <div id="extraControls" style="display: none;">
                <div id="dialog-5" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Definitions">
                  <div class="row" id="dialog_5_box_data">
                    <p style="font-weight: normal"><strong>COLLABORATED WITH </strong> - Artists with whom you have collaborated.</p>
                  </div>
                  <button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions_3();">
                    <span>OK</span>
                  </button>
                </div>
              </div>

              <div id="extraControls" style="display: none;">
                <div id="dialog-6" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Definitions">
                  <div class="row" id="dialog_6_box_data">
                    <p style="font-weight: normal"><strong>INFLUENCED BY</strong> - People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors, theorists, etc. You do not need to have a relationship with this person in order to list them as having an impact on your work.</p>
                  </div>
                  <button class="button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions_4();">
                    <span>OK</span>
                  </button>
                </div>
              </div>
              <div class="row">
                <div id="prev_save_row">
                  <div class="prev_button large-4 small-8 columns ">
                    <button style="font-style: normal;" class="primary button" id="previous" type="button" onclick="window.open('add_artist_biography.php','_self')">
                      <span>&lt; Previous</span>
                    </button>
                  </div>
                  <div id="contribute_lineage_button" class="large-3 small-8 columns">
                    <button class="button" type="submit" name="save" id="save">
                      <span>Contribute Lineage</span>
                    </button>
                  </div>
                </div>
                <div class="save_and_continue large-5 small-12 columns">
                  <button style="font-style: normal;" class="secondary button" id="next1" name="next1" type="button" onclick="window.open('profiles.php','_self');">
                    <span>Save & Continue Later</span>
                  </button>
                </div>
                <!-- <div class="column">
              </div> -->
              </div>

              <!-- <div class="row">
              <?php if ($_SESSION["timeline_flow"] == "relation_add" || $_SESSION["timeline_flow"] == "add_lineage") : ?>
                <div class="large-10">
                  <button class="button" type="button" name="home" id="home" onclick="window.open('login.php','_self');">
                    <span>Back to Profile</span>
                  </button>
                  &nbsp;
                  <button class="button" type="button" name="next1" id="next1" onclick="window.open('profiles.php','_self');">
                    <span>Save and come back later</span>
                  </button>
                  &nbsp;
                  <button class="button" type="submit" name="save" id="save">
                    <span>Save and Contribute Lineage</span>
                  </button>
                </div>
              <?php endif; ?>
              <?php if ($_SESSION["timeline_flow"] == "artist_add") : ?>
                <div class="large-10">
                  <button class="button " type="button" name="previous" id="previous" onclick="window.open('add_artist_biography.php','_self');">
                    <span>&lt; Previous</span>
                  </button>
                   &nbsp;
                  <button class="button" type="button" name="next1" id="next1" onclick="window.open('profiles.php','_self');">
                    <span>Save and come back later</span>
                  </button>
                  &nbsp;
                  <button class="button" type="submit" name="next" id="next">
                    <span>Save and Contribute Lineage &gt;</span>
                  </button>
                </div>
              <?php endif; ?>
              <?php if ($_SESSION["timeline_flow"] == "edit") : ?>
                <div class="large-10">
                  <button class="button" type="button" name="previous" id="previous" onclick="window.open('add_artist_biography.php','_self');">
                    <span>&lt; Previous</span>
                  </button>
                    &nbsp;
                  <button class="button" type="button" name="next1" id="next1" onclick="window.open('profiles.php','_self');">
                    <span>Save and come back later</span>
                  </button>
                  &nbsp;
                  <button class="button" type="submit" name="save" id="save">
                    <span>Save and Contribute Lineage &gt;</span>
                  </button>
                </div>
                <div id="terms_validation" style="red"></div>
              <?php endif; ?>

              <div class="column">
              </div>
            </div> -->
        </div>
        </fieldset>
        </section>
      </div>
    </div>
    </div>
  </form>



  <div id="extraControls" style="display: none;">
      <div id="dialog-gen" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Genre Note">
          <div class="row">
              <p style="font-weight: normal">If you are unable to find the genre you want in the dropdown list, you can add additional genres here.</p>
          </div>
          <button class="primary button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeGenreNote();">
              <span>OK</span>
          </button>
      </div>
  </div>

  

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link href="css/fSelect2.css" rel="stylesheet">
  <script src="js/fSelect.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.3/bootbox.min.js"></script>


  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  </head>

  <?php
  include 'footer.php';
  ?>
  <script>
    // Code for auto complete
    var artist_first_name_last_name = new Array();
    var artist_last_name_first_name = new Array();
    var artist_full_name_f_l_list = {};
    var artist_full_name_l_f_list = {};
    var first_call = true;

    var comboTree1;
    var table;
    var isArtistEntryFormPopulated = false;
    var artistIdInForm = null;

    // For user profile popup
    var source_page = 'add_lineage';

    function clearFormEvent() {
      isArtistEntryFormPopulated = false;
      artistIdInForm = null;

      var genreList = document.getElementById('relation_genre');
      genreListLength = genreList.options.length;
      for (var i = 0; i < genreListLength; i++) {
        genreListOption = genreList.options[i];
        genreListValue = genreList.options[i].value;

        genreListOption.selected = false;
      }
      $('.multi-select-dd').fSelect('reload');

      $('#add_user_profile_form')[0].reset();

    }

    function getData() {

      table = $("#display_relations").DataTable({
        "ajax": {
          "type": "POST",
          "url": "artistrelationcontroller.php",
          "data": function(d) {
            return JSON.stringify({
              "action": "getArtistWithGroupedRelations",
              "artist_profile_id_1": '<?php echo $_SESSION["artist_profile_id"] ?>',
              "artist_profile_id_2": ""
            });
          },
          "dataSrc": function(json) {
            // manipulate your data (json)
            console.log(json)
            response = JSON.stringify(json);
            jsonData = $.parseJSON(response);
            jsonData = jsonData.artist_relation;
            if (jsonData) {
              jsonData.forEach(function(i) {
                // console.log(i)
                if (i.artist_email_id_2.indexOf('dummyemail@') !== -1) {
                  // console.log(i)
                  i.artist_email_id_2 = "--";
                }
                if (i.artist_website_2.indexOf('null') !== -1) {
                  i.artist_website_2 = "";
                }
              });
            } else {
              jsonData = "";
            }
            // console.log(jsonData);
            // return the data that DataTables is to use to draw the table
            return jsonData;
          }
        },
        columns: [{
            "data": "artist_name_2"
          },
          {
            "data": "artist_email_id_2"
          },
          {
            "data": "artist_website_2"
          },
          {
            "data": "artist_relation"
          },
          {
            data: null,
            className: "center",
            defaultContent: '<a class="editor_edit">Edit</a> / <a class="editor_remove">Delete</a>'
          }
        ]
      });
    }

    // Ajax request to prepare the list for First names and Last names
    function getArtistName(obj_name, source_name) {
      $.ajax({
        type: "POST",
        url: 'artistcontroller.php',
        data: JSON.stringify({
          "action": "getArtistProfile"
        }),
        async: false,
        success: function(response) {
          response = JSON.stringify(response);
          artistNames = $.parseJSON(response);
          finalNames = artistNames.artist_profile;
          if (finalNames) {
            for (var i = 0; i < finalNames.length; i++) {
              fullName_f_l = finalNames[i].artist_first_name + " " + finalNames[i].artist_last_name;
              fullName_l_f = finalNames[i].artist_last_name + " " + finalNames[i].artist_first_name;

              artist_first_name_last_name.push(fullName_f_l);
              artist_last_name_first_name.push(fullName_l_f);
              artist_full_name_f_l_list[fullName_f_l] = {};
              artist_full_name_f_l_list[fullName_f_l]['artist_first_name'] = finalNames[i].artist_first_name;
              artist_full_name_f_l_list[fullName_f_l]['artist_last_name'] = finalNames[i].artist_last_name;

              artist_full_name_l_f_list[fullName_l_f] = {};
              artist_full_name_l_f_list[fullName_l_f]['artist_first_name'] = finalNames[i].artist_first_name;
              artist_full_name_l_f_list[fullName_l_f]['artist_last_name'] = finalNames[i].artist_last_name;
            }
          }
        },
        error: function(response) {
          console.log("Unable to fetch artist names");
        },
        done: function(response) {
          completeArtistName(obj_name, source_name);
        }
      });
    }


    // code for the autocomplete lineal_first_name
    $('#lineal_first_name').on('keypress', function() {
      if (first_call) {
        getArtistName('lineal_first_name', artist_first_name_last_name);
        first_call = false;
      } else {
        completeArtistName('lineal_first_name', artist_first_name_last_name);
      }
    });

    // code for the autocomplete lineal_first_name
    $('#lineal_last_name').on('keypress', function() {
      if (first_call) {
        getArtistName('lineal_last_name', artist_last_name_first_name);
        first_call = false;
      } else {
        completeArtistName('lineal_last_name', artist_last_name_first_name);
      }
    });

    $("#" + artist_first_name_last_name).autocomplete.filter = function(array, term) {
      var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i");
      return $.grep(array, function(value) {
        return matcher.test(value.label || value.value || value);
      });
    };

    $("#" + artist_last_name_first_name).autocomplete.filter = function(array, term) {
      var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i");
      return $.grep(array, function(value) {
        return matcher.test(value.label || value.value || value);
      });
    };

    function completeArtistName(obj_name, source_name) {
      $obj_name = $("#" + obj_name);
      $obj_name.autocomplete({
        minLength: 3, // minimum of 3 characters to be entered before suggesting artist names
        // function to sort the list from beginning of string
        source: function(request, response) {
          var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
          response($.grep(source_name, function(item) {
            return matcher.test(item);
          }));
        },
        select: function(event, ui) {
          if (isNaN(this.value)) {
            if (obj_name == 'lineal_first_name') {
              $("#lineal_last_name").val(artist_full_name_f_l_list[ui.item.label]['artist_last_name']);
              ui.item.value = artist_full_name_f_l_list[ui.item.label]['artist_first_name'];
            } else if (obj_name == 'lineal_last_name') {
              $("#lineal_first_name").val(artist_full_name_l_f_list[ui.item.label]['artist_first_name']);
              ui.item.value = artist_full_name_l_f_list[ui.item.label]['artist_last_name'];

              // Disply first name div when
              $("#lineal_first_name_div").show();
            }
          } else {
            $("#obj_name").val("");
          }
        }
      });
    }

    function loadGenres() {
      fetch("genrecontroller.php", {
          method: "post",
          body: JSON.stringify({
            action: "getGenres"
          })
        })
        .then(res => res.json())
        .then(
          result => {
            console.log(result['genres']);

            let genreList = document.getElementById('relation_genre');
            for (let i = 0; i < result['genres'].length; i++) {
              let genre = result['genres'][i];
              let opt = document.createElement('option');
              opt.text = genre.genre_name;
              opt.value = genre.genre_id;
              genreList.add(opt);

            }

            // style the list of genres with
            // checkboxes
            $('.multi-select-dd').fSelect();

          },
          error => {
            alert("error! loadgenres_add_lineage");
          }
        );
    }


    // Display textbox for only last name. The first name textbox can be displayed only when we fill last name or select last name from type ahead
    function hideFirstName() {
      $("#lineal_first_name_div").hide();
      $("#lineal_last_name").focusout(function() {
        $("#lineal_first_name_div").show();
        $("#lineal_first_name").focus();
      });
    }

    // various bindings that need to wait for the page to load before we can add them
    $(document).ready(function() {

      if ($("#is_user_artist").val() == 'other') {
        if ($("#profile_complete_status").val() == '0') {
          showRefNote();
        }
        $(".add-reference-button").show();
      } else {
        $(".add-reference-button").hide();
      }
      // load the current list of relations
      getData();

      //load the list of genres from the database
      loadGenres();

      // Display definitions of the 4 Relationships
      readDefinitions();

      // hide div for first name
      if ($("#lineal_first_name").val() == '') {
        hideFirstName();
      }

      // show or hide the list of works you've danced in, if you mark that as one of the
      // relationship types when you add a relation.
      $('#danced').change(function() {
        if (this.checked) {
          $("#danced_options").fadeIn('slow');
        } else {
          $("#danced_options").fadeOut('slow');
        }
      });

      //delete relation
      $('#display_relations').on('click', 'a.editor_remove', function(e) {

        var deletedrow = table.row($(this).parents('tr')).data();
        //console.log(deletedrow.artist_profile_id_2);
        $.ajax({
          type: "POST",
          url: 'artistrelationcontroller.php',
          data: JSON.stringify({
            "action": "deleteArtistRelationWithOtherIdentifiers",
            "artist_profile_id_2": deletedrow.artist_profile_id_2
          }),
          success: function(response) {
            $('#display_relations').DataTable().ajax.reload();

          }
        });
      })


      // edit existing relations.  This loads the information for the relation back
      // into the edit form, so that they can update the record
      $('#display_relations').on('click', 'a.editor_edit', function(e) {
        $("#lineal_first_name_div").show();

        // read the data for the row using some datatables black magic
        var editedrow = table.row($(this).parents('tr')).data();
        //console.log(editedrow);

        isArtistEntryFormPopulated = true;
        artistIdInForm = editedrow.artist_profile_id_2;
        var relationString = editedrow.artist_relation;
        var relation_array = relationString.split(',');

        // if the artist danced in the work of this lineal artist,
        // load the works that they danced in
        if (relation_array.includes('Danced in the Work of')) {
          $.ajax({
            type: "POST",
            url: 'artistrelationcontroller.php',
            data: JSON.stringify({
              "action": "getArtistRelation",
              "artist_profile_id_1": <?php echo $_SESSION['artist_profile_id'] ?>,
              "artist_profile_id_2": editedrow.artist_profile_id_2,
              "artistrelation": 'Danced in the Work of'
            }),
            success: function(response) {
              console.log(<?php echo $_SESSION['artist_profile_id'] ?>);
              console.log(editedrow.artist_profile_id_2);
              response = JSON.stringify(response);
              jsonData = $.parseJSON(response);
              jsonData = jsonData.artist_relation[0];
              $('#danced_titles').val(jsonData.works);
              $("#danced_options").fadeIn('slow');
            }
          });
        }

        // load the lineal artist from the DB and display the info
        $.ajax({
          type: "POST",
          url: 'artistrelationcontroller.php',
          data: JSON.stringify({
            "action": "getArtistRelation",
            "artist_profile_id_1": '<?php echo $_SESSION["artist_profile_id"] ?>',
            "artist_profile_id_2": editedrow.artist_profile_id_2
          }),
          success: function(response) {
            response = JSON.stringify(response);
            jsonData = $.parseJSON(response);
            jsonData = jsonData.artist_relation;
            jsonData = jsonData[0];
            //console.log(jsonData);
            let name = jsonData.artist_name_2;

            if (name) {
              let nameParts = name.split("-");

              $('#lineal_first_name').val(nameParts[0]);
              $('#lineal_last_name').val(nameParts[1]);
            }
            $('#lineal_email_address').val(jsonData.artist_email_id_2);
            // if the email address of the lineal artist is not a fake one,
            // display it in the form. If it is, clear it out in the event there was a value there
            if (!jsonData.artist_email_id_2.includes("dummy")) {
              $('#email_display').val(jsonData.artist_email_id_2);
            } else {
              $('#email_display').val("");
            }
            $('#lineal_website').val(jsonData.artist_website_2);

            $('#relation_user_genres').val(jsonData.relation_user_genres);

            // set all the genres that are associated in the relationship
            var gstr = jsonData.relation_genres;
            if (gstr && gstr != null) {
              gstr = gstr.split(",");
            } else {
              gstr = [];
            }
            var genreList = document.getElementById('relation_genre');
            genreListLength = genreList.options.length;
            for (var i = 0; i < genreListLength; i++) {
              genreListOption = genreList.options[i];
              genreListValue = genreList.options[i].value;
              genreListOption.selected = gstr.includes(genreListValue);

            }

            $('.multi-select-dd').fSelect('reload');
          }
        });

        // set the relation type checkboxes
        $('#studied').prop('checked', false);
        $('#danced').prop('checked', false);
        $('#collaborated').prop('checked', false);
        $('#influenced').prop('checked', false);
        for (var i = 0; i < relation_array.length; i++) {
          if (relation_array[i] == 'Studied Under') {
            $('#studied').prop('checked', true);
          } else if (relation_array[i] == 'Danced in the Work of') {
            $('#danced').prop('checked', true);
          } else if (relation_array[i] == 'Collaborated With') {
            $('#collaborated').prop('checked', true);
          } else if (relation_array[i] == 'Influenced By') {
            $('#influenced').prop('checked', true);
          }
        }

      });
    });

    // if a user clicks "accept terms" in the T&C popup, close the
    // popup and check the checkbox
    function acceptTerms() {
      $("#dialog-1").dialog("close");
      document.getElementById("terms").checked = true;
    };

    // display the terms and conditions popup
    function readTermsConditions() {
      $("#dialog-1").dialog({
        width: 600
      });
      $("#dialog-1").dialog("open");
      console.log("ok")
    }

    // display the terms and conditions popup
    function readDefinitions() {
      // @media
      let media = window.matchMedia("(max-width: 1000px)");
      if (media.matches) {
        $('#dialog_2_box_data').html('\
        <div style="font-weight: normal;">There are four types of lineal lines or Relationships:</div>\
        <details>\
          <summary> > DANCED IN THE WORK OF</summary>\
          <div style="font-weight: normal;"><small>Choreographers whose work you have danced in.</small></div>\
        </details>\
        <details>\
          <summary> > STUDIED UNDER</summary>\
          <div style="font-weight: normal;"><small>Teachers under whom you have studied.</small></div>\
        </details>\
        <details>\
          <summary> > COLLABORATED WITH</summary>\
          <div style="font-weight: normal;"><small>Artists with whom you have collaborated.</small></div>\
        </details>\
        <details>\
          <summary> > INFLUENCED BY</summary>\
          <div style="font-weight: normal;"><small>People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors,\
                      theorists, etc. You do not need to have a relationship with this person in order to list them as having an impact on your work.</small></div>\
        </details>\
        ');
        $("#dialog-2").dialog({
          width: 300
        });
      } else {
        $("#dialog-2").dialog({
          width: 800
        });
      }
      $("#dialog-2").dialog("open");
      console.log("ok");
      console.log("Here to Test Pop-UP - OPEN!");
    }

    function closeDefinitions() {
      $("#dialog-2").dialog("close");
      console.log("Here to Test Pop-UP - CLOSE!");
    };

    function readDefinitions_1() {
      // @media
      let media = window.matchMedia("(max-width: 1000px)");
      if (media.matches) {
        $("#dialog_3_box_data").html('\
        <p style="font-weight: normal"><strong>DANCED IN THE WORK OF </strong> - <small>Choreographers whose work you have danced in.</small></p>\
        ');
        $("#dialog-3").dialog({
          width: 300
        });
      } else {
        $("#dialog-3").dialog({
          width: 800
        });
      }
      $("#dialog-3").dialog("open");
      console.log("ok");
      console.log("Here to Test Pop-UP - OPEN!");
    }

    function closeDefinitions_1() {
      $("#dialog-3").dialog("close");
      console.log("Here to Test Pop-UP - CLOSE!");
    };

    function readDefinitions_2() {
      // @media
      let media = window.matchMedia("(max-width: 1000px)");
      if (media.matches) {
        $('#dialog_4_box_data').html('\
        <p style="font-weight: normal"><strong>STUDIED UNDER</strong> - <small>Teachers under whom you have studied</small></p>\
        ');
        $("#dialog-4").dialog({
          width: 300
        });
      } else {
        $("#dialog-4").dialog({
          width: 800
        });
      }
      $("#dialog-4").dialog("open");
      console.log("ok");
      console.log("Here to Test Pop-UP - OPEN!");
    }

    function closeDefinitions_2() {
      $("#dialog-4").dialog("close");
      console.log("Here to Test Pop-UP - CLOSE!");
    };

    function readDefinitions_3() {
      // @media
      let media = window.matchMedia("(max-width: 1000px)");
      if (media.matches) {
        $("#dialog_5_box_data").html('\
        <p style="font-weight: normal"><strong>COLLABORATED WITH </strong> - <small>Artists with whom you have collaborated.</small></p>\
        ');
        $("#dialog-5").dialog({
          width: 300
        });
      } else {
        $("#dialog-5").dialog({
          width: 800
        });
      }
      $("#dialog-5").dialog("open");
      console.log("ok");
      console.log("Here to Test Pop-UP - OPEN!");
    }

    function closeDefinitions_3() {
      $("#dialog-5").dialog("close");
      console.log("Here to Test Pop-UP - CLOSE!");
    };

    function readDefinitions_4() {
      // @media
      let media = window.matchMedia("(max-width: 1000px)");
      if (media.matches) {
        $('#dialog_6_box_data').html('\
        <p style="font-weight: normal"><strong>INFLUENCED BY</strong> - <small>People with whom you have NOT studied, danced or collaborated, but who have significantly influenced your work such as other artists, authors, theorists, etc. You do not need to have a relationship with this person in order to list them as having an impact on your work.</small></p>\
        ');
        $("#dialog-6").dialog({
          width: 300
        });
      } else {
        $("#dialog-6").dialog({
          width: 800
        });
      }
      $("#dialog-6").dialog("open");
      console.log("ok");
      console.log("Here to Test Pop-UP - OPEN!");
    }

    function closeDefinitions_4() {
      $("#dialog-6").dialog("close");
      console.log("Here to Test Pop-UP - CLOSE!");
    };

    // ok, I didn't come up with this regex, I blindly copied it from the internet.  Shame on me.  Seems to work, though.
    function validateEmail(email) {
      var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }

    // this is called when we click the "add new artist and continue" button.  it is only ever called with a route from one place, so the
    // argument seems sort of worthless here.  The route is "none" if you pick there were no matches from the popup that says that There
    // are similar artists in the database.
    function addingArtist(route = "") {

      var fname = document.getElementById('lineal_first_name').value;
      var lname = document.getElementById('lineal_last_name').value;
      var email = document.getElementById('email_display').value;

      // check email
      if (email !== "") {
        document.getElementById('lineal_email_address').value = email;
        if (validateEmail(email) == false) {
          alert("The email address you entered is not valid.");
          return;
        }
      }

      // Check last name
      if (lname == "") {
        alert("Please enter LAST Name for lineal artist");
        event.preventDefault();
        return;
      }

      // Check first name
      if (fname == "") {
        alert("Please enter First Name for lineal artist");
        return;
      }

      // Check studied and collaborated
      if (document.getElementById('studied').checked == false && document.getElementById('danced').checked == false && document.getElementById('collaborated').checked == false && document.getElementById('influenced').checked == false) {
        alert("Please select type of relationship");
        event.preventDefault();
        return;
      }

      // Get data for Genre and Danced in the Work of
      var lineal_genres = document.getElementById("relation_genre");
      var works = document.getElementById("danced_titles").value;

      var glength = lineal_genres.options.length;
      var glist = "";
      for (var i = 0; i < glength; i++) {
        if (lineal_genres.options[i].selected) {
          glist = glist + ',' + lineal_genres.options[i].value;
        }
      }
      glist = glist.substr(0, glist.length);
      console.log("yo");
      console.log(glist);


      var other_genres = "";
      if (document.getElementById("relation_user_genres")) {
        other_genres = document.getElementById("relation_user_genres").value;
      }

      console.log("hi");
      console.log(other_genres);

      // Checkboxes data
      var selected_checkboxes = new Array();
      var unselected_checkboxes = new Array();

      if ($('#studied').is(':checked')) {
        selected_checkboxes.push($('#studied').val());
      } else {
        unselected_checkboxes.push('Studied Under');
      }

      if ($('#danced').is(':checked')) {
        selected_checkboxes.push($('#danced').val());
        $("#danced_options").fadeOut('slow');
      } else {
        unselected_checkboxes.push('Danced in the Work of');
      }

      if ($('#collaborated').is(':checked')) {
        selected_checkboxes.push($('#collaborated').val());
      } else {
        unselected_checkboxes.push('Collaborated With');
      }

      if ($('#influenced').is(':checked')) {
        selected_checkboxes.push($('#influenced').val());
      } else {
        unselected_checkboxes.push('Influenced By');
      }


      var payloadForAristForm = {};
      payloadForAristForm.action = "getProfiles";
      payloadForAristForm.artistfirstname = fname;
      payloadForAristForm.artistlastname = lname;
      payloadForAristForm.artistemailaddress = document.getElementById('lineal_email_address').value;
      payloadForAristForm.profilename = document.getElementById('lineal_email_address').value;
      payloadForAristForm.is_user_artist = "other";
      payloadForAristForm.artistwebsite = document.getElementById('lineal_website').value;
      payloadForAristForm.newgenre = glist;
      payloadForAristForm.p_artist_fname = '<?php echo $artist_fname; ?>';
      payloadForAristForm.p_artist_lname = '<?php echo $artist_lname; ?>';
      payloadForAristForm.p_artist_email = '<?php echo $artist_email; ?>';
      payloadForAristForm.route = route;

      // if we clicked on the "edit" button, pull the relevant ID from the form
      if (isArtistEntryFormPopulated) {
        payloadForAristForm.artistprofileid = artistIdInForm;
      }

      $.ajax({
        url: "artistrelationcontroller.php",
        type: 'POST',
        data: JSON.stringify(payloadForAristForm),
        success: function(response) {
          console.log(response);

          //similar profiles found
          var code = '';
          if (response.hasOwnProperty("similar_profiles")) {
            resp = response.similar_profiles;
            loadModal(resp);

          } else {
            var pid1 = response.parent_artist.artist_profile_id;
            var fname1 = response.parent_artist.artist_first_name;
            var lname1 = response.parent_artist.artist_last_name;
            var fullname1 = fname1.concat('-', lname1);
            var email1 = response.parent_artist.artist_email_address;
            var pid2 = response.child_artist.artist_profile_id;
            var fname2 = response.child_artist.artist_first_name;
            var lname2 = response.child_artist.artist_last_name;
            var fullname2 = fname2.concat('-', lname2);
            var email2 = response.child_artist.artist_email_address;
            var website2 = document.getElementById('lineal_website').value;

            // Delete unselected checkbox relations if any
            for (var i = 0; i < unselected_checkboxes.length; i++) {
              $.ajax({
                type: "POST",
                url: 'artistrelationcontroller.php',
                data: JSON.stringify({
                  "action": "deleteArtistRelationWithOtherIdentifiers",
                  "artist_profile_id_1": pid1,
                  "artist_profile_id_2": pid2,
                  "artistrelation": unselected_checkboxes[i]
                }),
                success: function(response) {}
              });
            }

            // add reations in artist_relation
            var loopLength = selected_checkboxes.length;
            for (var i = 0; i < selected_checkboxes.length; i++) {
              artistRelationPayload = {};
              artistRelationPayload.action = "addOrEditArtistRelationWithOtherFields";
              artistRelationPayload.artist_profile_id_1 = pid1;
              artistRelationPayload.artist_profile_id_2 = pid2;
              artistRelationPayload.artistname1 = fullname1;
              artistRelationPayload.artistemailId1 = email1;
              artistRelationPayload.artistname2 = fullname2;
              artistRelationPayload.artistemailId2 = email2;
              artistRelationPayload.artistwebsite2 = website2;
              artistRelationPayload.artistrelation = selected_checkboxes[i];
              artistRelationPayload.relation_genres = glist;
              artistRelationPayload.relation_user_genres = other_genres;

              if (selected_checkboxes[i] == 'Danced in the Work of') {
                console.log(works);
                artistRelationPayload.works = works;
              }

              $.ajax({
                type: "POST",
                url: 'artistrelationcontroller.php',
                data: JSON.stringify(artistRelationPayload),
                success: function(response) {
                  $('#display_relations').DataTable().ajax.reload();
                  isArtistEntryFormPopulated = false;
                  artistIdInForm = null;
                  $('.multi-select-dd').fSelect('reload');
                  $('#add_user_profile_form')[0].reset();
                  $("#lineal_last_name").val('');
                  $("#lineal_first_name").val('');
                  hideFirstName();
                },
                error: function(response) {
                  console.log(response);
                }
              });
            }
          }
        },
        error: function(response) {
                  console.log(response);
                }
      });

      $("#relation_genre option:selected").removeAttr("selected");
    }

    // this is the action taken if you click the "no artists match" button in the
    // artist matching page - Popup
    $(document).on('click', '.noneofthem', function() {
      $("#modal").css("display", "none");
      $("#modal-overlay").css("display", "none");
      addingArtist("none");
    });
  </script>


<script>
  $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script>


<script>
  function readGenreNote() {
        let media = window.matchMedia("(max-width: 1000px)");
        if (media.matches) {
            $("#dialog-gen").dialog({
                width: 300
            });
        } else {
            $("#dialog-gen").dialog({
                width: 800
            });
        }
        $("#dialog-gen").dialog("open");
    }

  function closeGenreNote() {
        $("#dialog-gen").dialog("close");
        console.log("Here to Test Pop-UP - CLOSE!");
    };
</script>


</body>

</html>