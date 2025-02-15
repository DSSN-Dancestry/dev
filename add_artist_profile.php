<?php

// this page is used to create a new profile for the first time. It could be
// the logged in user's profile, or one that they are entering on behalf
// of another artist.  It can also be routed from the edit profile function,
// if this was the last page that was updated when a user was creating/editing
// the profile, by clicking on the "Add Artist Profile" stage in the timeline

include 'util.php';
my_session_start();

require 'connect.php';

$conn = getDbConnection();
$query = "SELECT * FROM admin_features;";
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$admin_result = $statement->fetchAll();
$admin_result = json_encode($admin_result);



// set the stage in the timeline, to highlight the right section
$_SESSION["timeline_stage"] = "profile";


// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
    header('Location: login.php');
    exit;
}

// this variable tells us if it is a new profile record, or if we are viewing/editing one
$prepopulated = "false";

// if we are viewing or editing, as opposed to creating a new profile, load the first/last/email from the session
if (isset($_SESSION['timeline_flow']) &&  ($_SESSION['timeline_flow'] == "edit" || $_SESSION['timeline_flow'] == "view")) {
    $prepopulated = "true";
    $user_email_address = $_SESSION["user_email_address"];
    $user_lastname = $_SESSION["user_lastname"];
    $user_firstname = $_SESSION["user_firstname"];
} else {
    $user_email_address = '';
    $user_lastname = '';
    $user_firstname = '';
}

// if we are in view mode, set a flag; this will be used to disable all inputs once the
// page is rendered
if (isset($_SESSION['timeline_flow']) &&  $_SESSION['timeline_flow'] == "view") {
    echo "<script>var disabled_input=true;</script>";
} else {
    echo "<script>var disabled_input=false;</script>";
}

// WHY?
if (isset($_SESSION["contribution_type"])) {
    $contribution_form_type = $_SESSION["contribution_type"];
    if (isset($_SESSION["artist_first_name"])) {
        $artist_fname = $_SESSION["artist_first_name"];
    } else {
        $artist_fname = "";
    }

    if (isset($_SESSION["artist_last_name"])) {
        $artist_lname = $_SESSION["artist_last_name"];
    } else {
        $artist_lname = "";
    }

    if (isset($_SESSION["artist_email_address"])) {
        $_SESSION['initial_eaddress'] = $_SESSION["artist_email_address"];
        if (strpos($_SESSION["artist_email_address"], 'dummyemail@') === false) {
            $artist_eaddress = $_SESSION["artist_email_address"];
        } else {
            $artist_eaddress = "";
        }
    } else {
        $artist_eaddress = "";
        $str = rand();
        $_SESSION['initial_eaddress'] = 'dummyemail@' . md5($str) . sha1($str);
    }
}

if ($_SESSION['timeline_flow'] == 'artist_add') {
    $_SESSION['status'] = '0';
    $_SESSION['artist_reference'] = '';
    $_SESSION['reference_details'] = '';
}
?>

<?php include 'menu.php'; ?>
<html>

<head>
    <script>
        var admin_result = <?php echo $admin_result ?>;
    </script>
    <style type='text/css'>
        .disabledbutton {
            pointer-events: none;
        }

        .mrl27p {
            margin-left: 27px;
        }

        #newArtistGenreDiv,
        .newGenreDiv_as_id {
            padding: 0px;
            width: 100%;
            max-width: 575;
        }

        .additional_genress_as_id {
            padding: 0px;
            width: 100%;
            /* max-width: 575; */
        }

        #home,
        #next,
        #next1 {
            margin: 0px;
        }

        @media only screen and (max-width: 1000px) {
            #prev_save_row {
                display: flex;
                padding-bottom: 10px;
            }

            #home,
            #next,
            #next1 {
                width: 100%;
            }

            #personal_details {
                display: flex;
                flex-direction: column;
            }

            .personal_details_childs {
                width: 100%;
            }
        }
    </style>
    <title>Add Artist Profile | Choreographic Lineage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>


    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="css/fSelect2.css" rel="stylesheet">
    <script src="js/fSelect.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> -->
    <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script> -->
    <!-- <script src="https://getbootstrap.com/docs/3.4/javascript"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.3/bootbox.min.js"></script> -->


    <link href="css/progressbar.css" rel="stylesheet">
    <script src="submit_database_request.js"></script>
</head>

<body>
    <?php include 'user_list.php'; ?>
    <form id="add_user_profile_form" name="add_user_profile_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="addOrEditArtistProfile">
        <input type="hidden" name="artist_profile_id" value="<?php echo $_SESSION["artist_profile_id"] ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"] ?>">
        <input type="hidden" name="is_user_artist" id="is_user_artist" value="<?php echo (($_SESSION['contribution_type'] == "own") ? 'artist' : 'other')  ?>">
        <input type="hidden" name="profile_name" id="profile_name" value="<?php echo $_SESSION["user_email_address"]  ?>" />
        <input type="hidden" name="profile_complete_status" id="profile_complete_status" value="<?php echo $_SESSION['status'] ?>" />
        <input type="hidden" name="status" value="25">
        <input type="hidden" name="user_genres_session" id="user_genres_session" value="<?php
                                                                                        if (isset($_SESSION["user_genres"])) {
                                                                                            echo $_SESSION["user_genres"];
                                                                                        } else {
                                                                                            echo "";
                                                                                        } ?>">
        <input type="hidden" name="session_artist_first_name" id="session_artist_first_name" value="<?php
                                                                                                    if (isset($_SESSION["artist_first_name"])) {
                                                                                                        echo $_SESSION["artist_first_name"];
                                                                                                    } else {
                                                                                                        echo "";
                                                                                                    } ?>">
        <input type="hidden" name="session_artist_last_name" id="session_artist_last_name" value="<?php
                                                                                                    if (isset($_SESSION["artist_last_name"])) {
                                                                                                        echo $_SESSION["artist_last_name"];
                                                                                                    } else {
                                                                                                        echo "";
                                                                                                    } ?>">
        <input type="hidden" name="session_artist_year_of_birth" id="session_artist_year_of_birth" value="<?php
                                                                                                            if (isset($_SESSION["year_of_birth"])) {
                                                                                                                echo $_SESSION["year_of_birth"];
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            } ?>">
        <?php include 'progressbar.php'; ?>
        <?php include 'add_artist_references.php'; ?>

        <div class="row">
            <div style="clear:both; text-align:left; padding-left: 5px;">
                <h2 style="display:inline;">
                    <strong>
                        <?php echo (($_SESSION['contribution_type'] == "own") ? 'MY' : "ARTIST'S")  ?> PROFILE INFORMATION</strong>
                </h2>
                <!-- <div class="add-reference-button" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div> -->
                <!-- <h5  style="display:inline; float: right; color: #006400;"></h5> -->
                <div class="add-reference-button" style="display: none;" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div>
                <h5 style="display:inline; float: right; color: #006400;"></h5>
            </div>
        </div>
        </div>
        <div class="row sectionbox">
            <div class="row ">
                <div class="medium-12 column">
                    <section>
                        <fieldset>
                            <div id="personal_details" class="row">
                                <div class="small-6 column personal_details_childs">
                                    <label for="artist_first_name">
                                        <?php echo (($_SESSION['contribution_type'] == "own") ? 'Your First Name' : 'First Name of Artist') ?> <span style="color:red;font-weight: bold;"> *</span>
                                        <input value="<?php echo (($_SESSION['contribution_type'] == "own") ? $_SESSION['user_firstname'] : $artist_fname) ?>" autocomplete="off" type="text" id="artist_first_name" name="artist_first_name" placeholder="First Name" required>
                                        <div id="firstnamelist" style="background-color:#eee;"></div>
                                    </label>
                                </div>
                                <div class="small-6 column personal_details_childs">
                                    <label for="artist_last_name">
                                        <?php echo (($_SESSION['contribution_type'] == "own") ? 'Your Last Name' : 'Last Name of Artist') ?>
                                        <span style="color:red;font-weight: bold;"> *</span>
                                        <input value="<?php echo (($_SESSION['contribution_type'] == "own") ? $_SESSION['user_lastname'] : $artist_lname) ?>" autocomplete="off" type="text" id="artist_last_name" name="artist_last_name" placeholder="Last Name" required>
                                        <div id="lastnamelist" style="background-color:#eee;"></div>
                                    </label>
                                </div>
                                <div class="small-6 column personal_details_childs" id="yob_div">
                                    <label>
                                        Year of Birth
                                        <span id="dob_star" style="color:red;font-weight: bold;line-height: unset;"> *</span>
                                    </label>
                                    <input type="number" value="<?php echo isset($_SESSION['year_of_birth']) ? $_SESSION['year_of_birth'] : '' ?>" class="span2" id="year_of_birth" name="year_of_birth" placeholder="Birth Year" onblur="datevalidation()">
                                    <!-- <div class="row date_section">
                                        <div class="column medium-6">
                                            <fieldset>
                                                <legend>
                                                    Date of Birth
                                                    <span style="color:red;font-weight: bold;"> *</span>
                                                </legend>
                                                <input type="date" value="<?php echo isset($_SESSION['year_of_birth']) ? $_SESSION['year_of_birth'] : '' ?>" class="span2" id="year_of_birth" name="year_of_birth" placeholder="yyyy-mm-dd" onblur="datevalidation()">
                                            </fieldset>
                                        </div> -->
                                    
                                    <!-- </div> -->
                                </div>
                                <div id="email_address_div" class="small-6 column personal_details_childs">
                                    <label for="artist_email_address">
                                        <?php echo (($_SESSION['contribution_type'] == "own") ? 'Your Email Address' : 'Email Address of Artist') ?>
                                    </label>
                                    <input value="<?php echo (($_SESSION['contribution_type'] == "own") ? $_SESSION['user_email_address'] : $artist_eaddress) ?>" autocomplete="off" type="email" id="artist_email_address" name="artist_email_address" placeholder="Email Address">
                                    <div id="duplication_check" style="color:red"></div>
                                </div>
                                
                            </div>
                        </fieldset>
                    </section>
                </div>
            </div>

            <div class="row">
                <fieldset>
                    <div class="small-3 column">
                        <legend>
                            <div style="color:red;" id="first_name_message"></div>
                        </legend>
                    </div>
                    <div class="small-9 column">
                        <legend>
                            <div style="color:red;" id="last_name_message"></div>
                        </legend>
                    </div>
                </fieldset>
            </div>
            <div id="other_artist_section">
                <div class="row">
                    <fieldset class="large-12 columns">
                    
                        <legend>
                            Is the Artist living or deceased?
                        </legend>
                        <input type="radio" name="artist_status" value="living" id="artist_living" onclick="artistStatusSelection();" checked <?php
                                                                                                                                                if (isset($_SESSION["artist_status"])) {
                                                                                                                                                    echo (($_SESSION["artist_status"] == 'living') ? 'checked' : '');
                                                                                                                                                }
                                                                                                                                                ?> />
                        <label for="artist_living">Living</label>
                        <input type="radio" name="artist_status" value="deceased" id="artist_deceased" onclick="artistStatusSelection();" <?php
                                                                                                                                            if (isset($_SESSION["artist_status"])) {
                                                                                                                                                echo (($_SESSION["artist_status"] == 'deceased') ? 'checked' : '');
                                                                                                                                            }
                                                                                                                                            ?> />
                        <label for="artist_deceased">Deceased</label>
                    </fieldset>
                    <div class="small-6 column" id="date_of_death_div" style="display:block; float: left">
                        <fieldset>
                            <legend>
                                Date of Death
                                <span style="color:red;font-weight: bold;"> *</span>
                            </legend>
                            <input type="date" value="<?php echo isset($_SESSION['date_of_death']) ? $_SESSION['date_of_death'] : '' ?>" class="span2" id="date_of_death" name="date_of_death" placeholder="yyyy-mm-dd" onblur="deathvalidation()">
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="row">
                <fieldset class="column medium-6">
                    <legend>
                        <div style="color:red;" id="date_message"></div>
                    </legend>
                </fieldset>
                <fieldset class="column medium-12">
                    <legend>
                        <div style="color:red;" id="dd_message"></div>
                        <div id="dd_instruction" style="font-size:13px;"><em>If exact date is unknown, please enter January 1st and the year</em></div>
                    </legend>
                </fieldset>
            </div>
        </div>
        <div id="artist_type_div" class="row sectionbox">
            <div class="row">
                <section>
                    <fieldset class="column medium-9">
                        <legend>
                            <strong>Type of Artist</strong>
                            <small>(check all that apply)</small>
                        </legend>
                        <div id="newArtistGenreDiv" class="medium-3 column" style="max-width: 575px;">
                            <label for="ArtistType">
                                <select id="artistGenreList" name='artist_genre[]' placeholder='Select Artist Type' placeclass="multi-select-dd" multiple="multiple">
                                    <!--<option value="" disabled selceted>Select types</option>-->
                                    <!-- The above may need changes to the javascript -->
                                    <?php
                                    // create the select list --> MAKE THIS DATABASE DRIVEN
                                    $artistGenres = ['Actor', 'Choreographer', 'Composer', 'Costume_Designer', 'Dancer', 'Filmmaker', 'Lighting_Designer', 'Musician', 'Poet', 'Visual_Artist', 'Scenic_Designer', 'Other'];
                                    foreach ($artistGenres as $artistGenre) {
                                        echo ("<option value=\"" . $artistGenre . "\">" . $artistGenre . "</option>");
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <!-- <br> -->
                        <div class="medium-12 column">
                            <label for="Other_Artist_Text_Input" id="Other_Artist_Text" style="display:none; margin-left: -15px;">Please separate multiple entries by comma:
                                <input style="max-width: 575px;" autocomplete="off" type="text" id="Other_Artist_Text_Input" name="other_artist_text_input" value="<?php echo isset($_SESSION['other_artist_text_input']) ? $_SESSION['other_artist_text_input'] : ''
                                                                                                                                            ?>" />
                            </label>
                        </div>
                    </fieldset>
                </section>
            </div>
        </div>
        <div class="row sectionbox" id="genres_div">
            <div class="row">
                <section>
                    <fieldset class="column medium-9">
                        <legend>
                            <strong>Dance Genres</strong>
                            <small>(check all that apply)</small>
                        </legend>
                        <div id="newGenreDiv" class="small-3 column newGenreDiv_as_id">
                            <label for="Genre">
                                <select id="genreList" name='genre[]' placeholder='Select Genres' class="multi-select-dd" multiple="multiple">
                                </select>
                            </label>

                        </div>
                        <div id="newGenreDiv" class="small-12 column additional_genress_as_id">
                            <label for="Genre">Additional Genres&nbsp; &nbsp;<img src="img/help.png" class="h13p w13p cursorp" onclick="readGenreNote()"></img>
                            </label>
                            <input id="userGenreList" style="max-width: 575px;" name='user_genres' type="text" value="<?php if (isset($_SESSION["user_genres"])) {
                                                                                                                            echo trim($_SESSION["user_genres"]);
                                                                                                                        } ?>" placeholder="Genre1, Genre2">
                            </label>
                        </div>
                    </fieldset>
                </section>
            </div>
        </div>

        <div id="extraControls" style="display: none;">
            <div id="dialog-4" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Genre Note">
                <div class="row">
                    <p style="font-weight: normal">If you are unable to find the genre you want in the dropdown list, you can add additional genres here.</p>
                </div>
                <button class="primary button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeGenreNote();">
                    <span>OK</span>
                </button>
            </div>
        </div>

        <div id="extraControls" style="display: none;">
            <div id="dialog-5" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9">
                <div class="row">
                    <p style="font-weight: normal">As you move through the lineage form, you can note the sources you used to complete this artist’s lineage by clicking on the references icon <img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"> at the top right of each page.</p>
                </div>
                <button class="primary button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeRefNote();">
                    <span>OK</span>
                </button>
            </div>
        </div>

        <div class="row">
            <div id="prev_save_row">
                <?php if (isset($_SESSION['artist_relation_add'])) : ?>
                    <div class="prev_button large-4 small-8 column">
                        <button class="primary button" id="previous" type="button" onclick="validate(window.open.bind(null,'profiles.php','_self'));">
                            <span>Previous</span>
                        </button>
                    </div>
                <?php else : ?>
                    <div class="prev_button large-4 small-8 column">
                        <?php if (isset($_SESSION['contribution_type']) && $_SESSION['contribution_type'] == 'own') : ?>
                            <button class="primary button" type="button" name="home" id="home" onclick="window.open('profiles.php','_self')">
                                <span>&lt; Profile</span>
                            </button>
                        <?php else : ?>
                            <button class="primary button" type="button" name="home" id="home" onclick="window.open('other_artist_profiles.php','_self')">
                                <span>&lt; Profile</span>
                            </button>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>
                <div class="save_and_next large-3 small-8 column">
                    <!--<button class="primary button" id="next1" type="button" onclick="window.open('profiles.php','_self');"> -->
                    <button class="primary button" id="next" type="button" name="next" onclick="validate(window.open.bind(null,'add_artist_personal_information.php','_self'));">
                        <span><?php echo (($_SESSION['timeline_flow'] == "view") ? "" : "Save & ") ?>Next &gt;</span>
                    </button>
                </div>
            </div>
            <div class="save_and_continue large-5 small-12 column">
                <button class="secondary button" id="next1" name="later" type="button" onclick="validate(window.open.bind(null, 'profiles.php','_self'));">
                    <span>Save & Continue Later</span>
                </button>
            </div>
        </div>
    </form>
</body>
<?php
include 'footer.php';
?>

</html>



<script type="text/javascript">
    // used for profile popup
    var source_page = 'add_user_profile';
    var add_profile_callback = '';

    function focusDiv(div_name) {
        $('html, body').animate({
            'scrollTop': $("#" + div_name).position()
        });
    }

    function validate(callback) {
        add_profile_callback = callback;
        // console.log(callback)
        let valid = datevalidation();

        if ($('input[name="artist_status"]:checked').val() == "deceased") {
            valid = deathvalidation();
        }


        var text = document.getElementById("duplication_check").innerHTML;
        if (text.trim() === ("!! User already exists. Please change artist name").trim()) {
            alert("Cannot submit form with duplicate artist name");
            valid = false;
        }

        // check first name
        if ($("#artist_first_name").val().trim() == "") {
            document.getElementById('first_name_message').style.display = "block";
            document.getElementById('first_name_message').innerHTML = "First Name is a required field.";
            valid = false;
            focusDiv('first_name_message');
        } else {
            document.getElementById('first_name_message').style.display = "none";
        }
        // check last name
        if ($("#artist_last_name").val().trim() == "") {
            document.getElementById('last_name_message').style.display = "block";
            document.getElementById('last_name_message').innerHTML = "Last Name is a required field.";
            valid = false;
            focusDiv('last_name_message');
        } else {
            document.getElementById('last_name_message').style.display = "none";
        }

        if (valid) {
            if ($("#profile_complete_status").val() == '0') {
                checkMultipleUsers(add_profile_callback);
            } else {
                submitJson('#add_user_profile_form', 'artistcontroller.php', null, add_profile_callback);

                $("#userGenreList").val($("#userGenreList").val().trim());
                if ($("#userGenreList").val() != "" && $("#userGenreList").val() != $("#user_genres_session").val()) {
                    var json_data_additional_genre_mail = {};
                    json_data_additional_genre_mail.action = "sendEmail";
                    json_data_additional_genre_mail.first_name = $("#artist_first_name").val();
                    json_data_additional_genre_mail.last_name = $("#artist_last_name").val();
                    json_data_additional_genre_mail.user_email_address = $("#artist_email_address").val();
                    json_data_additional_genre_mail.user_genres = $("#userGenreList").val();
                    $.ajax({
                        url: "additional_genre_mail.php",
                        type: 'POST',
                        dataType: "json",
                        contentType: "application/json; charset=UTF-8",
                        data: JSON.stringify(json_data_additional_genre_mail),
                        success: function(response) {
                            console.log(response);
                        }
                    });
                }
            }
        }
    } // end function validate

    $(document).on('click', '.add_profile_yes', function() {
        submitJson('#add_user_profile_form', 'artistcontroller.php', null, add_profile_callback);
    });
    $(document).on('click', '.add_profile_no', function() {
        window.open('profiles.php', '_self');
    });

    function checkMultipleUsers(add_profile_callback) {
        var json_data_add_artist_profile_form = {};
        json_data_add_artist_profile_form.action = "getProfiles";
        json_data_add_artist_profile_form.artistfirstname = $("#artist_first_name").val();
        json_data_add_artist_profile_form.artistlastname = $("#artist_last_name").val();
        json_data_add_artist_profile_form.artistemailaddress = $("#artist_email_address").val();
        json_data_add_artist_profile_form.route = '';
        json_data_add_artist_profile_form.p_artist_fname = '<?php echo $user_firstname; ?>';
        json_data_add_artist_profile_form.p_artist_lname = '<?php echo $user_lastname; ?>';
        json_data_add_artist_profile_form.p_artist_email = '<?php echo $user_email_address; ?>';

        // if we clicked on the "edit" button, pull the relevant ID from the form
        if ($("#artist_profile_id").val() != "") {
            json_data_add_artist_profile_form.artistprofileid = $("#artist_profile_id").val();
        }

        $.ajax({
            url: "artistrelationcontroller.php",
            type: 'POST',
            data: JSON.stringify(json_data_add_artist_profile_form),
            success: function(response) {
                console.log(response);
                var resp = '';
                if (response.hasOwnProperty("similar_profiles")) {
                    resp = response.similar_profiles;
                    if (resp[0]['artist_first_name'] == $("#session_artist_first_name").val()) {
                        if (resp[0]['artist_last_name'] == $("#session_artist_last_name").val()) {
                            resp = '';
                        }
                    }
                }
                // Display user popup
                if (resp != '') {
                    loadModal(resp);
                    console.log("false");
                } else {
                    console.log("true");
                    submitJson('#add_user_profile_form', 'artistcontroller.php', null, add_profile_callback);
                }
            }
        });
    }


    $(function() {
        $('#artistGenreList').change(function(e) {
            var selected = $(e.target).val();
            console.log(selected);
            if(jQuery.inArray("Other", selected) !== -1){
                $("#Other_Artist_Text").show();
            }
            else{
                $("#Other_Artist_Text").hide();
            }
        }); 
    });

    var selectedGenre = '<?php echo isset($_SESSION["artist_genre"]) ? $_SESSION["artist_genre"] : ""; ?>';
    if (selectedGenre.includes("Other")) {
        $("#Other_Artist_Text").show();
    } else {
        $("#Other_Artist_Text").hide();
    }

    function artistStatusSelection() {
        console.log("Setting living status");
        if ($('input[name="artist_status"]:checked').val() == "living") {
            console.log("alive");
            $("#date_of_death").val("");
            $("#date_of_death_div").hide();
            $("#dd_instruction").hide();
            $("#date_of_death").prop("required", false);
        } else if ($('input[name="artist_status"]:checked').val() == "deceased") {
            console.log("dead");
            $("#date_of_death_div").show();
            $("#dd_instruction").show();
            $("#date_of_death").prop("required", true);
        }
    }


    // call this function when the document is finished loading to set
    // current values
    $(document).ready(function() {
        if ($("#is_user_artist").val() == 'other') {
            $(".add-reference-button").show();
            console.log("here");
            if ($("#profile_complete_status").val() == '0') {
                showRefNote();
            }
            
        } else {
            $(".add-reference-button").hide();
        }

        // $('.multi-select-dd').fSelect();
        var currentFlow = '<?php echo $_SESSION["timeline_flow"]; ?>';

        // load the current genre list from the API
        loadGenres();

        // if this profile is one we're contributing on behalf of
        // another artist, show the relevant sections, otherwise
        // hide them.
        if ("<?php echo $contribution_form_type ?>" == "other") {
            $("#other_artist_section").show();
            $(".other_artist").show();
            $("#year_of_birth").prop("required", true);

        } else {
            $("#year_of_birth").prop("required", true);
            $("#other_artist_section").hide();
            $("#date_of_death_div").hide();
        }


        artistStatusSelection();
        // artistTypeSelection();


        // if they click the "Other" box for artist type, display
        // an additional text box for them to enter an additional type
        // $("#Other_Artist_Type").click(artistTypeSelection);

        // if we are in "view mode", disable all the input fields
        if (disabled_input) {
            $('input').attr('disabled', 'true')
        }
    });

    function datevalidation() {
        for (var i = 0; i < admin_result.length; i++) {
            if (admin_result[i]["feature_id"] == 1) {
                if (admin_result[i]["feature_enabled"] != 0) {
                    // console.log(admin_result[i]["feature_enabled"]);
                    var birthdate = document.getElementById('year_of_birth');
                    var date = new Date();
                    birth = new Date(birthdate.value);
                    console.log("DOB IS :" + birthdate.value + ":");

                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth() + 1;
                    var yyyy = today.getFullYear();
                    if (dd < 10) {
                        dd = '0' + dd;
                    }
                    if (mm < 10) {
                        mm = '0' + mm;
                    }
                    var today = yyyy + '-' + mm + '-' + dd;

                    if (!birthdate.value || birthdate.value == "" || birthdate.value == null) {
                        document.getElementById('date_message').style.display = "block";
                        document.getElementById("date_message").innerHTML = "Year of Birth is a required field.";
                        // focusDiv('date_message');
                        return false;
                    } else if (date < birth) {
                        document.getElementById('date_message').style.display = "block";
                        document.getElementById("date_message").innerHTML = "Year of Birth cannot be in future!";
                        // focusDiv('date_message');
                        return false;
                    } else if (today === birthdate.value) {
                        document.getElementById('date_message').style.display = "block";
                        document.getElementById("date_message").innerHTML = "Year of Birth cannot be today!";
                        // focusDiv('date_message');
                        return false;
                    } else {
                        document.getElementById('date_message').style.display = "none";
                        return true;
                    }
                } else {
                    $("#dob_star").hide();
                    return true;
                }
            }
        };
    }

    function deathvalidation() {
        var birth = document.getElementById("year_of_birth");
        var death = document.getElementById("date_of_death");
        var bd = new Date(birth.value);
        var dd = new Date(death.value);

        console.log("Date of Death is " + death.value);

        if (!death.value || death.value == "" || death.value == null) {
            document.getElementById('dd_message').style.display = "block";
            document.getElementById('dd_message').innerHTML = "Date of Death is a required field.";
            return false;
        } else if (bd > dd) {
            document.getElementById('dd_message').style.display = "block";
            document.getElementById('dd_message').innerHTML = "! Invalid Date of Death";
            return false;
        } else {
            document.getElementById('dd_message').style.display = "none";
            return true;
        }
    }



    // add validation to the email address box.  This will check for
    // duplicate entries when you leave the email box
    $("#artist_email_address").blur(function() {
        var emailaddr = $(this).val();
        if (emailaddr && emailaddr != '<?php echo isset($_SESSION["artist_email_address"]) ? $_SESSION["artist_email_address"] : 'blank'; ?>') {
            $.ajax({
                url: "duplication_check.php",
                method: "POST",
                data: {
                    email: emailaddr
                },
                success: function(response) {
                    $('#duplication_check').html(response);
                }
            });
        } else {
            $('#duplication_check').html("");
        }
    });

    function setGenres() {
        let currentFlow = '<?php echo $_SESSION["timeline_flow"]; ?>';
        let newOption = '<?php echo isset($_SESSION["genre"]) ? $_SESSION["genre"] : ""; ?>';
        newOption = newOption.split(",");

        // console.log("newOption is " + newOption);
        let genreList = document.getElementById('genreList');
        genreListLength = genreList.options.length;


        for (var i = 0; i < genreListLength; i++) {
            genreListOption = genreList.options[i];
            genreListValue = genreList.options[i].value;
            if (newOption.includes(genreListValue)) {
                genreListOption.selected = true;
            } else {
                genreListOption.selected = false;
            }
        }
        // console.log('TEST HERE');
        // Load type of artist from db on document ready
        let artistGenreList = document.getElementById('artistGenreList');
        artistGenreListLength = artistGenreList.options.length;
        let artistGenreListOptions = '<?php echo isset($_SESSION["artist_genre"]) ? $_SESSION["artist_genre"] : ""; ?>';
        artistGenreListOptions = artistGenreListOptions.split(",");
        for (var i = 0; i < artistGenreListLength; i++) {
            artistGenreListOption = artistGenreList.options[i];
            artistGenreListValue = artistGenreList.options[i].value;
            // console.log(artistGenreListValue);
            if (artistGenreListOptions.includes(artistGenreListValue)) {
                // console.log(artistGenreListValue);
                artistGenreListOption.selected = true;
            } else {
                artistGenreListOption.selected = false;
            }
        }

        $('#artistGenreList').fSelect();
        $('#genreList').fSelect();
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
                    let genreList = document.getElementById('genreList');
                    for (let i = 0; i < result['genres'].length; i++) {
                        let genre = result['genres'][i];
                        let opt = document.createElement('option');
                        opt.text = genre.genre_name;
                        opt.value = genre.genre_id;
                        genreList.add(opt);
                    }
                    setGenres();
                },
                error => {
                    alert("error! add_artist_profile");
                }
            );
    }

    function readGenreNote() {
        let media = window.matchMedia("(max-width: 1000px)");
        if (media.matches) {
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

    function closeGenreNote() {
        $("#dialog-4").dialog("close");
        console.log("Here to Test Pop-UP - CLOSE!");
    };

    function showRefNote() {
        let media = window.matchMedia("(max-width: 1000px)");
        if (media.matches) {
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

    function closeRefNote() {
        $("#dialog-5").dialog("close");
        console.log("Here to Test Pop-UP - CLOSE!");
    };
</script>

<script>
    for (var i = 0; i < admin_result.length; i++) {
        if (admin_result[i]["feature_id"] == 1) {
            if (admin_result[i]["feature_enabled"] == 0) {

                $("#dob_star").hide();
                $("#yob_div").hide();
                $("#email_address_div").css("float", "unset");

            }
        }
    };

    for (var i = 0; i < admin_result.length; i++) {
        if (admin_result[i]["feature_id"] == 3) {
            if (admin_result[i]["feature_enabled"] == 0) {

                $("#artist_type_div").hide();

            }
        }
    };

    for (var i = 0; i < admin_result.length; i++) {
        if (admin_result[i]["feature_id"] == 5) {
            if (admin_result[i]["feature_enabled"] == 0) {

                $("#genres_div").hide();

            }
        }
    };
</script>