<?php
include 'util.php';
my_session_start();

// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
    header('Location: login.php');
    exit;
}

include 'menu.php';

require 'connect.php';

$conn = getDbConnection();
$query = "SELECT * FROM admin_features;";
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$admin_result = $statement->fetchAll();
$admin_result = json_encode($admin_result); 

// set the stage in the timeline, to highlight the right section
$_SESSION["timeline_stage"] = "personal";


if (isset($_SESSION['country_residence'])) {
    echo "<script>var country_res='" . $_SESSION['country_residence'] . "';</script>";
}
if (isset($_SESSION['country_birth'])) {
    echo "<script>var country_b='" . $_SESSION['country_birth'] . "';</script>";
}
if (isset($_SESSION['state_residence'])) {
    echo "<script>var state_res='" . $_SESSION['state_residence'] . "';</script>";
}
if (isset($_SESSION['timeline_flow']) &&  $_SESSION['timeline_flow'] == "view") {
    echo "<script>var disabled_input=true;</script>";
} else {
    echo "<script>var disabled_input=false;</script>";
}

?>

<html>

<head>
    <script>
        var admin_result = <?php echo $admin_result ?>;
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
    <link href="css/progressbar.css" rel="stylesheet">
    <script src="submit_database_request.js"></script>
    <title>Add Artist Personal Information | Choreographic Lineage</title>
    <style type="text/css">
        .mrl27p {
            margin-left: 27px;
        }

        #gender-ethnicity-flex {
            display: flex;
            justify-content: space-between;
            width: 48.6%;
        }

        .other_gender_ethnicity {
            padding-left: 15px;
        }

        #genderList,
        #ethnicityList {
            width: 272px;
        }

        #previous {
            margin: 0;
        }

        #next {
            margin: 0;
        }

        #next1 {
            margin: 0;
        }

        #gender_other_text,
        #ethnicity_other_text {
            max-width: 272px;
        }

        .univ_details {
            display: flex;
        }

        .delete_add_edu_row {
            display: flex;
        }

        .add_another_univ_inst {
            flex-grow: 2;
        }

        .delete_edu_button {
            padding-top: 27px;
        }

        #education_entries {
            display: flex;
        }

        @media only screen and (max-width: 1000px) {

            .add_another_univ_inst {
                text-align: right;
            }

            .univ_details {
                flex-direction: column;
            }

            #gender_other_text,
            #ethnicity_other_text {
                max-width: 1000px;
            }

            #gender-ethnicity-flex {
                flex-direction: column;
                width: 100%;
            }

            #genderList,
            #ethnicityList {
                width: 100%;
            }

            .other_gender_ethnicity {
                padding-left: 11.3px;
                width: 96.7%;
            }

            #prev_save {
                display: flex;
                padding-bottom: 10px;
            }

            #previous,
            #next,
            #next1 {
                width: 100%;
            }

            #prev_save {
                display: flex;
                width: 100%;
                justify-content: space-between;
            }

            #education_entries {
                flex-direction: column;
            }

            .delete_edu_button {
                padding-top: 0;
            }
        }
    </style>
</head>

<body>

    <form id="add_artist_personal_id" name="add_artist_personal_id" enctype="multipart/form-data" action="add_artist_biography.php" method="post">
        <!-- Getting gender info-->
        <?php include 'progressbar.php'; ?>
        <?php include 'add_artist_references.php'; ?>
        <input type="hidden" name="action" value="addOrEditArtistProfile">
        <input type="hidden" name="artist_profile_id" value="<?php echo $_SESSION["artist_profile_id"] ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"] ?>">
        <input type="hidden" name="is_user_artist" id="is_user_artist" value="<?php echo (($_SESSION['contribution_type'] == "own") ? 'artist' : 'other')  ?>">
        <input type="hidden" name="status" value="50">

        <div class="row">
            <div style="clear: both; padding-left: 5px;">
                <h2 style="display:inline;"><strong>PERSONAL INFORMATION</strong></h2>
                <div class="add-reference-button" style="display: none;" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div>
                <h5 style="display:inline; float: right; color: #006400;"></h5>
            </div>
        </div>

        <div class="row" style="padding-left: 5px;">
            <h5><em>Please tell us more about <?php echo (($_SESSION['contribution_type'] == "own") ? 'yourself' : 'this artist') ?>...</em></h5>
        </div>

        <div class="row">
            <fieldset class="large-12 columns">
                <div class="row sectionbox" id="identity_div">
                    <label>
                        <legend><strong>My Identity</strong></legend>
                    </label>
                    <div id="gender-ethnicity-flex">
                        <div id="gender_div">
                            <!-- Getting gender info -->
                            <label>
                                <legend><?php echo ((isset($_SESSION["contribution_type"]) && ($_SESSION["contribution_type"] == "own")) ? "Your" : "Artist") ?> Gender</legend>
                            </label>
                            <select id="genderList" name='gender' class='select'>
                            <option value="none" selected disabled hidden>Choose Gender</option>
                                <?php
                                //Undid some poor soul's hard work to instead create a select menu
                                //lets make this database driven
                                $genders = ['Male/Man:male', 'Female/Woman:female', 'Non-binary/Third Gender:nonbinary', 'Prefer not to disclose:prefer_not_to_disclose', 'Other:other'];
                                foreach ($genders as $gender) {
                                    $genderParts = explode(":", $gender);
                                    echo ("<option ");
                                    if (isset($_SESSION['gender'])) {
                                        echo (($_SESSION["gender"] == $genderParts[1]) ? "selected " : '');
                                    }
                                    echo ("value=\"" . $genderParts[1] . "\" id=\"gender_" . $genderParts[1] . "\">" . $genderParts[0] . "</option>");
                                }
                                ?>
                            </select>
                            <div class="row">
                                <div class="other_gender_ethnicity">
                                    <label for="gender_other_text" id="gender_other_text_label" style="display:none">
                                        Please specify your gender:
                                        <input autocomplete="off" type="text" id="gender_other_text" name="gender_other" value="<?php echo isset($_SESSION['gender_other']) ? $_SESSION['gender_other'] : '' ?>" />
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="ethnicity_div">
                            <!-- Getting Ethnicity info -->
                            <label>
                                <legend><?php echo ((isset($_SESSION["contribution_type"]) && $_SESSION["contribution_type"] == "own") ? "Your" : "Artist") ?> Ethnicity</legend>
                            </label>
                            <select id="ethnicityList" name='ethnicity'>
                            <option value="none" selected disabled hidden>Choose Ethnicity</option>
                                <?php
                                // create the set of radio buttons for ethnicities.  This should eventually be database driven...
                                $ethnicities = ['African American or Black:aab', 'Alaskan National or Indian:ani', 'Asian:asian', 'Caucasian or White:cw','Hawaiian or Pacific Islander:hpi', 'Other:other', 'Prefer not to answer:na'];
                                foreach ($ethnicities as $ethnicity) {
                                    $eParts = explode(":", $ethnicity);
                                    echo ("<option ");
                                    if (isset($_SESSION['ethnicity'])) {
                                        echo (($_SESSION["ethnicity"] == $eParts[0]) ? "selected " : '');
                                    }
                                    echo ("value=\"" . $eParts[0] . "\" id=\"ethnicity_" . $eParts[1] . "\">" . $eParts[0] . "</option>");
                                } ?>
                            </select>
                            <div class="row">
                                <div class="other_gender_ethnicity">
                                    <label for="ethnicity_other_text" id="ethnicity_other_text_label" style="display:none">
                                        Please specify your ethnicity:
                                        <input autocomplete="off" type="text" id="ethnicity_other_text" name="ethnicity_other" value="<?php echo isset($_SESSION['ethnicity_other']) ? $_SESSION['ethnicity_other'] : '' ?>" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="location_div" class="row sectionbox">
                    <?php if ($_SESSION["artist_status"] != 'deceased') {
                    ?>
                        <div>
                            <label>
                                <legend><strong>Location</strong></legend>
                            </label>
                            <div class="row">
                                <!-- Getting Address info -->
                                <div class="medium-3 columns shrink">
                                    <label for="country_birth">
                                        <legend>Country of Birth</legend>
                                        <select id="country_birth" name="country_birth" value="<?php echo isset($_SESSION['country_birth']) ? $_SESSION['country_birth'] : '' ?>">
                                            <?php include 'data/countries.html'; ?>
                                        </select>
                                    </label>
                                </div>

                                <div class="medium-3 columns country_residence">
                                    <label for="country_residence">
                                        <legend>Country of Residence</legend>
                                        <select id=country_residence name="country_residence" value="<?php echo (isset($_SESSION['country_residence']) ? $_SESSION['country_residence'] : '') ?>">
                                            <?php include 'data/countries.html'; ?>
                                        </select>
                                    </label>
                                </div>

                                <div class="medium-3 columns" id="states_US">
                                    <label for="state_residence">
                                        <legend>State of Residence</legend>
                                        <select id="state_residence" name="state_residence" value="<?php echo (isset($_SESSION['state_residence']) ? $_SESSION['state_residence'] : '') ?>">
                                            <?php include 'data/states.html'; ?>
                                        </select>
                                    </label>
                                </div>
                                <div class="medium-3 columns" id="states_international">
                                    <label for="state_province">
                                        <legend>State/Province of Residence</legend>
                                        <input autocomplete="off" type="text" id="state_province" name="state_province" placeholder="Enter your state or province" value="<?php echo (isset($_SESSION['state_province']) ? $_SESSION['state_province'] : '') ?>" />
                                    </label>
                                </div>

                                <div class="medium-3 columns shrink">
                                    <label for="city">
                                        <legend>City of Residence</legend>
                                        <input type="text" id="city" placeholder="City" name="city_residence" value="<?php echo (isset($_SESSION['city_residence']) ? $_SESSION['city_residence'] : '') ?>" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php
                    } ?>
                </div>
        </div>
        </fieldset>
        </div>
        <div class="row" id="education_div_1">
            <div style="padding-left: 5px;">
                <h2><strong>EDUCATIONAL INFORMATION</strong></h2>
            </div>
        </div>

        <div class="row" id="education_div_2">
            <div class="medium-12 column sectionbox">
                <legend><strong>University/College</strong></legend>
                <?php if (isset($_SESSION['university']) && count($_SESSION['university']) > 0) : ?>
                    <?php foreach ($_SESSION['university'] as $key => $value) : ?>
                        <div id="education_entries" class="row education_entries">
                            <div class="small-12 column" style="float: unset;">
                                <label for="university_name">University Name
                                    <input type="text" class="university_name" id="university_name" name="university[]" placeholder="Search University(Min 3 Character)" value="<?php echo $value ?>" />
                                </label>
                            </div>
                            <div class="small-12 column">
                                <label for="major">Major
                                    <input type="text" class="major" id="major" name="major[]" placeholder="Name of the Major" value="<?php echo $_SESSION['major'][$key] ?>" />
                                </label>
                            </div>
                            <div class="small-12 column">
                                <label for="degree">Degree
                                    <input type="text" class="degree" id="degree" name="degree[]" placeholder="Name of the degree earned" value="<?php echo $_SESSION['degree'][$key] ?>" />
                                </label>
                            </div>
                            <div class="small-6 column delete_edu_button" style="width: unset; float: unset;">
                                <label for="delete_college_education">
                                    <button type="button" id="delete_college_education" class="alert button delete_education_entry education_button"><span>Delete</span></button>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div id="education_entries" class="row education_entries">
                        <div class="small-12 column" style="float: unset;">
                            <label for="university_name">University Name
                                <input type="text" class="university_name" id="university_name" name="university[]" placeholder="Search University(Min 3 Character)">
                            </label>
                        </div>
                        <div class="small-12 column">
                            <label for="major">Major
                                <input type="text" class="major" id="major" name="major[]" placeholder="Name of the Major">
                            </label>
                        </div>
                        <div class="small-12 column">
                            <label for="degree">Degree
                                <input type="text" class="degree" id="degree" name="degree[]" placeholder="Name of the degree earned">
                            </label>
                        </div>
                        <div class="small-6 column delete_edu_button" style="width: unset; float: unset;">
                            <label for="delete_college_education">
                                <button type="button" id="delete_college_education" class="alert button delete_education_entry education_button"><span>Delete</span></button>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="small-6 column add_another_univ_inst" style="width: 100%; text-align: center">
                    <label for="add another college/university">
                        <button class="success button education_button" style="background-color:#65ba79;" id="addUniversity" type="button">
                            <span>Add another college/university</span>
                        </button>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" id="education_div_3">
            <div class="medium-12 column sectionbox">
                <legend><strong>Other Education (Non University)</strong></legend>
                <?php if (isset($_SESSION['institution_name']) && count($_SESSION['institution_name']) > 0) : ?>
                    <?php foreach ($_SESSION['institution_name'] as $key => $value) : ?>
                        <div id="other_education_entries" class="row other_education_entries">
                            <div class="univ_details">
                                <div class="small-12 column">
                                    <label for="institution_name">Institution Name
                                        <input type="text" class="institution_name" id="institution_name" name="institution_name[]" placeholder="Name of the Institution" value="<?php echo $value ?>" />
                                    </label>
                                </div>
                                <div class="small-12 column">
                                    <label for="other_degree">Degree/Certificate/Training
                                        <input type="text" class="other_degree" id="other_degree" name="other_degree[]" placeholder="Name of the Degree, Certificate or Training" value="<?php echo $_SESSION['other_degree'][$key] ?>" />
                                    </label>
                                </div>
                                <div class="small-6 column delete_edu_button" style="width: unset;">
                                    <label for="delete_other_education">
                                        <button type="button" id="delete_other_education" class="alert button delete_other_education_entry education_button"><span>Delete</span></button>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div id="other_education_entries" class="row other_education_entries">
                        <div class="univ_details">
                            <div class="small-12 column">
                                <label for="institution_name">Institution Name
                                    <input type="text" class="institution_name" id="institution_name" name="institution_name[]" placeholder="Name of the Institution">
                                </label>
                            </div>
                            <div class="small-12 column">
                                <label for="other_degree">Degree/Certificate/Training
                                    <input type="text" class="other_degree" id="other_degree" name="other_degree[]" placeholder="Name of the Degree, Certificate or Training">
                                </label>
                            </div>
                            <div class="small-6 column delete_edu_button" style="width: unset;">
                                <label for="delete_other_education">
                                    <button type="button" id="delete_other_education" class="alert button delete_other_education_entry education_button"><span>Delete</span></button>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="small-6 column add_another_univ_inst" style="width: 100%; text-align: center">
                    <label for="add_another_institution">
                        <button class="success button education_button" style="background-color:#65ba79;" id="addInstitution" type="button">
                            <span>Add another institution</span>
                        </button>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="prev_save_continue_row">
                <div id="prev_save" style="font-weight: normal;">
                    <div class="prev_button large-4 small-8 column">
                        <button class="primary button" id="previous" type="button" onclick="submit_validation('add_artist_profile.php')">
                            <span>&lt; Previous</span>
                        </button>
                    </div>
                    <div class="save_and_next large-3 small-8 column">
                        <button class="primary button" id="next" type="button" onclick="submit_validation('add_artist_biography.php')">
                            <span><?php echo (($_SESSION['timeline_flow'] == "view") ? "" : "Save & ") ?>Next &gt;</span>
                        </button>
                    </div>
                </div>
                <div class="save_and_continue large-5 small-12 columns">
                    <button class="btn float-right secondary button mrl27p" id="next1" type="button" onclick="submit_validation('profiles.php')">
                        <span>Save & Continue Later</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</body>


<script type="text/javascript">


    function ethnicityvalidation() {
        var ethnicity = document.getElementById("ethnicity_other_text").value;
        if (ethnicity == "") {
            $('#otherethnicityvalidation').html("Cannot be Empty !!");
        }
    }

    function submit_validation(nextPage) {

        let validForm = true;
        var genderVal = document.getElementById("genderList");
        var selectedVal = genderVal.options[genderVal.selectedIndex].value;
        if (selectedVal == "other") {
            var txt = $("#gender_other_text").val();
            if (txt == "") {
                alert("Please describe your gender")
                validForm = false;
            }
        }
        
        var ethnicityVal = document.getElementById("ethnicityList");
        var selectedVal2 = ethnicityVal.options[ethnicityVal.selectedIndex].value;
        if (selectedVal2 == "Other") {
            var txt = $("#ethnicity_other_text").val();
            if (txt == "") {
                alert("Please describe your ethnicity")
                validForm = false;
            }
        }

        console.log("checking if form valid...")
        if (validForm) {
            console.log("form valid, submitting")
            submitJson('#add_artist_personal_id', 'artistcontroller.php', null, window.open.bind(null, nextPage, '_self'));
        }
    }



    function datevalidation() {

        var birthdate = document.getElementById('date_of_birth');
        var date = new Date();
        birth = new Date(birthdate.value);
        console.log("DOB IS " + birthdate.value);

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

        if (!birth || birth == "") {
            document.getElementById('date_message').style.display = "block";
            document.getElementById("date_message").innerHTML = "Date of Birth is a required field.";
            return false;
        }
        if (date < birth) {
            document.getElementById('date_message').style.display = "block";
            document.getElementById("date_message").innerHTML = "Date of Birth cannot be in future!";
            return false;
        } else if (today === birthdate.value) {
            document.getElementById('date_message').style.display = "block";
            document.getElementById("date_message").innerHTML = "Date of Birth cannot be today!";
            return false;
        } else {
            document.getElementById('date_message').style.display = "none";
        }
        return true;
    }

    var items = [];
    var noOfEducation = $(".education_entries").length;
    var noOfOther = $(".other_education_entries").length;

    $(".delete_education_entry").click(function() {
        if (noOfEducation > 1)
            $(this).closest(".education_entries").remove();
        else //Clear the text if there is only a single
            $(this).closest(".education_entries").find('input:text').val('');

        noOfEducation = $(".education_entries").length;
        noOfOther = $(".other_education_entries").length;
    });

    $(".delete_other_education_entry").click(function() {
        if (noOfOther > 1)
            $(this).closest(".other_education_entries").remove();
        else
            $(this).closest(".other_education_entries").find('input:text').val('');
        noOfEducation = $(".education_entries").length;
        noOfOther = $(".other_education_entries").length;

    });

    // if the user selects "other" for ethnicity, display the
    // other text box for input
    $("input[name='ethnicity']").click(function() {
        if ($("input[name='ethnicity']:checked").val() == "Other") {
            $("#ethnicity_other_text").val("");
            $("#ethnicity_other_text_label").show();
        } else {
            $("#ethnicity_other_text_label").hide();
        }
    });

    // if the user picks US as a country, show the state box.
    // otherwise, use a free text box for province
    $("#country_residence").change(function() {
        var currCountry = $(this).val();
        if (currCountry == "United States of America") {
            $("#states_US").show();
            $("#states_international").hide();
        } else {
            $("#state_province").val("");
            $("#states_international").show();
            $("#states_US").hide();
        }
    });


    $("#addUniversity").click(function() {

        noOfEducation = noOfEducation + 1;
        var clone = $('.education_entries:last').clone();
        clone.find("input:text").val("");
        clone.insertAfter('.education_entries:last');
        clone.find(".university_name").autocomplete({
            source: items
        });

        clone.find(".university_name").autocomplete("option", "minLength", 3);

    });

    $("#addInstitution").click(function() {

        noOfOther = noOfOther + 1;
        var clone = $('.other_education_entries:last').clone();
        clone.find("input:text").val("");
        clone.insertAfter('.other_education_entries:last');

        // $(".delete_other_education_entry").click(function() {

        //     if (noOfOther > 1) {
        //         $(this).closest(".other_education_entries").remove();
        //         noOfEducation = $(".education_entries").length;
        //         noOfOther = $(".other_education_entries").length;
        //     } else {
        //         $(this).closest(".other_education_entries").find('input:text').val('');
        //     }
        // });
    });

    $(document).ready(function() {
        if ($("#is_user_artist").val() == 'other') {
            // if($("#profile_complete_status").val() == '0'){
            //     showRefNote();
            // }
            $(".add-reference-button").show();
        } else {
            $(".add-reference-button").hide();
        }

        // hide all the optional boxes by default to clear the state
        $("#gender_other_text_label").hide();
        $("#ethnicity_other_text_label").hide();
        $("#states_US").hide();
        $("#states_international").hide();

        // then display them as appropriate
        if ($("input[name='ethnicity']:checked").val() == "other") {
            $("#ethnicity_other_text_label").show();
        }

        if ($("input[name='gender']:checked").val() == "other") {
            $("#gender_other_text_label").show();
        }

        if (typeof country_res !== 'undefined' && country_res !== "") {
            $("#country_residence").find("option[value='" + country_res + "']").attr('selected', 'selected');
            if (country_res == "United States of America") {
                $("#states_US").show();
                $("#states_international").hide();
            } else {
                $("#states_international").show();
                $("#states_US").hide();
            }
        } else {
            $("#states_US").show();
            $("#states_international").hide();
        }

        if (typeof country_b !== 'undefined' && country_b !== "") {
            $("#country_birth").find("option[value='" + country_b + "']").attr('selected', 'selected');
            //console.log($(".country_birth").value);
            //console.log("Country of birth set to "+country_b);
        } else {
            console.log("Contry of Birth Undefined");
        }

        if (typeof state_res !== 'undefined' && state_res !== "") {
            console.log(state_res);
            $("option[value='" + state_res + "']").attr('selected', 'selected');
        }

        if (disabled_input) {
            $('input').attr('disabled', 'true');
            $('select').attr('disabled', 'true');
            $('.education_button').hide();
        }

        //NEW CODE
        {
            const url = 'world_universities.json';

            $.getJSON(url, function(data) {
                $.each(data, function(key, val) {
                    items.push(val.name);
                });
            });
        }

        // this needs some serious performance tuning... the autocomplete takes
        // a zillion hours to render if you type "university"
        $(".university_name").autocomplete({
            source: items
        });

        $(".university_name").autocomplete("option", "minLength", 2);



        var selectedGender = $("#genderList").val();
        if (selectedGender == "other") {
            $("#gender_other_text_label").show();
        } else {
            $("#gender_other_text_label").hide();
        }

        $("#genderList").change(function() {
            var selectedGender = $("#genderList").val();
            console.log("selected gender = " + selectedGender);
            if (selectedGender == "other") {
                console.log("selected other gender");
                $("#gender_other_text_label").show();
            } else {
                console.log("otherG not selected");
                $("#gender_other_text_label").hide();
            }
        });


        var selectedEthnicity = $("#ethnicityList").val();
        if (selectedEthnicity == "Other") {
            $("#ethnicity_other_text_label").show();
        } else {
            $("#ethnicity_other_text_label").hide();
        }


        $("#ethnicityList").change(function() {
            var selectedEthnicity = $("#ethnicityList").val();
            console.log("selected ethnicity = " + selectedEthnicity);
            if (selectedEthnicity == "Other") {
                console.log("selected other enthnicity");
                $("#ethnicity_other_text_label").show();
            } else {
                console.log("otherE not selected");
                $("#ethnicity_other_text_label").hide();
            }
        });
    });
</script>

<?php
include 'footer.php';
?>

<script>
    var x = 2;
    for (var i = 0; i < admin_result.length; i++){
        if (admin_result[i]["feature_id"] == 7){
            if (admin_result[i]["feature_enabled"] == 0){

                $("#gender_div").hide();
                x = x - 1;
            }
        }
    };

    for (var i = 0; i < admin_result.length; i++){
        if (admin_result[i]["feature_id"] == 8){
            if (admin_result[i]["feature_enabled"] == 0){

                $("#ethnicity_div").hide();
                x = x - 1;

            }
        }
    };

    if (x==0){
        $("#identity_div").hide();
    };

    for (var i = 0; i < admin_result.length; i++){
        if (admin_result[i]["feature_id"] == 9){
            if (admin_result[i]["feature_enabled"] == 0){

                $("#location_div").hide();

            }
        }
    };

    for (var i = 0; i < admin_result.length; i++){
        if (admin_result[i]["feature_id"] == 10){
            if (admin_result[i]["feature_enabled"] == 0){

                $("#education_div_1").hide();
                $("#education_div_2").hide();
                $("#education_div_3").hide();

            }
        }
    };

</script>

<script>
    function addArtistProfileLogs(addLog){
        if(addLog == "true"){
            $.ajax({
                url:"logcontroller.php",
                type:'POST',
                data:JSON.stringify({
                    "action":"addUserLogs",
                    "data":{'user': '<?php echo($_SESSION['user_id']);?>', 'oper': 'Edited personal information', 'det': '<?php echo $_SESSION["artist_profile_id"] ?>'}
                }),
                success:function(){
                }
            })
        }
	}
</script>


</html>

