<?php

require 'util.php';
require 'connect.php';
my_session_start();
include 'menu.php';



$query = "SELECT * FROM artist_profile;";

$conn = getDbConnection();
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$result = $statement->fetchAll();

$result = json_encode($result);






$query = "SELECT * FROM artist_social;";

$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$social_result = $statement->fetchAll();

$social_result = json_encode($social_result);





$query = "SELECT * FROM admin_features;";

$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$admin_result = $statement->fetchAll();

$admin_result = json_encode($admin_result);





?>


<html lang="en">

<head>

	<script>
		var allartistnames = <?php echo $result ?>;
		var socialMediaResults = <?php echo $social_result ?>;
		var admin_result = <?php echo $admin_result ?>;

		console.log(admin_result);
	</script>

	<title>Explore the Network | Dancestry</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.1/foundation.min.css">


	<link rel="stylesheet" href="css/lineage_style.css" type="text/css" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <script type="text/javascript" src="js/UserPopUp.js"></script> -->
	<!-- <script type="text/javascript" src="js/lineage_network_default.js"></script> -->
	<script type="text/javascript" src="js/tutorial.js"></script>
	<script src="js/platform.js"></script>
	<script type="text/javascript" src="js/browserCheck.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


	<script>
		const PROFILE_ID = <?php
							if (isset($_SESSION["user_id"])) {
								//user not login
								if (!is_null($_SESSION["profile_id"])) {
									echo $_SESSION["profile_id"];
								} else {
									echo "undefined";
								}
							} else {
								echo "undefined";
							}
							?>;

		window.onload = function() {
			strict_check();
			// mobile_warning();
			initNetwork();
			initSearchMenu();
			initRelationTab();
			promptUserFirstTimeTutorial();
		};
	</script>
</head>

<body>

	<div id="lineage_opacity" onclick="closeBack()"></div>

	<style>
		#lineage_opacity {
		background: rgba(0, 0, 0, 0.5);
		position: absolute;
		width: 100%;
		height: 0%;
		display: block;
		position: fixed;
		z-index: 2;
		}
	</style>

	<script>
		function closeBack(){
			document.getElementById("lineage_opacity").style.height = "0%";
			$("#filter_close_button").click();
		}
	</script>


	<div id="svg_arrow" style="position: absolute; z-index: 120;display: none;width: 500px;height: 500px">
		<svg width="100%" height="100%">
			<defs>
				<marker id="arrow" viewBox="0 -5 10 10" refX="5" refY="0" markerWidth="4" markerHeight="4" orient="auto">
					<path d="M0,-5L10,0L0,5" class="arrowHead" fill="red"></path>
				</marker>
			</defs>
			<line x1="95%" y1="5%" x2="5%" y2="95%" style="stroke:rgb(255,0,0);stroke-width:8" stroke-width="10" marker-end="url(#arrow)"></line>
		</svg>
	</div>


	<div hidden id="invalidInputOverlay">
		<div id="invalidInputPopup">
			<h2>Invalid Input!</h2>
			<h4>Please correct your search or filter criteria before continue.</h4>
			<p>This overlay will disapper automatically in 1s.</p>
		</div>
	</div>













	<script>
		$(document).ready(function() {
			$(".switch input").on("change", function(e) {
				const isOn = e.currentTarget.checked;

				if (isOn) {
					$("#filter_div").show();
				} else {
					$("#filter_div").hide();
				}
			});
		});
	</script>



	<style>
		.switch {
			position: relative;
			display: inline-block;
			top: 0px;
			width: 60px;
			height: 32px;
		}

		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		.slider {
			position: absolute;
			cursor: pointer;
			height: 30px;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
		}

		.slider:before {
			position: absolute;
			content: "";
			height: 26px;
			width: 26px;
			left: 4px;
			bottom: 2px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
		}

		input:checked+.slider {
			background-color: #2C9447;
		}

		input:focus+.slider {
			box-shadow: 0 0 1px #2C9447;
		}

		input:checked+.slider:before {
			-webkit-transform: translateX(26px);
			-ms-transform: translateX(26px);
			transform: translateX(26px);
		}

		.slider.round {
			border-radius: 20px;
		}

		.slider.round:before {
			border-radius: 50%;
		}
	</style>









	<div id="network_div">

		<img src="" alt="Workplace" usemap="#workmap" width="75" height="75" id="mainImage" style="position: absolute;z-index: 10; display: none;">


		<button id="filter_toggle"><i class="fa fa-search" aria-hidden="true" style="background: #2C9447; color: white; display: inline-block; font-size: 26px; z-index: 1; position: fixed; top: 69px; text-align: center; left: 0px; padding-top: 0px; vertical-align: text-top; border: 1px solid rgba(0,0,0,0.5); display: inline-block; width: 100%;"><span style="font-size: 20px; font-style: italic;"> Search Options</span></i></button>



		<div id="network_row" class="row">

			<div id="filter_div" class="small-12 medium-12 large-3 columns">

				<div id="searchbox_row" class="row">

					<div id='search_column' class="large-13 columns" style="direction: ltr; overflow: auto; height: 100%;">

						<div id="topSearch_text" style="float:left; font-size:20px;font-family: arial, sans-serif;font-weight:normal;">
							Search
						</div>

						<button id="filter_close_button" style="display: inline-block; position: relative; float: right; right: -10px; padding: 5px; padding-top: 5px; font-size: 26px; color: #2c9447;">&#x2715;</button>

						<button class="tutorialBot lineageBots" id="Tutor" onclick="tutorialWelcome()">Tutorial</button>


						<label for="searchbox"></label><input id="searchbox" type="search" placeholder="Enter Artist Name" />

						<div hidden id="addArtist" class="addArtist">
						</div>
						<button class='addArtistSearch' id='addArtistSearch'>Add Another Artist
							<span class="lineagePlus">+</span></button>
						<hr>
						</hr>
						<div hidden id='addGenre' class='addGenre'>
							<label id='addGenreLabel'>Genre</label>
						</div>
						<button class='addArtistSearch' id='addGenreSearch'>Add Genre to Search
							<span class="lineagePlus">+</span></button>
						<hr>
						</hr>
						<div hidden id="addArtistType" class='addArtistType'>
							<label id='addArtistTypeLabel'>Artist Type</label>
						</div>
						<button class='addArtistSearch' id='addArtistTypeSearch'>Add Artist Type
							to Search <span class="lineagePlus">+</span></button>

						</hr>
						<br />
						<button class='foldallsearch lineageBots' id='foldAllSearch'>Additional Searches</button>

			<div id="addallsearchtype" class="addAllSearchtype" style="display:none ;transition: 0.2s ease-out;">
							<div hidden id="addCountry" class='addCountry'>
								<label id='addCountryLabel'>Country</label>
						</button>
					</div>
					<button class='addArtistSearch' id='addCountrySearch'>Add Country to
						Search <span class="lineagePlus">+</span></button>
					<hr>
					</hr>
					<div hidden id="addState" class='addState'>
						<label id='addStateLabel'>State</label>
					</div>
					<button class='addArtistSearch' id='addStateSearch'>Add State to
						Search
						<span class="lineagePlus">+</span></button>
					<hr>
					</hr>
					<div hidden id="addCity" class='addCity'>
						<label id='addCityLabel'>City</label>
					</div>
					<button class='addArtistSearch' id='addCitySearch'>Add City to Search
						<span class="lineagePlus">+</span></button>
					<hr>
					</hr>
					<div hidden id="addUniversity"><label>University</label><input id="universitySearchbox" type="search" placeholder="Enter University" style="display:inline-block; width:85%" />
						<button id="closeUniversitySearch" style="display:inline-block; padding: 4px; border: 1px solid #999; height:39px">X
						</button>
					</div>
					<button hidden class='addArtistSearch' id='addUniversitySearch'>Add
						University to
						Search <span class="lineagePlus">+</span></button>
					<!-- <hr>
				</hr> -->
					<div hidden id="addDegree"><label>Degree</label><input id="degreeSearchbox" type="search" placeholder="Enter Degree" style="display:inline-block; width:85%" />
						<button id="closeDegreeSearch" style="display:inline-block; padding: 4px; border: 1px solid #999; height:39px">X
						</button>
					</div>
					<button hidden class='addArtistSearch' id='addDegreeSearch'>Add Degree
						to Search
						<span class="lineagePlus">+</span></button>
					<!-- <hr>
				</hr> -->
					<div hidden id="addMajor"><label>Major</label><input id="majorSearchbox" type="search" placeholder="Enter Major" style="display:inline-block; width:85%" />
						<button id="closeMajorSearch" style="display:inline-block; padding: 4px; border: 1px solid #999; height:39px">X
						</button>
					</div>
					<button hidden class='addArtistSearch' id='addMajorSearch'>Add Major
						to Search
						<span class="lineagePlus">+</span></button>
					<!-- <hr>
				</hr> -->
					<div hidden id="addEthnicity" class='addEthnicity'>
						<label id='addEthnicityLabel'>Ethnicity</label>
					</div>
					<button class='addArtistSearch' id='addEthnicitySearch'>Add Ethnicity
						to
						Search <span class="lineagePlus">+</span></button>
					<hr>
					</hr>
					<div hidden id="addGender" class='addGender'>
						<label id='addGenderLabel'>Gender</label>
					</div>
					<button class='addArtistSearch' id='addGenderSearch'>Add Gender to
						Search
						<span class="lineagePlus">+</span></button>
					<hr>
					</hr>
					<div hidden id="addLivingStatus">
						<label>Living Status</label>
						<label for="livingStatusSearchbox"></label>
						<select name="livingStatus" id="livingStatusSearchbox" style="display:inline-block; width:83%">
							<option value=""></option>
							<option value="alive">Alive</option>
							<option value="deceased">Deceased</option>
							<option value="unlisted">Unlisted</option>
						</select>
						<button id="closeLivingStatusSearch" style="display:inline-block; padding: 4px; border: 1px solid #999; height:39px">X
						</button>
					</div>
					<button hidden class='addArtistSearch' id='addLivingStatusSearch'>Add
						Living
						Status to Search <span class="lineagePlus">+</span></button>
				</div>

				
				<input class="searchbot lineageBots halfBots" id="search" type="submit" value="Search">
				<input class="clearAllBot lineageBots halfBots" id="clear" type="submit" value="Clear All" />
				<input class="searchAll lineageBots" id="searchAll" type="submit" value="Show Entire Network" />
				<input class="listallartists lineageBots" id="listallartists" type="submit" value="List All Artists" />
				<input class="familytree lineageBots" id="familytree" type="submit" value="Family Tree" style="display: none;" />


			</div>
		</div>

		<div hidden id="searchbox_node_id"></div>
	</div>



	<div id="load" class="loader-frame small-12 medium-12 large-9 columns" style="display: none;">
		<div id="loader_circles_div">
			<div class="circle loader1"></div>
			<div class="circle1 loader2"></div>
		</div>
	</div>
	<div id="extraControls" style="display: none;" class="p_up">
		<div id="dialog-2" style="font-weight: bold;width:800px;height:700px;background-color:#E7FBE9" title="Search the Network">
			<div class="row">
				<p>You can search for artists and explore their network by using the left side search panel of this
					page.<strong></strong></p>
			</div>
			<button class="primary button" style="margin:auto; display:block;" id="accept" type="submit" name="ok" onclick="closeDefinitions();">
				<span>OK</span>
			</button>
		</div>
	</div>

	<div id="network_display_div" class="small-12 medium-12 large-9 columns">
		<div id="NoResultWindow" class="NoResultWindowClass">
			<div class="TutorTextStyle">No result!</div>
			<button class='TutorButton' onclick="NoresultClose()">OK</button>
		</div>
		<div id="FilterWindow" class="filterWindow_popup">


			<div id="FilterWindow_search_rows" class="filterWindow_popup_left">

				<div hidden id="addGenre_popup" class="addGenre_popup"><label id="addGenre_popupLabel"><span style="font-family: arial, sans-serif;font-weight:normal;font-size:20px; margin:15px">Genre</span></label>
				</div>
				<button class='addArtistSearch2' id='addGenre_popupSearch'>Filter by Genre<span>+</span></button>
				<hr>
				</hr>
				<div hidden id="addArtistType_popup" class="addArtistType_popup"><label id="addArtistType_popupLabel"><span style="font-family: arial, sans-serif;font-weight:normal;font-size:20px; margin:15px">Artist Type</span></label>
				</div>
				<button class='addArtistSearch2' id='addArtistType_popupSearch'>Filter by Artist Type<span style="font-size:16px">+</span></button>
				<hr>
				</hr>

				<div hidden id="addCountry_popup" class="addCountry_popup"><label id="addCountry_popupLabel"><span style="font-family: arial, sans-serif;font-weight:normal;font-size:20px; margin:15px">Country</span></label>
				</div>
				<button class='addArtistSearch2' id='addCountry_popupSearch'>Filter by Country<span style="font-size:16px">+</span></button>
				<hr>
				</hr>
				<div hidden id="addState_popup" class="addState_popup"><label id="addState_popupLabel"><span style="font-family: arial, sans-serif;font-weight:normal;font-size:20px; margin:15px">State</span></label>
				</div>
				<button class='addArtistSearch2' id='addState_popupSearch'>Filter by State<span style="font-size:16px">+</span></button>
				<hr>
				</hr>
				<div hidden id="addCity_popup" class="addCity_popup"><label id="addCity_popupLabel"><span style="font-family: arial, sans-serif;font-weight:normal;font-size:20px; margin:15px">City</span></label>
				</div>
				<button class='addArtistSearch2' id='addCity_popupSearch'>Filter by City<span style="font-size:16px">+</span></button>
				<hr>
				</hr>

				<div hidden id="addRelationship_popup"><label><span style="font-size:18px;margin-top: 15px;margin-left: 15px"></span></label>
					<table>
						<caption>Relationship filter
							<button id="closeRelationship_popup" class="closeButtonFilter2">X</button>
						</caption>
						<tr>
							<th>Studied Under</th>
							<th><input id="study_rel" type="checkbox" style="display:inline-block; width:86%" checked /></th>
						</tr>
						<tr>
							<th>Collaborated With</th>
							<th><input id="coll_rel" type="checkbox" style="display:inline-block; width:86%" checked /></th>
						</tr>
						<tr>
							<th>Danced in Work of</th>
							<th><input id="dance_rel" type="checkbox" style="display:inline-block; width:86%" checked /></th>
						</tr>
						<tr>
							<th>Influenced By</th>
							<th><input id="infl_rel" type="checkbox" style="display:inline-block; width:86%" checked /></th>
						</tr>
					</table>
					<!-- All relationships<input id="relationshipCheckbox_popup" type="checkbox" style="display:inline-block; width:86%" /> -->

				</div>
				<button class='addArtistSearch2' id='addRelatioshipSearch_popup'>Filter by Relationship<span style="font-size:16px">+</span></button>

				<hr>
				</hr>
			</div>
			<div id="FilterWindow_button_rows" class="filterWindow_popup_right">
				<button id="filterWindClose" class='closeFilterWindow' aria-label='Close'>X</button>
				<button class='buttonGroup' id="search_popup" style="margin-bottom: 20px">Filter</button>
				<button class='clearButtonFilter' id="clear_popup" style="margin-bottom: 20px">Clear</button>
			</div>
		</div>
		<div id="AddRelationWindow" class="AddrelationClass">
			<img id="spin_loading_relation" src="./img/Spin_256.gif" alt="Loading please Wait" ">
					<button id=" close_EventPopUp" class="closeButtonFilter" onclick="closeAddRelationPopUp();">X</button>
			<div id="AddRelationWindow_content">
				<div style="background:#ddd; border-radius:10px; padding:10px; margin-bottom:15px;">
					<div class="row">
						<ul id=AddRelationPerson>
							<div id="AddRelationPersonMess" style="font-size:25px;">

							</div>
						</ul>
					</div>
					<div class="row">
						<div class="small-12 column" style="font-size:22px;">
							<label style="font-size:22px;">Type of Relationship (Check All that Apply):</label>
						</div>
					</div>
					<br>
					<div class="row" style="padding-left:0px;padding-right:0px;">
						<div class="small-3 column" style="padding-left:0px;padding-right:0px;">
							<input type="checkbox" id="studied" name="studied" class="rel_studied" value="Studied Under">
							<label style="font-size:22px;" for="studied">Studied Under</label>

						</div>
						<div class="small-3 column" style="padding-left:0px;padding-right:0px" style="font-size:22px;">
							<input type="checkbox" id="danced" name="danced" class="rel_danced" value="Danced in the Work of">
							<label style="font-size:22px;" for="danced">Danced in the Work of </label>

						</div>
						<div class="small-3 column" style="padding-left:0px;padding-right:0px" style="font-size:22px;">
							<input type="checkbox" id="collaborated" name="collaborated" class="rel_collaborated" value="Collaborated With">
							<label style="font-size:22px;" for="collaborated">Collaborated With </label>

						</div>
						<div class="small-3 column" style="padding-left:0px;padding-right:0px" style="font-size:22px;">
							<input type="checkbox" id="influenced" name="influenced" class="rel_influenced" value="Influenced By">
							<label style="font-size:22px;" for="influenced">Influenced By </label>

						</div>
					</div>
					<div class="row">
						<div class="small-8 column">
							<div id="danced_options" style="display:none;">
								<p>Please list the titles of the works you have danced in by this artist. (separate
									multiple titles with a comma)</p>
								<input type="text" id="danced_titles" class="danced_titles" name="Danced_titles" placeholder="Dance titles">
							</div>
						</div>
					</div>
				</div>
				<div>

					<button class="AddRelationButton " id="add_relation_button" type="button">
						<span style="font-size:22px;">Add to network</span>
					</button>
				</div>
			</div>
		</div>
		<div id="TutorShowUp" class="Tutorshowup">
			<div id="TutorText1" class="TutorShowText">
				&ensp;Dancestry&ensp;TUTORIAL&ensp;&ensp;
			</div>
		</div>

		<div id="allTutorialWindow">
			<div id="TutorWindow" class="TutorWindow_popup">
				<button id="close_welcome_window_tutorial" class="closeButtonFilter">X</button>
				<div id="TutorText" class="TutorTextStyle">
					In Dancestry Network
					<br>You can search,
					filter, and explore our data!<br> To get started with the tutorial,
					click <span style="font-size: 20px;font-weight:bold">"Get Started"</span>.

				</div>
				<div id="TutorWindow" class="TutorWindow">
					<button class='TutorButton' id="Tutor_start_network">Get Started</button>
					<button class='TutorButton' id="Skip_rel">End Tutorial</button>
				</div>
			</div>
			<div id="NetworkTutorWindow" class="TutorWindow_popup">
				<div class="currentChapterText">Tutorial Chapter I: Search<br></div>
				<button class='closeButtonFilter' id="skipNetwork_rel">X</button>
				<ul class="progressbar">

					<li class="completeone" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
						<p>
							Search</p>
					</li>
					<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
						<p>
							Visualization Network</p>
					</li>
					<li class="completethree" style="cursor: pointer; color: black;--my-color-var: white;">
						<p>Filter</p>
					</li>

				</ul>
				<div class="progress">


					<div class="progress-bar" style="width:8.3%"></div>
				</div>

				<div id="TutorText" class="TutorTextStyle">

					When searching the network, you can enter one or more search terms in the
					<br>search interface on the left hand side.
				</div>
				<div id="TutorWindow" class="TutorWindow">

					<button class='TutorButton' id="NetworkTutorNext">Next</button>
					<!--<input id="skipNetwork_rel" type="checkbox" style="display:inline-block" />-->

					<!-- <button class='buttonGroup' onclick="filterClose()">Close</button> -->
				</div>
			</div>
		</div>
		<div id="TutorWindowIfSkipped" class="TutorWindow_popup">
			<button id="close_skipped_window_tutorial" class="closeButtonFilter">X</button>
			<div id="TutorText" class="TutorTextStyle">
				You have skipped or completed parts of the tutorial previously.
				<br>Do you want to re-try the tutorial?
			</div>
			<div id="TutorWindow" class="TutorWindow">
				<button class='TutorButton' id="chapter_review_tutor_window">Yes</button>
				<button class='TutorButton' id="Tutor_start_network_if_skipped">No</button>

			</div>
		</div>
		<div id="FirstTimeTutorialWindow" class="TutorWindow_popup">
			<div id="TutorText" class="TutorTextStyle">
				Welcome to the Dancestry Network!
				<br>Would you like to learn how to explore the
				network?
				<br>(Next time you can use the [Tutorial] button on the left to redo it.)
			</div>
			<div id="TutorWindow" class="TutorWindow">
				<button class='TutorButton' id="first_time_yes">Yes</button>
				<button class='TutorButton' id="first_time_no">No</button>
				<button class='TutorButton' id="first_time_snooze">Later</button>
			</div>
		</div>
		<div id="CompleteFirstChapter" class="TutorWindow_popup">
			<button id="close_first_chapter_window_tutorial" class="closeButtonFilter">X</button>
			<div id="TutorText" class="TutorTextStyle">
				You have completed the search functionality chapter of the tutorial.
				<br>Do you want to continue where you left off?
			</div>
			<div id="TutorWindow" class="TutorWindow">
				<button class='TutorButton' id="continue_to_second_chapter">Continue</button>
				<button class='TutorButton' id="start_over_first_chapter">Start Over</button>
				<button class='TutorButton' id="skip_from_first_chapter">Skip All</button>
			</div>
		</div>
		<div id="CompleteSecondChapter" class="TutorWindow_popup">
			<div id="TutorText" class="TutorTextStyle">
				You have completed the network chapter of the tutorial.
				<br>Do you want to continue where you left off?
			</div>
			<div id="TutorWindow" class="TutorWindow">
				<button class='TutorButton' id="continue_to_third_chapter">Continue</button>
				<button class='TutorButton' id="start_over_second_chapter">Start Over</button>
				<button class='TutorButton' id="skip_from_second_chapter">Skip All</button>
			</div>
		</div>
		<div id="ChaptersSelect" class="TutorWindow_popup">
			<button id="close_chapter_select" class="closeButtonFilter">X</button>
			<div id="TutorText" class="TutorTextStyle">
				Here are all of the available chapters that you can review.
				<br>Which Functionality would you like to review again?
			</div>
			<div id="TutorWindow" class="TutorWindow">
				<button class='TutorButton' id="restart_first_chapter">Search</button>
				<button class='TutorButton' id="restart_second_chapter">Visualization Network</button>
				<button class='TutorButton' id="restart_third_chapter">Filters</button>
			</div>
		</div>
		<div id="FilterTutorWindow" class="TutorWindow_popup2">
			<button id="skipFilter_rel" class="closeButtonFilter">X</button>
			<div class="currentChapterText">Tutorial Chapter III: Filter</div>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: white;">
					<p>Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
					<p>Visualization
						Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p>Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:70%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle">

				Want to further refine your search? No problem, you can filter your search results
				<br>using the filter bar above the network area.
				<br>
				<span style="font-size: 22px;font-weight:bold">Click "Add Filter" button above</span>
				<br>
				<br>
			</div>
			<div id="FilterWindow_button_rows" class="filterWindow_popup_right">

			</div>
		</div>
		<div id="DisabledTutorWindow" class="TutorAddFilter_popup">
			<div class="currentChapterText">Tutorial Chapter III: Filter</div>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p style="font-size:11px; padding:5;">Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:85%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle2">
				Click
				<span style="font-size: 20px;font-weight:bold">"Filter by Genre"</span>.
			</div>

			<button class='closeButtonFilter' onclick="Skip()">X</button>

		</div>
		<div id="DisabledTutorWindow2" class="TutorAddFilter_popup">
			<div class="currentChapterText">Tutorial Chapter III: Filter</div>
			<button class='closeButtonFilter' onclick="Skip()">X</button>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p style="font-size:11px; padding:5;">Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:90%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle2">
				Let's filter Contemporary or Modern!
				<span style="font-size: 20px;font-weight:bold">Click "Next"</span>

			</div>
			<button class='buttonGroup2' id="typeInModernNext">Next</button>
		</div>
		<div id="DisabledTutorWindow3" class="TutorAddFilter_popup">
			<div class="currentChapterText">Tutorial Chapter III: Filter</div>
			<button class='closeButtonFilter' onclick="Skip()">X</button>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
					<p style="font-size:11px; padding:5;">Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p style="font-size:11px; padding:5;">Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:95%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle2">

				<span style="font-size: 24px;">Now, click </span>
				<span style="font-size: 24px;font-weight:bold">"Filter"</span>
				<br>

			</div>

		</div>
		<div id="DisabledTutorWindow4" class="TutorAddFilter_popup">
			<div id="TutorText" class="TutorTextStyle2">
				Click "Filter".

			</div>
		</div>
		<div id="FiltorCongrat" class="TutorWindow_popup2">
			<div class="currentChapterText">End of All Chapters</div>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: white;">
					<p>Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white;">
					<p>Visualization
						Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p>Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:100%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle">
				Congratulations, you have successfully added a "Contemporary or Modern" genre filter to your search
				results!
				<br>
				All the people you see in the network are in the "Contemporary or Modern" genre .
				<br>
				<span style="font-size: 22px;font-weight:bold">You have finished all tutorials!</span>
			</div>
			<div id="TutorWindow" class="TutorWindow">

				<!--<button class='TutorButton' id="FilterCongratNext">Close</button>-->
				<!--<input id="filterCongrat_rel" type="checkbox" style="display:inline-block" />-->
				<button class='TutorButton' id="filterCongrat_rel">Close</button>
				<!-- <button class='buttonGroup' onclick="filterClose()">Close</button> -->
			</div>
		</div>


		<div id="NetworkTutorWindow1.5" class="TutorWindow_popup">
			<div class="currentChapterText">Tutorial Chapter I: Search<br></div>
			<button class='closeButtonFilter' id="skipNetwork_rel2">X</button>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p>Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Visualization
						Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:16.6%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle">

				Let's take a look at Melanie Aceto's network
				<br>
				<span style="font-size: 22px;font-weight:bold">We have filled it out for you! </span>


			</div>
			<div id="TutorWindow" class="TutorWindow">

				<button class='TutorButton' id="NetworkTutorNext1">Next</button>

			</div>
		</div>
		<div id="NetworkTutorWindow2.0" class="TutorWindow_popup">
			<div class="currentChapterText">Tutorial Chapter I: Search<br></div>
			<button class='closeButtonFilter' id="skipNetwork_rel3">X</button>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p>Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Visualization
						Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:25%"></div>
			</div>
			<div id="TutorText" class="TutorTextStyle">
				<br>


				Now click "<span style="font-size: 22px;font-weight:bold">Search</span>" on the left.
				<br>
				<br>
				<br>
			</div>
			<div id="TutorWindow" class="TutorWindow">

			</div>
		</div>
		<div id="NetworkTutorWindow2" class="networkWindow2_popup">
			<button id="close_NetworkTutorWindow2" class="closeButtonFilter">X</button>
			<div class="currentChapterText">Next Chapter: Visualization Network</div>
			<ul class="progressbar">

				<li class="completeone" style="cursor: pointer; color: black;--my-color-var: #2C9447;">
					<p>Search</p>
				</li>
				<li class="completetwo" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Visualization
						Network</p>
				</li>
				<li class="completethree" style="cursor: pointer; color: black;--my-color-var: white">
					<p>Filter</p>
				</li>

			</ul>
			<div class="progress">

				<div class="progress-bar" style="width:37%"></div>

			</div>
			<div id="TutorText" class="TutorTextStyle">

				Your search result will appear in the network screen below. <br>
				Let's go to the next Chapter and see how it works.
			</div>
			<div id="TutorWindow2" class="TutorWindow">

				<button class='TutorButton' style="width:fit-content" id="NetworkTutorGotIt2">Next Chapter of
					Tutorial
				</button>
				<button class='TutorButton' style="display:none" id="skipNetwork2_rel">End Tutorial</button>
			</div>
		</div>
		<div hidden id="noLineagePopup" class="alert-box success">No lineage added for this user!</div>
		<div hidden id="noLineagePopup" class="suggestion-box success">Try double clicking the node to close it!
		</div>

		<div class="tab-content" id="networkTab" style="margin-right: 10%;">
			<div id="topFilterBar" class="rightClickDiv topFilter">
				<div id="topFilter_text">
					Add a filter to your search results below:
				</div>
				<div></div>

				<button id="addFilter" class="topFilterClass lineageBots" onclick="filterPopup()">Add Filter +</button>


				<div></div>
				<div id="AddedFilter">


				</div>
			</div>


			<div hidden id="searchTextValue"></div>
			<div hidden id="genreTextValue"></div>
			<div hidden id="uniTextValue"></div>
			<div hidden id="stateTextValue"></div>
			<div hidden id="countryTextValue"></div>
			<div hidden id="majorTextValue"></div>
			<div hidden id="degreeTextValue"></div>
			<div hidden id="ethnicityTextValue"></div>
			<div hidden id="bioTextValue"></div>
			<div id="search_text" class="searchTextClass" style="width:100%"></div>

			<div id="network_container" style="max-height: 90%;">

				<img id="spin_loading" src="./img/Spin_256.gif" alt="Loading please Wait" style="margin-top: 300px;">
				<div id="my_network" style="position: relative; overflow: hidden; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 100%; height: 100%;">

				</div>
				<div id="loadingBar">
					<div class="outerBorder">
						<div id="progress_text">0%</div>
						<div id="progress_border">
							<div id="progress_bar"></div>
						</div>
					</div>
				</div>


				<div id="EventPopUp" class="filterWindow_popup" style="left:15%; top:20%; min-height: 200px">
					<img id="spin_loading_event" src="./img/Spin_256.gif" alt="Loading please Wait">
					<button id="close_EventPopUp" class="closeButtonFilter" onclick="$('#EventPopUp').hide();">X
					</button>
					<div id="eventTable" style="display: none">

						<div id="EventPopUp_h1" class="TutorTextStyle">
						</div>


						<table>
							<thead style="display: table-header-group">
								<tr>
									<th style='text-align:center'>Event Name</th>
									<th style='text-align:center'>Location</th>
									<th style='text-align:center'>Date</th>
									<th style='text-align:center'>Time</th>
								</tr>
							</thead>
							<tbody id="artist_events">

							</tbody>
						</table>
					</div>
				</div>
				<ul id="network_node_menu" class="custom-menu">
					<li id="searchRelation">

					</li>

					<li id="hideRelation">


					</li id="foldRelation">

					<li id="Event">





					</li>

					<li id="AddRelation">

					</li>

					<li id="familyTreeMenu">

					</li>
				</ul>

			</div>

			<div hidden id="mySidenav" class="sidenav">
				<div class="row">
					<div class="large-10 column profile-details-class"></div>
					<div class="large-2 column profile-details-class">
					</div>
				</div>
				<div class="large-12 column profile-details-class" id="prof_space"></div>
			</div>

		</div>










	</div>




	<div class="large-12 column profile-details-class" id="prof_space_modal" style="display: none; overflow: hidden;"></div>




	<div id="artistlist_display_div" class="small-12 medium-12 large-9 columns" style="max-width: 80%;">

		<div id="artist_alphabets" class="small-12 medium-12 large-12 columns" style="padding: 5px; border-bottom: 1px solid black; overflow: scroll;">
		</div>

		<div id="my_artists" style="left: 25px; position: relative; overflow: scroll; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 100%; height: 100%;">
		</div>

	</div>



	<div id="familytree_display_div" class="small-12 medium-12 large-9 columns" style="position: relative; float: left; min-width: 60%; max-width: 60%;">

		<div class="row" id="familytree_buttons">
			<div class="large-12 small-12 columns">
				<input type="button" id="download_family" class="lineageBots" value="Download Family Lineage" style="font-size: 16px;" onclick="fam_down()">
				<input id="familytree_download_name" style="display: none;"></input>
				<input type="button" id="close_family" class="lineageBots" value="Close Family Lineage" style="font-size: 16px; float: right;">
			</div>
		</div>


		<style>
			#download_family{
				max-width: 48%;
			}
			#close_family{
				max-width: 48%;
			}
		</style>


		<div id="familytree_container">
			<div id="my_family_tree" style="position: relative; overflow: hidden; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 100%; height: 100%;">
				<div id="familytreenetwork">
				</div>

				<style type="text/css">
					#familytreenetwork {
						max-width: 800px;
						max-height: 1000px;

						border: 2px solid lightgray;
						position: relative;
						overflow: hidden;

					}

					/* #familytreenetwork .vis-network {
					position: absolute;
					top: -200px;
					} */
				</style>

			</div>
		</div>
	</div>

	</div>



	</div>







	<style>
		.card {
			padding: 1rem;
		}

		.cards {
			margin: 0 auto;
			display: grid;
			gap: 0rem;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
		}
	</style>














	<style type='text/css'>
		div.outerBorder {
			position: relative;
			top: 250px;
			width: 50%;
			height: 60px;
			margin: auto auto auto auto;
			border: 8px solid rgba(0, 0, 0, 0.1);

			background: -moz-linear-gradient(top,
					rgba(252, 252, 252, 1) 0%,
					rgba(237, 237, 237, 1) 100%);
			/* FF3.6+ */
			background: -webkit-gradient(linear,
					left top,
					left bottom,
					color-stop(0%, rgba(252, 252, 252, 1)),
					color-stop(100%, rgba(237, 237, 237, 1)));
			/* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,
					rgba(252, 252, 252, 1) 0%,
					rgba(237, 237, 237, 1) 100%);
			/* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,
					rgba(252, 252, 252, 1) 0%,
					rgba(237, 237, 237, 1) 100%);
			/* Opera 11.10+ */
			background: -ms-linear-gradient(top,
					rgba(252, 252, 252, 1) 0%,
					rgba(237, 237, 237, 1) 100%);
			/* IE10+ */
			background: linear-gradient(to bottom,
					rgba(252, 252, 252, 1) 0%,
					rgba(237, 237, 237, 1) 100%);
			/* W3C */
			border-radius: 72px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
		}

		#progress_text {
			position: absolute;
			top: 8px;
			left: calc(100% + 20px);
			width: 30px;
			height: 50px;
			margin: auto auto auto auto;
			font-size: 22px;
			color: #000000;
		}

		#progress_border {
			position: absolute;
			top: 8px;
			left: 10px;
			width: 90%;
			height: 30px;
			box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
			border-radius: 10px;
		}

		#progress_bar {
			position: absolute;
			top: 4px;
			left: 0px;
			width: 25px;
			height: 20px;
			border-radius: 11px;
			border: 2px solid rgba(30, 30, 30, 0.05);
			background: rgb(23, 219, 20);
			box-shadow: 2px 0px 4px rgba(0, 0, 0, 0.4);
		}

		#loadingBar {
			position: absolute;
			top: 0px;
			left: 0px;
			width: 100%;
			height: 100%;
			background-color: rgba(200, 200, 200, 0.8);
			-webkit-transition: all 0.5s ease;
			-moz-transition: all 0.5s ease;
			-ms-transition: all 0.5s ease;
			-o-transition: all 0.5s ease;
			transition: all 0.5s ease;
			display: none;
			opacity: 1;
		}



		.networkHint {
			text-align: center;
			border-color: #006400;
			border-width: 3px;
			background-color: white;
			border-style: solid;
			padding: 4px;
			width: 25%;
			left: 20%;
		}

		.AddrelationClass {
			font-family: arial, sans-serif;
			display: none;
			position: absolute;
			text-align: center;
			width: 800px;
			border-color: #006400;
			border-width: 3px;
			background-color: white;
			border-style: solid;
			padding: 4px;
			top: 20%;
			left: 10%;
			z-index: 80;
			border-style: solid;
		}

		.actives {

			background-color: #137525;
		}


		.actives:after {
			content: "\2212";
		}

		.bodydiv {

			overflow: auto;
			border: 25px;
			border-style: solid;
			border-color: black;
			position: absolute;
			left: 0;
			bottom: 0;
			top: 0;
			right: 0
		}


		.networkHint p {
			margin: 10px;
			font-size: 16pt;
			font-family: arial, sans-serif;
		}

		.networkHint button {
			/*text-align: center;*/
			/*padding: 5px;
            background-color: #1dc116;*/
		}

		.custom-menu {
			display: none;
			z-index: 100;
			position: absolute;
			overflow: hidden;
			border: 1px solid #CCC;
			white-space: nowrap;
			font-family: sans-serif;
			background: #FFF;
			color: #333;
			border-radius: 5px;
		}

		.custom-menu li {
			padding: 8px 12px;
			cursor: pointer;
		}

		.custom-menu li:hover {
			background-color: #DEF;
		}


		#relation_check {
			/* position: absolute;
            top: 0px;
            left: 0px;
            width: 902px;
            height: 200px; */
			margin-top: 2px;
			margin-bottom: 2px;
			padding-top: 12px;
			height: 60px;
			text-align: center;
			background-color: #d9d9d9;

		}

		#relation_check label {
			display: inline-block;

			background-color: #d9d9d9;
			font-size: large;
			padding: 4px;

		}


		#my_network {

			height: 1000px;
			border: 1px solid lightgray;
			/* background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url("./img/logo.png"); */
			background-repeat: no-repeat;
			background-size: 100%;
			cursor: grab;
		}

		#network_container {
			height: 1000px;
			text-align: center;

		}


		:root {
			--Studied_Under: #6666FF;
			--Collaborated_With: #40BF80;
			--Danced_in_the_Work_of: #3B2E5A;
			--Influenced_By: #29A3A3;
			--isArtistNodeColor: #1A3263;
			--notArtistNodeColor: #6E91D8;
		}

		.pic {
			height: 240px;
			overflow: hidden;
			width: 93%;
			margin-bottom: 1px;
		}

		.info {
			text-align: center;
			font-weight: bold;
			background-color: white;
			margin-bottom: 5px;
		}

		.p_up {
			margin-top: 250px;
			margin-left: 180px;
		}

		.name {
			text-align: center;
			font-weight: bold;
			margin-bottom: 2px;
		}

		.gender {
			text-align: center;
			margin-bottom: 2px;
		}

		.status {
			text-align: center;
			margin-bottom: 2px;
		}

		.education {
			text-align: center;
			margin-bottom: 2px;
			margin-left: 2px;
		}

		.genre {
			text-align: center;
			margin-bottom: 2px;
		}

		.lineage {
			text-align: left;
			font-weight: bold;
			margin-left: 2px;
		}

		/*thead {*/
		/*    display: none;*/
		/*}*/

		.biography {
			font-size: 15px;
			margin-bottom: 2px;
			color: #4743f7;
			text-align: center;
		}

		.bluenode:before {
			/* height: 20px;
        width: 20px;
        background-color: var(--isArtistNodeColor);
        border-radius: 50%;
        display:inline-block */
			content: '\25CF';
			font-size: 20px;
			border: 4px;
			color: rgb(121, 144, 212);
		}

		.rednode:before {
			content: '\25CF';
			font-size: 20px;
			color: rgb(39, 53, 98);
		}

		.redarrow:before {
			content: '\279B';
			font-size: 20px;
			color: var(--Studied_Under);
		}

		.yellowarrow:before {
			content: '\279B';
			font-size: 20px;
			color: var(--Collaborated_With);
		}

		.greenarrow:before {
			content: '\279B';
			font-size: 20px;
			color: var(--Danced_in_the_Work_of);
		}

		.bluearrow:before {
			content: '\279B';
			font-size: 20px;
			color: var(--Influenced_By);
		}

		.greyarrow:before {
			content: '\279B';
			font-size: 20px;
			color: grey;
		}

		.my-legend .legend-title {
			text-align: left;
			margin-bottom: 5px;
			font-weight: bold;
			font-size: 90%;
		}

		.my-legend .legend-scale ul {
			margin: 0;
			margin-bottom: 5px;
			padding: 0;
			float: left;
			list-style: none;
		}

		.my-legend .legend-scale ul li {
			font-size: 80%;
			list-style: none;
			margin-left: 0;
			line-height: 18px;
			margin-bottom: 2px;
		}

		.my-legend ul.legend-labels li span {
			display: block;
			float: left;
			height: 16px;
			width: 30px;
			margin-right: 5px;
			margin-left: 0;
			border: 1px solid #999;
		}

		.my-legend .legend-source {
			font-size: 70%;
			color: #999;
			clear: both;
		}

		.my-legend a {
			color: #777;
		}

		body {
			font-family: "Lato", sans-serif;
			transition: background-color .5s;

		}


		.sidenav {
			/*min-height: 600px;*/
			height: 58% !important;
			width: 300px;
			position: fixed;
			z-index: 1;
			top: 0;
			right: 0;
			background-color: white;
			overflow-x: hidden;
			transition: 0.5s;
			padding-top: 60px;
			border: 5px solid #ddd;
		}

		.sidenav a {
			padding: 8px 8px 8px 32px;
			text-decoration: none;
			display: block;
			transition: 0.3s;
		}

		.sidenav a:hover {
			color: #f1f1f1;
		}

		.sidenav .closebtn {
			/*  position: absolute;
          top: 0;
          right: 0;
          font-size: 25px;
          margin-left: 50px;
          margin-top: -15px;
          color: #383839;
        */

			position: relative;
			margin-right: 10px;
			margin-top: -15px;
			border: 0;
			background: #006400;
			color: white;
			padding: 0px 2px;
			font-size: 1.3rem;
		}

		@media screen and (max-height: 450px) {
			.sidenav {
				padding-top: 15px;
			}

			.sidenav a {
				font-size: 18px;
			}
		}

		.profile-details-class {
			display: none;
			margin-bottom: 5px;
			margin-top: -50px;
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

		.progressbar {
			counter-reset: step;
			margin-top: 10px;
			font-family: arial, sans-serif;
			font-size: 18px;
		}

		.progressbar p {
			margin: 0;
			font-family: arial, sans-serif;
			font-size: 18px;
			font-weight: bold;
		}

		.progressbar li {
			list-style-type: none;
			width: 33%;
			float: left;
			font-size: 12px;
			position: relative;
			text-align: center;
			text-transform: uppercase;
			color: #7d7d7d;
		}

		.progressbar li:before {
			width: 30px;
			height: 30px;
			content: counter(step);
			counter-increment: step;
			float: left;
			line-height: 28px;
			border: 2px solid #7d7d7d;
			display: block;
			text-align: center;
			margin: 0 auto 5px auto;
			border-radius: 50%;
			background-color: var(--my-color-var);
		}

		.progressbar li:after {
			width: 100%;

			content: '';
			position: absolute;
			background-color: #7d7d7d;
			top: 15px;
			left: -50%;
			z-index: -1;
		}


		.progressbar li:first-child:after {
			content: none;
		}


		#invalidInputOverlay {
			position: fixed;
			height: 100%;
			width: 100%;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background: rgba(0, 0, 0, 0.8);
			z-index: 20000;
			display: none;
		}

		#invalidInputPopup {
			max-width: 600px;
			width: 30%;
			max-height: 300px;
			height: 30%;
			padding: 20px;
			position: relative;
			background: #fff;
			margin: 20px auto;
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

		#mySidenav {
			margin-top: 300px;
			/* min-height: 490px; */
		}

		#mySidenav_div {
			overflow-x: hidden;
			text-align: center;
		}


		.alert-box {
			padding: 15px;
			border: 1px solid transparent;
			border-radius: 4px;
			width: 100%;
		}

		.suggestion-box {
			padding: 15px;
			border: 1px solid transparent;
			border-radius: 4px;
			width: 100%;
		}

		.success {
			color: #3c763d;
			background-color: #dff0d8;
			border-color: #d6e9c6;
			display: none;
		}

		.rightClickSuggestion {
			position: sticky;
			color: #3c763d;
			background-color: #dff0d8;
			border-color: #d6e9c6;
			padding: 15px;
			border: 1px solid transparent;
			border-radius: 4px;
		}

		.rightClickDiv {

			overflow: hidden;
			width: 100%;
		}


		/* text right next to top filter button*/
		#topFilter_text {
			float: left;
			border: none;
			height: 30px;
			margin-left: 0px;
			font-size: 20px;
			padding: 5px 12px;
			font-weight: normal;
			width: fit-content;
			font-family: arial, sans-serif;

		}


		@media screen and (max-width: 1024px){
			#topFilter_text {
				float: left;
				border: none;
				height: 30px;
				margin-left: 0px;
				font-size: 17px;
				padding: 5px 10px 10px 0px;
				font-weight: normal;
				width: fit-content;
				font-family: arial, sans-serif;
			}

			#network_div {
				max-height: 100%;
			}

			#network_row{
				max-height: 85%;
			}

		}

		/* Style of top filter button */


		.AddedFilter {
			background-color: #2C9447;
			float: left;
			border: none;
			text-align: right;
			outline: none;
			cursor: pointer;
			font-family: arial, sans-serif;
			font-size: 20px;
			font-weight: bold;
			margin-top: 7px;
			margin-bottom: 7px;
			margin-left: 7px;
			padding: 0px 15px;
			transition: 0.3s;
			height: 25px;


			width: fit-content;
			box-shadow: 1px 1px 2px #999999;
		}

		.AddedFilterClose {
			background-color: #708090;
			float: left;
			border: none;
			font-family: arial, sans-serif;
			font-size: 20px;
			font-weight: bold;
			text-align: right;
			outline: none;
			cursor: pointer;

			margin-top: 7px;
			margin-bottom: 7px;
			padding: 0px 7px;
			height: 25px;
			transition: 0.3s;


			width: fit-content;
			box-shadow: 1px 1px 2px #999999;
		}

		.AddedFilterClose:hover {
			background-color: #606060;

		}

		.closeFilterWindow {
			position: absolute;
			top: 0px;
			right: 0px;
			font-size: 21px;
			width: 24px;
			height: 24px;
			opacity: 0.5;
		}

		.closeFilterWindow:hover {
			opacity: 1;
			background-color: #dcdcdc;
		}

		.CloseButtt {
			display: inline-block;
			padding: 5px;
			border: 1px solid #999;
			height: 39px;
		}

		.TutorWindow_popup {
			display: none;
			position: fixed;
			text-align: center;
			width: 750px;
			border-color: #006400;
			border-width: 3px;
			background-color: white;
			border-style: solid;
			padding: 4px;
			top: 20%;

			z-index: 80;

			border-style: solid;
		}

		.Tutorshowup {
			display: none;
			position: fixed;
			text-align: center;
			width: fit-content;
			top: 0%;
			left: 0%;


			background-color: darkgreen;
			z-index: 80;


		}

		.TutorShowText {
			margin-top: 50px;
			font-weight: bold;
			font-style: italic;
			font-family: arial, sans-serif;
			top: 20%;
			color: white;
			text-shadow: -1px 1px 0 #000, 1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;
			height: 80%;
			background-color: darkgreen;
			font-size: 40px;
			margin: 10px;
		}

		.TutorShowText:before {
			content: "";
			position: absolute;
			right: -30px;
			bottom: 0;
			width: 0;
			height: 0;
			border-left: 30px solid darkgreen;
			border-top: 50px solid transparent;
			border-bottom: 43px solid transparent;

		}

		.TutorWindow_popup2 {
			display: none;
			position: fixed;
			text-align: center;
			top: 30%;

			background-color: white;
			z-index: 80;

			border-style: solid;
			width: 750px;
			border-color: #006400;
			border-width: 3px;
			padding: 4px;


		}


		#network_div {
			height: 75%;
		}

		.NetworkWindow2_popup {
			display: none;
			position: fixed;
			text-align: center;
			top: 0%;
			left: 40%;
			width: 700px;


			padding: 4px;

			background-color: white;
			z-index: 80;

			border: #006400;
			border-style: solid;
		}

		.TutorAddFilter_popup {
			display: none;
			position: absolute;


			top: 10%;
			right: 32%;
			width: 250px;
			height: 240px;
			background-color: white;
			z-index: 85;

			border: #006400;
			border-style: solid;
		}

		.disabledbutton2 {
			pointer-events: none;
			opacity: 1;
		}

		.TutorTextStyle {
			font-weight: 400;
			font-size: 22px;
			height: 25%;
			background: white;
			font-family: arial, sans-serif;
			padding: 0 0 0 0px;
			text-align: center;
			width: auto;
			margin-top: 25px;
			margin-left: 50px;
			margin-right: 50px;
		}

		.TutorTextStyle2 {
			height: 10%;
			font-family: arial, sans-serif;
			font-size: 20px;
			background: white;
			width: fit-content;
			margin: 30px;
		}

		.progress {
			padding: 0px;
			background: rgba(0, 0, 0, 0.25);
			border-radius: 6px;
			-webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25), 0 1px rgba(255, 255, 255, 0.08);
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25), 0 1px rgba(255, 255, 255, 0.08);
		}

		.progress>.progress-bar {

			background-color: #2C9447;
			height: 16px;
			border-radius: 4px;
			background-image: -webkit-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
			background-image: -moz-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
			background-image: -o-linear-gradient(top, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
			background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.05));
			-webkit-transition: 0.4s linear;
			-moz-transition: 0.4s linear;
			-o-transition: 0.4s linear;
			transition: 0.4s linear;
			-webkit-transition-property: width, background-color;
			-moz-transition-property: width, background-color;
			-o-transition-property: width, background-color;
			transition-property: width, background-color;
			-webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.25), inset 0 1px rgba(255, 255, 255, 0.1);
			box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.25), inset 0 1px rgba(255, 255, 255, 0.1);
		}

		.currentChapterText {
			text-align: left;
			font-weight: bold;
			font-family: arial, sans-serif;
			font-size: 22px;
		}

		.AddRelationButton {
			background-color: #2C9447;
			/* Green */
			margin: 10px;
			border: 3px solid black;
			outline: none;
			cursor: pointer;

			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: fit-content;
		}

		.TutorButton {
			background-color: #2C9447;
			/* Green */
			margin: 25px;
			border: 3px solid black;
			outline: none;
			cursor: pointer;

			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: 120px;
		}

		.TutorButton:hover {
			background-color: #137525;

		}

		.BoxTutorButton {
			background-color: #2C9447;
			/* Green */
			margin: 10px;
			border: 3px solid black;
			outline: none;
			cursor: pointer;

			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: 120px;
		}

		.BoxTutorButton:hover {
			background-color: #137525;

		}

		.SkipButton {
			background-color: #2C9447;
			/* Green */
			border: 3px solid black;
			outline: none;

			float: top;
			text-align: center;

			font-size: 20px;
			font-weight: 400;
		}

		.filterWindow_popup {
			display: none;
			position: absolute;
			width: 700px;
			top: 0%;
			right: 30%;
			background-color: white;
			z-index: 80;
			overflow-y: scroll;
			border: #006400;
			border-style: solid;


		}

		.NoResultWindowClass {
			display: none;
			position: fixed;
			text-align: center;
			width: 750px;
			border-color: #006400;
			border-width: 3px;
			background-color: white;
			border-style: solid;
			padding: 4px;
			top: 30%;
			left: 35%;

			z-index: 80;

			border-style: solid;

		}


		.filterWindow_popup::-webkit-scrollbar {
			display: none;
		}

		.filterWindow_popup_left {
			z-index: 100;
			float: left;
			width: 60%;
			margin: 20px;
		}

		.searchTextClass {}

		.buttonGroup {
			background-color: #2C9447;
			/* Green */
			float: left;
			border: 3px solid black;
			outline: none;
			cursor: pointer;
			margin-left: 0;
			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: 120px;
			bottom: 10px;
			right: 20%;
			position: absolute;
		}

		.buttonGroup:hover {
			background-color: #137525;

		}

		.closeButtonFilter2 {

			/* Green */
			float: right;
			border: 1px solid rgb(153, 153, 153);
			outline: none;
			cursor: pointer;
			margin-left: 0;

			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;


			right: 20%;


			width: 20px;
			height: 39px;

		}

		.buttonGroup2 {
			background-color: #2C9447;
			/* Green */
			float: left;
			border: 3px solid black;
			outline: none;
			cursor: pointer;
			margin-left: 0;
			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: 120px;
			bottom: 10px;
			right: 25%;
			position: absolute;


		}

		.TutorButton:hover {
			background-color: #137525;

		}

		.closeButtonFilter {
			position: absolute;
			top: 0px;
			right: 0px;
			font-size: 21px;
			width: 24px;
			height: 24px;
			opacity: 0.5;

		}

		th {
			text-align: left;

		}

		caption {
			text-align: left;
		}

		.clearButtonFilter {
			background-color: gray;
			/* Green */
			float: left;
			border: 3px solid black;
			outline: none;
			cursor: pointer;
			margin-left: 0;
			padding: 10px 12px;
			transition: 0.3s;
			font-size: 15px;
			font-weight: 400;
			width: 120px;
			bottom: 10px;
			right: 5%;
			position: absolute;
		}

		.filterWindow_popup_right {
			bottom: 10px;
		}





		.addArtistSearch2 {

			cursor: pointer;

			/* padding: 5px; */
			font-size: 22px;
			margin-top: 5px;
			margin-left: 15px;
			font-family: arial, sans-serif;
		}

		.noProfileSideNav {
			position: fixed;
			z-index: 1;
			top: 0;
			right: 0;
			background-color: white;
			overflow-x: hidden;
			transition: 0.5s;
			padding-top: 60px;
		}

		/*Filter Demo  */
		.disabledbutton {
			pointer-events: none;
			opacity: 0.3;
			/*background-color: black;*/
		}

		.table_lineage {
			width: 100%;
			margin-left: 8px;
			margin-right: 2px;
			background-color: #eee;
		}

		.mrt10 {
			margin-top: 10px;
		}

		.mrb5 {
			margin-bottom: 10px;
		}

		.biography {
			font-size: 15px;
			margin-bottom: 2px;
			color: #4743f7;
			text-align: center;
		}

		.pic {
			height: 240px;
			overflow: hidden;
			width: 340px;
			margin-bottom: 1px;
		}

		.info {
			text-align: center;
			font-weight: bold;
			/* background-color:#000; */
			margin-bottom: 5px;
		}

		.name {
			text-align: center;
			font-weight: bold;
			margin-bottom: 2px;
		}

		.education {
			text-align: center;
			margin-bottom: 2px;
			margin-left: 2px;
		}

		.genre {
			text-align: center;
			margin-bottom: 2px;
		}

		.tal {
			text-align: left;
		}

		#fade {
			display: none;
			position: fixed;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: black;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		#light {
			display: none;
			position: absolute;
			top: 30%;
			left: 30%;
			max-width: 800px;
			max-height: 480px;
			margin-left: -300px;
			margin-top: -250px;
			border: 2px solid #FFF;
			background: #FFF;
			z-index: 1002;
			overflow: visible;
		}

		#boxclose {
			float: right;
			cursor: pointer;
			color: #fff;
			border: 1px solid #AEAEAE;
			border-radius: 3px;
			background: #222222;
			font-size: 31px;
			font-weight: bold;
			display: inline-block;
			line-height: 0px;
			padding: 11px 3px;
			position: absolute;
			right: 2px;
			top: 2px;
			z-index: 1002;
			opacity: 0.9;
		}

		.boxclose:before {
			content: "×";
		}

		#fade:hover~#boxclose {
			display: none;
		}

		.test:hover~.test2 {
			display: none;
		}


		#navbar {
			z-index: 9999;
		}

		button:hover {
			color: #fff;
		}

		.topFilterClass {
			border-radius: 7px;
		}
	</style>

	<script>
		// bind progress bar functions for chapter I and III.
		// chapter II progress bar function is in tutorial.js. in addProgressBarButtons(popup);
		$('li.completetwo').click(function() {


			setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
			closeAllWindows2();
			clearTimeout(timeoutFunc);
			document.getElementById('search').style.backgroundColor = "#2C9447";
			lineage_network.vis_net.setOptions({
				interaction: {
					dragView: true,
					dragNodes: true,
				}
			});
			$(".custom-menu").hide();
			var search2 = document.getElementById("search");
			search2.onclick = function() {};
			clearSearchText();
			document.getElementById("searchbox").value = "Melanie Aceto";
			$('#search').click();
			clearSearchText();
			window.scrollTo(0, 190);
			var tutorshow = document.getElementById("TutorShowUp");
			tutorshow.style.display = "block";
			tutorshow.style.zIndex = "110";
			networkTutorial();
		});
		$('li.completeone').click(function() {
			$(".custom-menu").hide();
			setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
			closeAllWindows2();
			clearTimeout(timeoutFunc);
			document.getElementById('search').style.backgroundColor = "#2C9447";
			lineage_network.vis_net.setOptions({
				interaction: {
					dragView: true,
					dragNodes: true,
				}
			});

			var tutorshow = document.getElementById("TutorShowUp");
			tutorshow.style.display = "block";
			tutorshow.style.zIndex = "110";
			searchTutorial();
		});

		$('li.completethree').click(function() {
			$(".custom-menu").hide();
			setCookie('skipped_tutorial_all', 'true', cookies_exp_hours);
			closeAllWindows2();
			clearTimeout(timeoutFunc);
			document.getElementById('search').style.backgroundColor = "#2C9447";
			lineage_network.vis_net.setOptions({
				interaction: {
					dragView: true,
					dragNodes: true,
				}
			});

			var search2 = document.getElementById("search");
			search2.onclick = function() {};



			stage.close();
			var tutorshow = document.getElementById("TutorShowUp");
			tutorshow.style.display = "block";
			tutorshow.style.zIndex = "110";
			filterTutorial();

		});
	</script>


	<script>
		$(document).keyup(function(e) {
			if ($(".ui-autocomplete-input:focus") && (e.keyCode === 13)) {
				alert('Please Press "Search" or "Filter" to Continue')
			}
		});
	</script>


	<!-- <script>
		function closeNav() {
			$("#mySidenav").hide();
		}
	</script> -->


	<script>
		dragElement(document.getElementById("TutorWindow"));
		dragElement(document.getElementById("NetworkTutorWindow"));
		dragElement(document.getElementById("NetworkTutorWindow1.5"));
		dragElement(document.getElementById("NetworkTutorWindow2.0"));
		dragElement(document.getElementById("NetworkTutorWindow2"));
		dragElement(document.getElementById("FirstTimeTutorialWindow"));
		dragElement(document.getElementById("FilterTutorWindow"));

		dragElement(document.getElementById("FiltorCongrat"));
		dragElement(document.getElementById("CompleteFirstChapter"));
		dragElement(document.getElementById("CompleteSecondChapter"));

		function dragElement(elmnt) {
			var pos1 = 0,
				pos2 = 0,
				pos3 = 0,
				pos4 = 0;

			// otherwise, move the DIV from anywhere inside the DIV:
			elmnt.onmousedown = dragMouseDown;


			function dragMouseDown(e) {
				e = e || window.event;
				e.preventDefault();
				// get the mouse cursor position at startup:
				pos3 = e.clientX;
				pos4 = e.clientY;
				document.onmouseup = closeDragElement;
				// call a function whenever the cursor moves:
				document.onmousemove = elementDrag;
			}

			function elementDrag(e) {
				e = e || window.event;
				e.preventDefault();
				// calculate the new cursor position:
				pos1 = pos3 - e.clientX;
				pos2 = pos4 - e.clientY;
				pos3 = e.clientX;
				pos4 = e.clientY;
				// set the element's new position:
				elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
				elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
			}

			function closeDragElement() {
				// stop moving when mouse button is released:
				document.onmouseup = null;
				document.onmousemove = null;
			}
		}
	</script>


	<script>
		var coll = document.getElementsByClassName("foldAllSearch");
		var i;

		for (i = 0; i < coll.length; i++) {
			coll[i].addEventListener("click", function() {
				this.classList.toggle("actives");
				var content = this.nextElementSibling;
				if (content.style.display === "block") {
					$("#addallsearchtype").slideUp('');
					// $('#networkTab').css({marginTop: '-=300px'});
				} else {
					$("#addallsearchtype").slideDown('');
					content.style.display = "block";
					// $('#networkTab').css({marginTop: '+=300px'});
				}
			});
		}
	</script>


	<script>
		//THis function popup filter window
		function NoresultClose() {
			document.getElementById("NoResultWindow").style.display = "none";
		}

		function filterPopup() {

			document.getElementById("FilterWindow").style.display = "block";
			$("#filter_div,.topFilterClass,#relation_check,.searchTextClass, #network_container, #small-6 column, #topFilter, #topFilter_text, #navbar, .small-7, .small-5, .footer").addClass("disabledbutton");
		}
	</script>


	<script>
		window.document.onkeydown = function(e) {
			if (!e) {
				e = event;
			}
			if (e.keyCode == 27) {
				lightbox_close();
			}
		}

		function lightbox_open() {
			var lightBoxVideo = document.getElementById("VisaChipCardVideo");
			window.scrollTo(0, 0);
			document.getElementById('light').style.display = 'block';
			document.getElementById('fade').style.display = 'block';
			lightBoxVideo.play();
		}

		function lightbox_close() {
			var lightBoxVideo = document.getElementById("VisaChipCardVideo");
			document.getElementById('light').style.display = 'none';
			document.getElementById('fade').style.display = 'none';
			lightBoxVideo.pause();
		}
	</script>


	<script>
		$('#danced').change(function() {
			if (this.checked) {
				$("#danced_options").fadeIn('slow');
			} else {
				$("#danced_options").fadeOut('slow');
			}
		});

		// set the relation type checkboxes
		$('#studied').prop('checked', false);
		$('#danced').prop('checked', false);
		$('#collaborated').prop('checked', false);
		$('#influenced').prop('checked', false);
	</script>
















	<script>
		// Structure of this file:
		// the initial variables and the functions formatTabLabels and updatePage relate to the menu functions (switching bettween all relationships, studied under, collaborated with, danced in the work of, influenced by)
		//both of these functions' parameters refer to which filtering menu function we are on
		// draw() encompasses all of the possible functionality on load of the screen load
		// the beginning of the file is all global variables
		// the next section is what should be considered the main function, which will call all other functions
		// the last part of the file are the helper functions actually called




		var myNetwork = null;
		const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
		var lineage_network = undefined;
		var selected = -1;
		var currentPageType = "Full Network";
		var originalText = "";
		var countMap = new Map();
		var center = {
			posx: 0,
			posy: 0
		};
		countMap.set("Artist", 1);
		countMap.set("Genre", 0);
		countMap.set("Genre_popup", 0);
		countMap.set("ArtistType", 0);
		countMap.set("Country", 0);
		countMap.set("State", 0);
		countMap.set("City", 0);
		countMap.set("Ethnicity", 0);
		countMap.set("Gender", 0);
		countMap.set("Genre_popup", 0);
		countMap.set("ArtistType_popup", 0);
		countMap.set("Country_popup", 0);
		countMap.set("State_popup", 0);
		countMap.set("City_popup", 0);
		var autocompleteTextboxCounter = new Map();
		var all_nodes = {
			"Full Network": {
				nodes: [],
				edges: [],
				associatedNodeIDs: new Set(),
				associatedEdgeIDs: new Set()
			},
			"Studied Under": {
				nodes: [],
				edges: [],
				associatedNodeIDs: new Set(),
				associatedEdgeIDs: new Set()
			},
			"Collaborated With": {
				nodes: [],
				edges: [],
				associatedNodeIDs: new Set(),
				associatedEdgeIDs: new Set()
			},
			"Danced in the Work of": {
				nodes: [],
				edges: [],
				associatedNodeIDs: new Set(),
				associatedEdgeIDs: new Set()
			},
			"Influenced By": {
				nodes: [],
				edges: [],
				associatedNodeIDs: new Set(),
				associatedEdgeIDs: new Set()
			}
		}
		var tab_labels = {
			"Studied Under": "studied_with_tab",
			"Collaborated With": "collaborated_with_tab",
			"Danced in the Work of": "danced_for_tab",
			"Influenced By": "influenced_by_tab"
		}

		// makes our current tab appear active
		function initRelationTab() {
			var tab_labels = {
				"study_rel": "Studied Under",
				"coll_rel": "Collaborated With",
				"dance_rel": "Danced in the Work of",
				"infl_rel": "Influenced By"
			}
			var rel = ["Studied Under", "Collaborated With", "Danced in the Work of", "Influenced By"];
			$(".rel_box").each(function() {
				this.checked = true;
			});
			$(".rel_box").change(function() {
				if (this.checked) {
					rel.push(tab_labels[this.id]);
				} else {
					const index = rel.indexOf(tab_labels[this.id]);
					if (index > -1) {
						rel.splice(index, 1);
					}
				}
				lineage_network.applyEdgeFilters({
					artist_relation: rel
				});

			});
		}

		function tConvert(time) {
			time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
			if (time.length > 1) {
				time = time.slice(1);
				time[5] = +time[0] < 12 ? ' AM' : ' PM';
				time[0] = +time[0] % 12 || 12;
			}
			return time.join('');
		}


		function artistClick(name) {
			$("#mySidenav").hide();
			$("#network_display_div").show();
			$("#artistlist_display_div").hide();
			$("#familytree_display_div").hide();
			$("#network_div").css({
				"height": "75%"
			});
			document.getElementById("searchbox").value = name;
			$('#search').click();
		}


		// this filters the network to only show the relevant relationships
		function loadTableEvents(maindta, node) {
			let html = "";
			if (maindta['result'] == 0) {

				$('#EventPopUp_h1').html("There is no event for " + node["artist_first_name"]).show();

			} else {
				$('#EventPopUp_h1').html("Events Of " + node["artist_first_name"]).show();

				maindta['result'].forEach(item => {
					var eventStartDate = new Date(item.event_startdate);
					html += "<tr>";

					html += "<td style='text-align:center'>" + item.event_name + " </td>";
					html += "<td style='text-align:center'>" + item.event_location + "</td>";
					html += "<td style='text-align:center'>" + days[eventStartDate.getUTCDay()] + ", " + monthNames[eventStartDate.getUTCMonth()] + " " + eventStartDate.getUTCDate() + ", " + eventStartDate.getUTCFullYear() + "</td>";
					html += "<td style='text-align:center'>" + tConvert(item.event_time) + "</td>";
					html += "</tr>";
				});
			}
			$("#artist_events").html(html);

		}

		
		function loadArtistEvent(node) {
			$.ajax({
				type: "POST",
				url: "eventcontroller.php",
				data: JSON.stringify({
					"action": "getEventsByEmail",
					"useremailid": node["artist_email_address"]
				}),
				success: function(res) {
					loadTableEvents(res, node);
					$('#eventTable').show();
					$('#spin_loading_event').hide();
				},
				error: function(reponse) {
					console.log(reponse);
				}
			});
		}

		var im_x = 0;
		var im_y = 0;

		var mouse = {
			x: 0,
			y: 0
		};
		document.addEventListener('mousemove', function(e) {
			mouse.x = e.clientX;
			mouse.y = e.clientY;
			var mainImage = document.querySelector("#mainImage");
			if (mouse.x > im_x + 10 || mouse.x < im_x - 10 || mouse.y > im_y + 10 || mouse.y < im_y - 10) {
				mainImage.style.display = "none";
				mainImage.src = "";
			}
		});


		var changeChosenNodeSize = function(values, id, selected, hovering) {

			if (lineage_network.nodes.get(id)["artist_photo_path"]) {
				var mainImage = document.querySelector("#mainImage");
				mainImage.style.display = "block";
				mainImage.style.left = mouse.x - 30;
				mainImage.style.top = mouse.y + 10;
				im_x = mouse.x;
				im_y = mouse.y;
				mainImage.src = lineage_network.nodes.get(id)["artist_photo_path"];
			}

		};




		function initNetwork() {


			const default_options = {
				autoResize: true,
				height: '150%',
				width: '150%',
				// configure: {},    // defined in the configure module.
				edges: {
					smooth: {
						enabled: true, // allows curving of edges between nodes
						type: "dynamic", // curvature of edges is associated with physics of the network when set to dynamic
					},
					color: {
						color: "#C0C0C0",
						highlight: '#275f9c',
						hover: '#275f9c'
					},
					font: {
						align: "middle",
						size: 0
					}
				}, // defined in the edges module.
				nodes: {
					borderWidth: 5, // thickness of border around nodes
					color: {
						hover: {
							background: '#89082f', // background color of node on hover
							border: '#000000' // border color of node on hover
						}
					},
					size: 20, // size of node
					shapeProperties: {
						useBorderWithImage: true
					},
					shape: "circularImage",
					chosen: {
						node: changeChosenNodeSize
					},

				},

				interaction: {

					hover: true,
					tooltipDelay: 100
				}, // defined in the interaction module.
				// manipulation: {}, // defined in the manipulation module.
				physics: {
					// stabilization: false,
					barnesHut: {
						gravitationalConstant: -15000, // setting repulsion (negative value) between the nodes
						centralGravity: 0.9,
						avoidOverlap: 0.5, // pulls entire network to the center
						springLength: 95,
						springConstant: 0.04,
						// damping: 0.01
					},
					maxVelocity: 30,

					stabilization: {

						iterations: 100,
						updateInterval: 5,
					}
				}, // defined in the physics module.
			};
			lineage_network = new LineageNetwork("my_network", default_options);
			originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
			$('#search_text').html(originalText);

			drawDefaultNetwork(function() {
				return undefined;
			});


		}


		function initSearchMenu() {

			// normal global variables to help functions run
			var search_names = ["-1", "-1", "-1", "-1"];
			// "504" is melanie aceto
			var default_ids = ["534", "209", "102", "504"]
			var current_id = "-1"
			var search_filters = {
				"genre": "",
				"artistType": "",
				"country": "",
				"state": "",
				"city": "",
				"university": "",
				"degree": "",
				"major": "",
				"ethnicity": "",
				"gender": "",
				"livingStatus": ""
			}
			var autocompleteLoadedData = {
				'artist_name': [],
				'university': [],
				'city_names': [],
				'state_names': [],
				'country_names': [],
				'major_names': [],
				'artist_genres': [],
				'genres': [],
				'degree_names': [],
				'ethnicity_names': []
			}
			var autocompleteCategoryToAction = {
				'artist_name': ["getArtistNames", "artistcontroller.php"],
				'university': ["getUniversityNames", "artistcontroller.php"],
				'city_names': ["getCityNames", "artistcontroller.php"],
				'state_names': ["getStateNames", "artistcontroller.php"],
				'country_names': ["getCountryNames", "artistcontroller.php"],
				'major_names': ["getMajor", "artistcontroller.php"],
				'genres': ["getGenres", "genrecontroller.php"],
				'artist_genres': ["getArtistGenre", "artistcontroller.php"],
				'degree_names': ["getDegree", "artistcontroller.php"],
				'ethnicity_names': ["getEthnicity", "artistcontroller.php"]
			}
			var idToName = {}
			var savedNetwork = []
			var genreIdToName = {}
			var genreNameToID = {}


			//format variables to make network look a certain way
			var edge_colors_dict = {
				default_color: "#C0C0C0",
				"Studied Under": "#1A3263",
				"Collaborated With": "#1A3263",
				"Danced in the Work of": "#1A3263",
				"Influenced By": "#1A3263"
			};
			var isArtistNodeColor = "#1A3263"
			var notArtistNodeColor = "#FFFFFF"
			//var notArtistNodeColor = "#6E91D8"
			var default_shape = 'circularImage'
			var selected_shape_image = 'image'
			var selected_shape = 'square'
			document.getElementById("loader_circles_div").style.display = "none";
			var default_nodes = [];
			var default_edges = [];
			var nodeIDs_visible = [];
			var edgeIDs_visible = [];


			//get attributes of who we could possibly search for
			loadAutocompleteData('artist_name')
			loadAutocompleteData('university')
			loadAutocompleteData('city_names')
			loadAutocompleteData('state_names')
			loadAutocompleteData('country_names')
			loadAutocompleteData('major_names')
			loadAutocompleteData('artist_genres')
			loadAutocompleteData('degree_names')
			loadAutocompleteData('ethnicity_names')
			loadAutocompleteData('genres')

			//autocomplete calls for default artist searchbox
			autocomplete("#searchbox", 0)

			// Activated when someone starts to type in chat, search() function executed when artist selected
			// autocomplete()
			search_button = document.getElementById('search');
			search_button.addEventListener('click', (function(e) {
				submitSearch()
			}))
			//zeping
			search_button_popup = document.getElementById('search_popup');
			search_button_popup.addEventListener('click', (function(e) {
				FilterMenuEndEvent().then(endFilter);
			}))

			clear = document.getElementById('clear');
			clear.addEventListener('click', (function(e) {
				clearSearchbox()
			}));

			filterClo = document.getElementById('filterWindClose');
			filterClo.addEventListener('click', (function(e) {
				filterClose()
			}));

			search_all = document.getElementById('searchAll');
			search_all.addEventListener('click', (function(e) {
				searchEntireNet()
			}))

			list_all_artists = document.getElementById('listallartists');
			list_all_artists.addEventListener('click', (function(e) {
				searchAllArtists()
			}))

			family_tree = document.getElementById('familytree');
			family_tree.addEventListener('click', (function(e) {
				searchFamilyTree()
			}))

			closefamily = document.getElementById('close_family');
			closefamily.addEventListener('click', (function(e) {
				closeFamilyTree()
			}))

			clear_popup = document.getElementById("clear_popup");
			clear_popup.addEventListener("click", (function(e) {
				clearFilterMenu()
			}));

			addArtistSearch = document.getElementById('addArtistSearch');
			addArtistSearch.addEventListener('click', (function(e) {
				addSearchBox('Artist')
			}));

			addGenreSearch = document.getElementById('addGenreSearch');
			addGenreSearch.addEventListener('click', (function(e) {
				addSearchBox('Genre')
			}));

			addGenreSearch_popup = document.getElementById('addGenre_popupSearch');
			addGenreSearch_popup.addEventListener('click', (function(e) {
				addSearchBox('Genre_popup')
			}));

			addArtistTypeSearch = document.getElementById('addArtistTypeSearch');
			addArtistTypeSearch.addEventListener('click', (function(e) {
				addSearchBox('ArtistType')
			}));



			addArtistTypeSearch_popup = document.getElementById('addArtistType_popupSearch');
			addArtistTypeSearch_popup.addEventListener('click', (function(e) {
				addSearchBox('ArtistType_popup')
			}));

			addCountrySearch = document.getElementById('addCountrySearch');
			addCountrySearch.addEventListener('click', (function(e) {
				addSearchBox('Country')
			}));

			addCountrySearch_popup = document.getElementById('addCountry_popupSearch');
			addCountrySearch_popup.addEventListener('click', (function(e) {
				addSearchBox('Country_popup')
			}));

			addStateSearch = document.getElementById('addStateSearch');
			addStateSearch.addEventListener('click', (function(e) {
				addSearchBox('State')
			}));

			addStateSearch_popup = document.getElementById('addState_popupSearch');
			addStateSearch_popup.addEventListener('click', (function(e) {
				addSearchBox('State_popup')
			}));

			addCitySearch = document.getElementById('addCitySearch');
			addCitySearch.addEventListener('click', (function(e) {
				addSearchBox('City')
			}));

			addCitySearch_popup = document.getElementById('addCity_popupSearch');
			addCitySearch_popup.addEventListener('click', (function(e) {
				addSearchBox('City_popup')
			}));

			addRelationship_popup = document.getElementById('addRelatioshipSearch_popup');
			addRelationship_popup.addEventListener('click', (function(e) {
				openRelationship('addRelationship_popup', 'addRelatioshipSearch_popup', 'study_rel', "coll_rel", "dance_rel", "infl_rel")
			}))
			closeRelationship_popup = document.getElementById('closeRelationship_popup');
			closeRelationship_popup.addEventListener('click', (function(e) {
				closeRelationship('addRelationship_popup', 'addRelatioshipSearch_popup', 'study_rel', "coll_rel", "dance_rel", "infl_rel")
			}))
			addEthnicitySearch = document.getElementById('addEthnicitySearch');
			addEthnicitySearch.addEventListener('click', (function(e) {
				addSearchBox('Ethnicity')
			}))

			addGenderSearch = document.getElementById('addGenderSearch');
			addGenderSearch.addEventListener('click', (function(e) {
				addSearchBox('Gender')
			}))


			

			function addSearchBox(frontID) {

				countMap.set(frontID, countMap.get(frontID) + 1);


				if (countMap.get(frontID) >= 1) {
					if (frontID.includes('_')) {
						if (frontID.includes('ArtistType')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Artist Type' + ' +';
						} else if (frontID.includes('Country')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Country' + ' +';
						} else {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + frontID.substring(0, frontID.indexOf('_')) + ' +';
						}
					} else {
						if (frontID.includes('ArtistType')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Artist Type' + ' +';
						} else if (frontID.includes('Country')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Country' + ' +';
						} else {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + frontID + ' +';
						}
					}
				}
				var currentClassName = 'add' + frontID;
				var currentNum = Math.floor(document.getElementsByClassName(currentClassName).length / 2) + 1;
				var hidden = false;
				if (frontID != 'Artist' && document.getElementById(currentClassName + 'Label').style.display == 'none') {
					document.getElementById(currentClassName + 'Label').style.display = 'inline';
				}
				if (currentNum == 1) {
					createBox(currentNum, frontID, currentClassName);
				} else {
					for (var item of document.getElementsByClassName(currentClassName)) {
						if (item.style.display == 'none') {
							item.style.display = 'block';
							hidden = true;
							break;
						}
					}
					if (!hidden) {
						createBox(currentNum, frontID, currentClassName);
					}
				}
			}


			/**
			 * This function is called by "addSearchBox" sunction, it creates search boxes according to frontID, currentNum, and currentClassName.
			 * 
			 * @param  {string} currentNum Number in search boxes ID
			 * @param  {string} frontID	Common ID of search boxes.
			 * @param  {string} currentClassName Class name of searchboxes
			 */
			function createBox(currentNum, frontID, currentClassName) {
				//alert(document.getElementsByClassName(currentClassName)[0].id);
				//Note that label is the first element in currentArr
				var currentDivID = frontID + (currentNum).toString();
				var currentDiv = document.createElement('div');
				currentDiv.style = "display: block;";
				currentDiv.id = currentDivID;
				currentDiv.className = currentClassName;
				document.getElementById('add' + frontID).appendChild(currentDiv);

				//Create search box and close buuton. and set id for each of them dynamically
				var currentCloseID = 'close' + frontID + 'Search' + (currentNum).toString();
				var currentClose = document.createElement('button');
				currentClose.style = 'display:inline-block; padding: 4px; border: 1px solid #999; height:39px; margin-left:3.5px;';
				currentClose.textContent = "X";
				currentClose.id = currentCloseID;

				//create select box for gender and ethnicity
				if (frontID === 'Gender' || frontID == 'Ethnicity' || frontID.includes('ArtistType')) {
					var currentSelectID = frontID.charAt(0).toLowerCase() + frontID.slice(1) + 'Searchbox' + (currentNum).toString();
					var currentSelect = document.createElement('select');
					currentSelect.id = currentSelectID;
					currentSelect.className = currentClassName;
					var option = document.createElement('option');
					option.text = '';
					currentSelect.style = 'display:inline-block; width:83%';
					currentSelect.appendChild(option);
					if (frontID == 'Ethnicity') {
						autocompleteLoadedData['ethnicity_names'].sort(function(a, b) {
							var label_a = a.label.toUpperCase();
							var label_b = b.label.toUpperCase();
							if (label_a < label_b) {
								return -1;
							}
							if (label_a > label_b) {
								return 1;
							}
						});
						for (var data of autocompleteLoadedData['ethnicity_names']) {
							option = document.createElement('option');
							option.text = data.label;
							if (option.text.length > 0) {
								currentSelect.appendChild(option);
							}
						}
					} else if (frontID.includes('ArtistType')) {
						autocompleteLoadedData['artist_genres'].sort(function(a, b) {
							var label_a = a.label.toUpperCase();
							var label_b = b.label.toUpperCase();
							if (label_a < label_b) {
								return -1;
							}
							if (label_a > label_b) {
								return 1;
							}
						});
						for (var data of autocompleteLoadedData['artist_genres']) {
							option = document.createElement('option');
							option.text = data.label;
							if (option.text.length > 0) {
								currentSelect.appendChild(option);
							}
						}
					} else {
						// select box for gender
						option = document.createElement('option');
						option.text = 'Female';
						currentSelect.appendChild(option);
						option = document.createElement('option');
						option.text = 'Male';
						currentSelect.appendChild(option);
						option = document.createElement('option');
						option.text = 'Non-binary Gender';
						currentSelect.appendChild(option);
						option = document.createElement('option');
						option.text = 'Prefer not to answer';
						currentSelect.appendChild(option);
					}

					currentClose.addEventListener('click', (function(e) {
						closeSearchBox(currentDiv, currentSelect, frontID)
					}));
					document.getElementById(currentDivID).appendChild(currentSelect);
					document.getElementById(currentDivID).appendChild(currentClose);
					document.getElementById('add' + frontID).style.display = 'block';
				} else {
					//create search box for other criteria
					var currentBoxID = frontID.charAt(0).toLowerCase() + frontID.slice(1) + 'Searchbox' + (currentNum).toString();
					//var currentCloseID = 'close' + frontID + 'Search' + (currentNum).toString();
					var currentBox = document.createElement('input');
					//var currentClose = document.createElement('button');
					currentBox.id = currentBoxID;
					currentBox.type = 'search';
					currentBox.style = 'display:inline-block; width:85%';
					if (frontID.includes('Country')) {
						currentBox.placeholder = 'Enter a territory';
					} else if (frontID.includes('_')) {
						firstLetter = frontID[0].toLowerCase();
						var subID = frontID.substring(0, frontID.indexOf('_'));
						if (firstLetter == "a" || firstLetter == "e" || firstLetter == "i" || firstLetter == "o" || firstLetter == "u") {
							currentBox.placeholder = 'Enter an ' + subID.toLowerCase();
						} else {
							currentBox.placeholder = 'Enter a ' + subID.toLowerCase();
						}
					} else {
						firstLetter = frontID[0].toLowerCase();
						if (firstLetter == "a" || firstLetter == "e" || firstLetter == "i" || firstLetter == "o" || firstLetter == "u") {
							currentBox.placeholder = 'Enter an ' + frontID.toLowerCase();
						} else {
							currentBox.placeholder = 'Enter a ' + frontID.toLowerCase();
						}
					}
					currentBox.className = currentClassName;
					currentClose.addEventListener('click', (function(e) {
						closeSearchBox(currentDiv, currentBox, frontID)
					}));

					document.getElementById(currentDivID).appendChild(currentBox);
					document.getElementById(currentDivID).appendChild(currentClose);
					//alert(document.getElementById('addArtist').display == 'none');
					autocompleteForSearchBox(frontID, currentBoxID, currentNum)
					document.getElementById('add' + frontID).style.display = 'block';
				}

			}

			/**
			 * This function is called by "createBox" function, it assigns auto complete function to each boxes.
			 * @param  {string} frontID Common ID of search boxes.
			 * @param  {sting} currentBoxID Search Box ID.
			 * @param  {string} currentNum Number in ID.
			 */
			function autocompleteForSearchBox(frontID, currentBoxID, currentNum) {
				switch (frontID) {
					case ('Artist'):
						autocomplete('#' + currentBoxID, currentNum);
						break;
					case ('Genre'):
					case ('Genre_popup'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['genres']);
						break;
					case ('ArtistType'):
					case ('ArtistType_popup'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['artist_genres']);
						break;
					case ('Country'):
					case ('Country_popup'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['country_names']);
						break;
					case ('City'):
					case ('City_popup'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['city_names']);
						break;
					case ('State'):
					case ('State_popup'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['state_names']);
						break;
					case ('Ethnicity'):
						autocompleteAttribute('#' + currentBoxID, autocompleteLoadedData['ethnicity_names']);
						break;
				}
			}

			function openRelationship(id, menuOpener, inputID, inputID1, inputID2, inputID3) {
				var div = document.getElementById(id)
				if (window.getComputedStyle(div).display === "none") {
					$(`#${id}`).show()
					$(`#${menuOpener}`).hide()
					var input0 = document.getElementById(inputID)
					input0.checked = true;
					var input1 = document.getElementById(inputID1)
					input1.checked = true;
					var input2 = document.getElementById(inputID2)
					input2.checked = true;
					var input3 = document.getElementById(inputID3)
					input3.checked = true;
				}

			}


			function closeRelationship(id, menuOpener, inputID, inputID1, inputID2, inputID3) {
				var div = document.getElementById(id)
				var input = document.getElementById(inputID)
				var input1 = document.getElementById(inputID1)
				var input2 = document.getElementById(inputID2)
				var input3 = document.getElementById(inputID3)
				// tells us if it is hidden currently
				if (window.getComputedStyle(div).display !== "none") {
					$(`#${id}`).hide()
					$(`#${menuOpener}`).show()
					input.checked = true;
					input1.checked = true;
					input2.checked = true;
					input3.checked = true;
				}
			}

			/**
			 * Function for "X" button besides seach boxes. It will clear and hide thisBox, and change "add more..." button's  name if necessary.
			 * 
			 * @param  {object} thisDiv The Div which contains closing search boxes.
			 * @param  {object} thisBox The search boxes that being closed.	
			 * @param  {string} frontID The common ID of closing search box.
			 */
			function closeSearchBox(thisDiv, thisBox, frontID) {
				countMap.set(frontID, countMap.get(frontID) - 1);
				if (countMap.get(frontID) < 1) {
					if (frontID.includes('_')) {
						if (frontID.includes('ArtistType')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Filter by ' + 'Artist Type' + ' +';
						} else if (frontID.includes('Country')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Filter by ' + 'Country' + ' +';
						} else {
							document.getElementById('add' + frontID + 'Search').textContent = 'Filter By ' + frontID.substring(0, frontID.indexOf('_')) + ' +';
						}
					} else {
						if (frontID == 'ArtistType') {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add ' + 'Artist Type' + ' to Search +';
						} else if (frontID.includes('Country')) {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add ' + 'Country' + ' to Search +';
						} else {
							document.getElementById('add' + frontID + 'Search').textContent = 'Add ' + frontID + ' to Search +';
						}
					}
				}
				if (thisDiv.id.includes('Artist')) {
					var index = search_names.indexOf(thisBox.value);
					if (index > -1) {
						search_names.splice(index, 1);
					}
				}
				thisBox.value = '';
				thisDiv.style.display = 'none';
				var allClose = true;
				if (!thisDiv.id.includes('Artist') || thisDiv.id.length > 8) {
					for (var item of document.getElementsByClassName(thisDiv.className)) {
						if (!item.id.includes('add') && item.id.includes(thisDiv.className.substring(3, thisDiv.className.length - 1))) {
							if (item.style.display != 'none') {
								allClose = false
							}
						}
					}
					if (allClose) {
						document.getElementById(thisDiv.className + 'Label').style.display = 'none';
					}
				}

			}


			// called on load of the page when user is logged in
			function userSpecificHomeScreenLoad() {
				$.ajax({
					type: "POST",
					url: 'artistcontroller.php',
					data: JSON.stringify({
						"action": "getCompleteArtistProfile"
					}),
					success: function(response) {
						response = JSON.stringify(response);
						json_object = $.parseJSON(response);
						nodesFetched = json_object.artist_profile;
						//get the artist who is current logged in
						for (var node of nodesFetched)
							if (node.artist_email_address === email) current_id = node.artist_profile_id
						if (nodesFetched) getNetworkData(nodesFetched)
						myNetwork = makeNetWork(default_nodes, default_edges, options)
						// set functions for what happens when network is acted upon
						onNetworkChange(myNetwork);
						//centers the node to the top
						myNetwork.body.data.nodes.update({
							id: current_id,
							x: 0,
							y: 0
						});
						myNetwork.redraw();
					},
					error: function(xhr, status, error) {
						defaultHomeScreenLoad()
						console.log("Error");
						console.log(xhr.responseText);
					}
				});
			}

			// the output of the desired artist attributes look like this: [{attribute: "user data"}, {attribute: "user data"},...{attribute: "user data"}]
			function organizeSQLOutcome(sqlOutput) {
				if (Object.keys(sqlOutput[0])[0] == "genre_id")
					for (var genre of sqlOutput) {
						genreIdToName[genre["genre_id"]] = genre["genre_name"]
						genreNameToID[genre["genre_name"]] = genre["genre_id"]
					}
				var data = sqlOutput
				if (Object.keys(data[0])[0] == "artist_genre") data = organizeArtistTypeData(sqlOutput)
				var organizedOutput = [];
				var counter = 1;
				var listedByID = false;
				// if the attribute happens to already include an id, we do not want to overwrite it
				for (var key of Object.keys(data[0]))
					if (key.includes("id") && !key.includes("residence")) listedByID = true;
				// if it does not already have an id
				if (!listedByID) {
					for (var obj of data) {
						var row = {};
						for (var key of Object.keys(obj)) {
							row["id"] = counter; //writing in our own id
							row["label"] = obj[key];
						}
						organizedOutput.push(row);
						counter++;
					}
				}
				return organizedOutput;
			}

			// this particular artist attribute was not organized in the the data input
			function organizeArtistTypeData(sqlOutput) {
				var brokenUpGenres = new Set()
				for (var input of sqlOutput) {
					for (var genre of input[Object.keys(input)[0]].split(",")) {
						var artist_genre = genre
						if (genre.includes("_")) artist_genre = genre.split("_")[0] + " " + genre.split("_")[1]
						if (genre !== "") brokenUpGenres.add(artist_genre)
					}
				}
				var sqlFormat = []
				for (var artist_genre of brokenUpGenres) sqlFormat.push({
					"artist_genre": artist_genre
				})
				return sqlFormat
			}

			// all of the data needs to be loaded and ready to access in the different searches box
			function loadAutocompleteData(category) {
				fetch(autocompleteCategoryToAction[category][1], {
						method: "post",
						body: JSON.stringify({
							action: autocompleteCategoryToAction[category][0]
						})
					})
					// promise version of accessing sql: the ajax version did not work (I am not sure why)
					.then(res => res.json())
					.then(result => {
							// reorganize the output of the promise, so it looks like that of the names/genres output
							var organizedData = organizeSQLOutcome(result[category]);
							// there are preorgganized versions of the data for names and genres, so we want to keep that structure
							if (category == "artist_name") organizedData = result['artist_name']
							if (category == "genres") organizedData = result["genres"]
							for (let i = 0; i < organizedData.length; i++) {
								let data = organizedData[i];
								if (category == "artist_name") {
									fullName = data.artist_first_name + " " + data.artist_last_name;
									var imageURL = data.artist_photo_path;
									var is_artist = false;
									autocompleteLoadedData[category].push({
										label: fullName,
										node_id: data.artist_profile_id,
										image: imageURL,
										is_artist: is_artist
									});
									//this allows for easy access of names later on
									idToName[data.artist_profile_id] = fullName
								} else if (category == "genres") autocompleteLoadedData[category].push({
									label: data.genre_name,
									id: data.genre_id
								});
								else autocompleteLoadedData[category].push({
									label: data.label,
									id: data.id
								});
							}
						},
						error => {
							console.log("load auto-complete throws an error!");
						}
					);
			}

			//TODO : sort out the searches here
			//what displays the suggestions for what to search (for artists)
			function autocomplete(searchbox, id_num) {
				console.log("autocomplete");
				// code for the autocomplete searchbox


				$(searchbox).autocomplete({
					minLength: 1, // minimum of 1 character to be entered before suggesting artist names
					source: autocompleteLoadedData["artist_name"],
					select: function(event, ui) {
						if (isNaN(this.value)) {
							// the following commented out code was used in the original writing of this function, but it does not seem useful
							//$searchbox.val(ui.item.label); // display the selected text
							//  $("#searchTextValue").val(ui.item.label);
							//  $("#searchbox_node_id").val(ui.item.node_id); // save selected node_id to hidden input
							default_ids[id_num] = ui.item.node_id
							if (!search_names.includes(ui.item.label) && typeof ui.item.label !== "undefined") {
								search_names[id_num] = ui.item.label
							}
							//zeros out the network data structures

							//clearNonArtistSearchboxes()


						}
					}
				})
				// method to make images appear along with names in autocomplete THIS IS COPY PASTEED FROM js/lineage_network.js
				$(searchbox).data("ui-autocomplete")._renderItem = function(ul, item) {
					var $li = $('<li>');
					var $img = $('<img style="width:32px;height:32px;">');
					var imgURL = item.image
					if (item.image === "") imgURL = "./img/noProfile1.png"
					if (myNetwork && myNetwork.body.data.nodes._data[item.node_id])
						if (myNetwork.body.data.nodes._data[item.node_id].is_artist && !myNetwork.body.data.nodes._data[item.node_id].image.includes("upload")) imgURL = "./img/profileNoPic.png"
					$img.attr({
						src: imgURL, // path to image of the artist
						alt: "" // none used in case artist image is unavailable
					});
					$li.append('<a href="#">');
					$li.find('a').append($img).append(item.label);
					$li.find('a').css("display", "block");
					return $li.appendTo(ul);
				}
			};

			function ifLetterContains(src_arr, letter) {
				for (i = 0; i < src_arr.length; i++) {
					if (src_arr[i].label.toLowerCase().includes(letter.toLowerCase())) {
						return true;
					}
				}
				return false
			}
			//what displays the suggestions for what to search (for attributes of artists)
			function autocompleteAttribute(searchbox, src) {
				$searchboxNew = $(searchbox);
				$searchboxNew.autocomplete({
					minLength: 1, // minimum of 1 characters to be entered before suggesting genre names
					source: src,
					response: function() {
						if (!ifLetterContains(src, this.value) && typeof this.value !== "undefined") {
							document.getElementById(searchbox.slice(1, searchbox.length)).style.borderColor = "red";
							if (autocompleteTextboxCounter.get(searchbox) == undefined) {
								//console.log("autocomplete box is undefined," + searchbox);
								$('#invalidInputOverlay').fadeIn(300);
								setTimeout(function() {
									$('#invalidInputOverlay').fadeOut(800);
								}, 1100);
							} else {
								//console.log("There is an entry of " + searchbox)
								//console.log("Current length " + autocompleteTextboxCounter.get(searchbox))
								if (this.value.length > autocompleteTextboxCounter.get(searchbox)) {
									$('#invalidInputOverlay').fadeIn(300);
									setTimeout(function() {
										$('#invalidInputOverlay').fadeOut(800);
									}, 1100);
								}
							}
							document.getElementById("search_popup").disabled = true;

						} else {
							document.getElementById(searchbox.slice(1, searchbox.length)).style.borderColor = "green";

							document.getElementById("search_popup").disabled = false;

						}
						if (typeof this.value !== "undefined") {
							autocompleteTextboxCounter.set(searchbox, this.value.length);
						} else {
							autocompleteTextboxCounter.set(searchbox, 0);
						}

					},
					select: function(event, ui) {
						document.getElementById(searchbox.slice(1, searchbox.length)).style.borderColor = "green";
						document.getElementById("search_popup").disabled = false;
						var filter = searchbox.split("#")[1].split("Searchbox")[0];
						if (isNaN(this.value) && filter == "ethnicity") {
							$searchboxNew.val(ui.item.label); // display the selected text
						}
					}
				})
			};

			/**
			 * Function for "Clear All" button, it will clear and hide all search boxes, and perform default search.
			 */
			function clearSearchbox() {
				document.getElementById('searchbox').value = "";
				clearByClass('addArtist', 'Artist');
				clearByClass('addGenre', 'Genre');
				clearByClass('addArtistType', 'ArtistType');
				clearByClass('addCountry', 'Country');
				clearByClass('addState', 'State');
				clearByClass('addCity', 'City');
				clearByClass('addEthnicity', 'Ethnicity');
				clearByClass('addGender', 'Gender');
				resetBorderColourByTheirClass("addGenre");
				resetBorderColourByTheirClass("addCountry");
				resetBorderColourByTheirClass("addState");
				resetBorderColourByTheirClass("addCity");
				for (var filter of Object.keys(search_filters)) search_filters[filter] = "";
				//this removes the search 	banner
				$('input:checkbox').removeAttr('checked');
				for (var name of search_names) name = "-1"
				for (var id of default_ids) id = "-1"
				search_names = ["-1", "-1", "-1", "-1"];
				searchAndDraw({
					action: "centerSearchById",
					"artist_profile_id": ["534", "209", "102", "504"]
				}, lineage_network, function() {
					return undefined;
				});
				originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
				$('#search_text').html(originalText);
			}
			//@author: Tianyu Cao
			function clearByClass(classname, frontID) {
				for (var item of document.getElementsByClassName(classname)) {
					if (item.id.includes('Searchbox')) {
						closeSearchBox(item.parentNode, item, frontID);
					}
				}
				if (classname != 'addArtist') {
					document.getElementById(classname + 'Label').style.display = 'none';
				}

			}


			//create all filters
			//different kinds of filters using same close function may cause bug

			const FilterNodeData = {
				"artist_genre": [],
				"genre": [],
				"artist_residence_city": [],
				"artist_residence_state": [],
				"artist_residence_country": []
			}
			var textArr = [];

			function endFilter() {
				setTimeout(function() {
					closeFilter();
					//console.log("second function executed");
				}, 1);
			}


			function FilterMenuEndEvent() {
				return new Promise(function(resolve, reject) {
					document.getElementById("FilterWindow").style.display = "none";
					resetBorderColourByTheirClass("addGenre_popup");
					resetBorderColourByTheirClass("addCountry_popup");
					resetBorderColourByTheirClass("addState_popup");
					resetBorderColourByTheirClass("addCity_popup");
					$("#filter_div, .topFilterClass,#relation_check,.searchTextClass, #network_container, #small-6 column, #topFilter, #topFilter_text, #navbar, .small-7, .small-5, .footer").removeClass("disabledbutton");
					document.getElementById(lineage_network.conatiner_id).style.display = "none";
					var loading_img = document.getElementById("spin_loading");
					loading_img.style.display = 'inline-block';
					for (let item of document.getElementsByClassName('addGenre_popup')) {
						for (let value of autocompleteLoadedData.genres) {
							if (value.label == item.value) {
								if (item.value.length > 0) {
									if (checkExistance(FilterNodeData.genre, value.label)) {
										createFilterButton(item.value, 'genre', FilterNodeData);
									}
									textArr.push(item.value);
								}
							}
						}
					}

					for (let item of document.getElementsByClassName('addArtistType_popup')) {
						for (let value of autocompleteLoadedData.artist_genres) {
							if (value.label == item.value) {
								if (item.value.length > 0) {
									if (checkExistance(FilterNodeData.artist_genre, value.label)) {
										createFilterButton(item.value, 'artist_genre', FilterNodeData);
									}
									textArr.push(item.value);
								}
							}
						}
					}
					for (let item of document.getElementsByClassName('addCountry_popup')) {
						for (let value of autocompleteLoadedData.country_names) {
							if (value.label == item.value) {
								if (item.value.length > 0) {
									if (checkExistance(FilterNodeData.artist_residence_country, value.label)) {
										createFilterButton(item.value, 'artist_residence_country', FilterNodeData);
									}
									textArr.push(item.value);
								}
							}
						}
					}
					for (let item of document.getElementsByClassName('addCity_popup')) {
						for (let value of autocompleteLoadedData.city_names) {
							if (value.label == item.value) {
								if (item.value.length > 0) {
									if (checkExistance(FilterNodeData.artist_residence_city, value.label)) {
										createFilterButton(item.value, 'artist_residence_city', FilterNodeData);
									}
									textArr.push(item.value);
								}
							}
						}
					}
					for (let item of document.getElementsByClassName('addState_popup')) {
						for (let value of autocompleteLoadedData.state_names) {
							if (value.label == item.value) {
								if (item.value.length > 0) {
									if (checkExistance(FilterNodeData.artist_residence_state, value.label)) {
										createFilterButton(item.value, 'artist_residence_state', FilterNodeData);
									}
									textArr.push(item.value);
								}
							}
						}
					}
					resolve();
				})

			}

			function createFilterButton(value, key, FilterNodeData) {
				if (value.length > 0) {
					var input_button = document.createElement('input');
					var input_button_close = document.createElement('input');
					input_button.type = 'button';
					input_button_close.type = 'button';
					input_button.className = 'AddedFilter';
					input_button.value = value;
					input_button_close.className = 'AddedFilterClose';
					input_button_close.value = "x";
					var div = document.getElementById("AddedFilter");
					document.getElementById("AddedFilter").appendChild(input_button);
					document.getElementById("AddedFilter").appendChild(input_button_close);

					input_button_close.onclick = function() {
						input_button.remove();
						input_button_close.remove();
						var ind = textArr.indexOf(value);
						if (ind !== -1) {
							textArr.splice(ind, 1);
						}
						removeFromResultText(value);
						let data_values = FilterNodeData[key];
						const index = data_values.indexOf(value);
						if (index > -1) {
							data_values.splice(index, 1);
						}
						document.getElementById(lineage_network.conatiner_id).style.display = "none";
						var loading_img = document.getElementById("spin_loading");
						loading_img.style.display = 'inline-block';
						endFilter();
					}
				};
			}

			function closeFilter() {
				var temp_FilterNodeData = {
					"artist_genre": FilterNodeData.artist_genre,
					"genre": [],
					"artist_residence_city": FilterNodeData.artist_residence_city,
					"artist_residence_state": FilterNodeData.artist_residence_state,
					"artist_residence_country": FilterNodeData.artist_residence_country
				}
				//console.log(FilterNodeData.genre)
				for (let i = 0; i < FilterNodeData.genre.length; i++) {
					let item = FilterNodeData.genre[i];

					for (let value of autocompleteLoadedData.genres) {
						if (value.label == item) {
							temp_FilterNodeData.genre.push(value.id);
						}
					}
				}

				var num = lineage_network.applyNodeFilters(temp_FilterNodeData);
				//var numEdge = lineage_network.applyEdgeFilters(temp_FilterNodeData);
				var searchText = document.getElementById('search_text').innerHTML;
				var relaaa = ["Studied Under", "Collaborated With", "Danced in the Work of", "Influenced By"];

				if (document.getElementById('study_rel').checked == false) {
					if (relaaa.indexOf("Studied Under") != -1) {
						relaaa.splice(relaaa.indexOf("Studied Under"), 1);
					}
					if (searchText.length > 0) {
						searchText = removeFromResultText("Studied Under");
					}
				}
				if (document.getElementById('coll_rel').checked == false) {
					if (relaaa.indexOf("Collaborated With") != -1) {
						relaaa.splice(relaaa.indexOf("Collaborated With"), 1);
					}
					if (searchText.length > 0) {
						searchText = removeFromResultText("Collaborated With");
					}
				}
				if (document.getElementById('dance_rel').checked == false) {
					if (relaaa.indexOf("Danced in the Work of") != -1) {
						relaaa.splice(relaaa.indexOf("Danced in the Work of"), 1);
					}
					if (searchText.length > 0) {
						searchText = removeFromResultText("Danced in the Work of");
					}
				}
				if (document.getElementById('infl_rel').checked == false) {
					if (relaaa.indexOf("Influenced By") != -1) {
						relaaa.splice(relaaa.indexOf("Influenced By"), 1);
					}
					if (searchText.length > 0) {
						searchText = removeFromResultText("Influenced By");
					}
				}
				lineage_network.applyEdgeFilters({
					artist_relation: relaaa
				});
				var filterTextIndex = originalText.lastIndexOf('Not');

				//handle filter result text
				//@author Tianyu Cao
				var added = document.getElementsByClassName('AddedFilter');
				if (textArr.length != 0 || relaaa.length != 4 || added.length != 0) {
					if (searchText.length != 0) {
						if (searchText.includes('Not what')) {
							searchText = searchText.substring(0, searchText.lastIndexOf('Not')) + '<span style="font-weight:bold">' + num + '</span>' + ' results remain after applying filter criteria: ';
							if (num == 0) {
								document.getElementById("NoResultWindow").style.display = "block";

							}
						} else {
							searchText = originalText.substring(0, filterTextIndex) + '<span style="font-weight:bold">' + num + '</span>' + ' results remain after applying filter criteria: ';
							if (num == 0) {
								document.getElementById("NoResultWindow").style.display = "block";

							}
						}
					} else {
						searchText = '<span style="font-weight:bold">' + num + '</span>' + ' results remain after applying filter criteria: ';
						if (num == 0) {
							document.getElementById("NoResultWindow").style.display = "block";

						}
					}
					if (textArr.length != 0 || added.length != 0) {
						for (var item of added) {
							if (!searchText.includes(item.value)) {
								searchText = searchText + '<span style="font-weight:bold">' + item.value + '</span>' + ', ';
							}
						}
						for (var item of textArr) {
							if (!searchText.includes(item)) {
								searchText = searchText + '<span style="font-weight:bold">' + item + '</span>' + ', ';
							}
						}
					}
					if (relaaa.length != 4) {
						for (var item of relaaa) {
							if (!searchText.includes(item)) {
								searchText = searchText + '<span style="font-weight:bold">' + item + '</span>' + ', ';
							}
						}
					}
					searchText = searchText.substr(0, searchText.length - 2) + '. ';
					$('#search_text').html(searchText);

				}

				if (textArr.length == 0 && relaaa.length == 4 && originalText.length == 0 && added.length == 0) {
					$('#search_text').html('');
					$('#search_text').hide();
				} else if (textArr.length == 0 && relaaa.length == 4 && originalText.length != 0 && added.length == 0) {
					$('#search_text').html(originalText);
				}

				//then remove popup window
				//remove focus(white background that doesn't allow you to click anything else)
				// //document.getElementById("network_row").disabled = false;
				clearFilterMenu();
				var nodePos = {
					position: {
						x: 0,
						y: 0
					},
					scale: 0.6,
					offset: {
						x: 0,
						y: 0
					},
					animation: {
						duration: 1000,
						easingFunction: "easeInOutQuad"
					}
				}
				lineage_network.vis_net.moveTo(nodePos);

			}

			function removeFromResultText(value) {
				var searchText = document.getElementById('search_text').innerHTML;
				if (searchText.includes(value)) {
					var strIndex = searchText.indexOf(value);
					searchText = searchText.replace((value), '');
					searchText = searchText.substring(0, strIndex + 7) + searchText.substring(strIndex + 9, searchText.length);
					$('#search_text').html(searchText);
				}
				return searchText;
			}

			function resetBorderColourByTheirClass(classNameToReset) {
				var target = document.getElementsByClassName(classNameToReset);
				for (i = 0; i < target.length; i++) {
					target[i].style.borderColor = 'black';
				}

			}
			/**
			 * Function for "Clear" button in filter window. It will clear and hide all filter boxes.
			 */
			function clearFilterMenu() {
				clearByClass('addGenre_popup');
				clearByClass('addArtistType_popup');
				clearByClass('addCountry_popup');
				clearByClass('addState_popup');
				clearByClass('addCity_popup');
				countMap.set("Genre_popup", 0);
				countMap.set("ArtistType_popup", 0);
				countMap.set("Country_popup", 0);
				countMap.set("State_popup", 0);
				countMap.set("City_popup", 0);
				document.getElementById('addGenre_popupSearch').textContent = 'Filter by Genre';
				document.getElementById('addArtistType_popupSearch').textContent = 'Filter by Artist Type';
				document.getElementById('addCountry_popupSearch').textContent = 'Filter by Country';
				document.getElementById('addState_popupSearch').textContent = 'Filter by State';
				document.getElementById('addCity_popupSearch').textContent = 'Filter by City';
				document.getElementById("search_popup").disabled = false;
				resetBorderColourByTheirClass("addGenre_popup");
				resetBorderColourByTheirClass("addCountry_popup");
				resetBorderColourByTheirClass("addState_popup");
				resetBorderColourByTheirClass("addCity_popup");
			}

			/**
			 * This function is called by "submitSearch" function. It will fetch all information from those search boxes or filter boxes that containing
			 * parameter frontID, and store these information to container.
			 * @param  {string} frontID Common ID of search box
			 * @param  {Array} container Array that will be used to store input information.
			 * @author Tianyu Cao
			 */
			function fetchInput(frontID, container) {
				if (frontID == 'Artist' || frontID == 'searchbox') {
					if (frontID == 'Artist') {
						for (var item of document.getElementsByClassName('add' + frontID)) {
							for (var name of autocompleteLoadedData.artist_name) {
								if (name !== undefined && item.value !== undefined && name.label.toUpperCase() == item.value.toUpperCase()) {
									checkExistance(container, name.node_id.toString());
									if (!search_names.includes(name.label) && typeof name.label != "undefined") {
										search_names.push(name.label);
									}
								}
							}
						}
					} else {
						var item = document.getElementById('searchbox')
						for (var name of autocompleteLoadedData.artist_name) {

							if (name !== undefined && item.value !== undefined && name.label.toUpperCase() == item.value.toUpperCase()) {
								checkExistance(container, name.node_id.toString());
								if (!search_names.includes(name.label)) {

									search_names.push(name.label);

								}
							}
						}
					}
				} else if (frontID == 'Genre') {
					for (var item of document.getElementsByClassName('add' + frontID)) {
						for (var gen of autocompleteLoadedData.genres) {
							if (gen !== undefined && item.value !== undefined && gen.label.toUpperCase() == item.value.toUpperCase()) {
								checkExistance(container, gen.id);
							}
						}
					}
				} else {
					for (var item of document.getElementsByClassName('add' + frontID)) {
						if (item.id.includes('Searchbox')) {
							if (item.id.includes('gender') && item.value == 'Prefer not to answer') {
								checkExistance(container, 'prefer_not_to_disclose');
							} else {
								checkExistance(container, item.value);
							}
						}
					}
				}
				//console.log(container);		
			}

			/**
			 * This funciton is will be called by fetchInput() function, it check duplicates and pushes only NEW data to container.
			 * 
			 * @param  {Array} data Container that store data
			 * @param  {string} searchtext Input information
			 * @return {boolean} True if searchtext is pushed successfully; False otherwise.
			 */
			function checkExistance(data, searchtext) {
				if (searchtext.length != 0) {
					if (!data.includes(searchtext)) {
						data.push(searchtext)
						return true
					}
				}
				return false

			}
			/**
			 * This function check if parameter container is empty.
			 * 
			 * @param  {Array} data Container that stores data.
			 */
			function checkEmpty(data) {
				var allEmpty = true;
				if (data.artist_profile_id.length != 0) {
					allEmpty = false
				}
				if (data.artist_gender.length != 0) {
					allEmpty = false
				}
				if (data.artist_genre.length != 0) {
					allEmpty = false
				}
				if (data.genre.length != 0) {
					allEmpty = false
				}
				if (data.artist_residence_country.length != 0) {
					allEmpty = false
				}
				if (data.artist_residence_state.length != 0) {
					allEmpty = false
				}
				if (data.artist_residence_city.length != 0) {
					allEmpty = false
				}
				if (data.artist_ethnicity.length != 0) {
					allEmpty = false
				}
				return allEmpty;
			}

			/**
			 * This function check if user enter nothing into search boxes.
			 * 
			 * @return {boolean} True if user enter nothing; False, otherwise.
			 */

			function checkInputEmpty() {
				if (document.getElementById('searchbox').value !== undefined && document.getElementById('searchbox').value != '') {
					return false;
				}
				for (var item of document.getElementsByClassName('addArtist')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addGenre')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addArtistType')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addArtist')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addCountry')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addState')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addCity')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addEthnicity')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}
				for (var item of document.getElementsByClassName('addGender')) {
					if (item.value !== undefined && item.value != '') {
						return false;
					}
				}

				return true;
			}

			$("#network_display_div").show();
			$("#artistlist_display_div").hide();
			$("#familytree_display_div").hide();
			$("#network_div").css({
				"height": "75%"
			});

			

			function submitSearch() {

				if ($(window).width() < 1024) {
					$("#filter_close_button").click();
				};

				clearFilterMenu();
				resetBorderColourByTheirClass("addGenre");
				resetBorderColourByTheirClass("addCountry");
				resetBorderColourByTheirClass("addState");
				resetBorderColourByTheirClass("addCity");

				console.log(document.getElementsByClassName('AddedFilter')[1]);
				if (document.getElementsByClassName('AddedFilter').length > 0) {
					var i = 0;
					while (i <= document.getElementsByClassName('AddedFilter').length + 1) {
						if (typeof document.getElementsByClassName('AddedFilter')[0] != 'undefined') {
							document.getElementsByClassName('AddedFilter')[0].remove();
						}
						i = i + 1;
					}
					i = 0
					while (i <= document.getElementsByClassName('AddedFilterClose').length + 1) {
						if (typeof document.getElementsByClassName('AddedFilterClose')[0] != 'undefined') {
							document.getElementsByClassName('AddedFilterClose')[0].remove();
						}
						i = i + 1;
					}
				}
				textArr = [];
				var loadData = {
					"action": "filterSearchForALL",
					"artist_profile_id": [],
					"artist_gender": [],
					"artist_genre": [],
					"genre": [],
					"artist_residence_city": [],
					"artist_residence_state": [],
					"artist_ethnicity": [],
					"artist_residence_country": []
				}
				//console.log( autocompleteLoadedData.artist_name);
				//search_names = ["-1", "-1", "-1", "-1"];
				for (var name of search_names) name = "-1";
				if (document.getElementById('searchbox').disabled) {
					document.getElementById('searchbox').disabled = false;
					document.getElementById('searchbox').value = 'Melanie Aceto';
					search_names = ["Melanie Aceto", "-1", "-1", "-1"];
				}

				fetchInput('searchbox', loadData.artist_profile_id)
				fetchInput('Artist', loadData.artist_profile_id)
				fetchInput('Genre', loadData.genre)
				fetchInput('ArtistType', loadData.artist_genre)
				fetchInput('Country', loadData.artist_residence_country)
				fetchInput('City', loadData.artist_residence_city)
				fetchInput('State', loadData.artist_residence_state)
				fetchInput('Ethnicity', loadData.artist_ethnicity)
				fetchInput('Gender', loadData.artist_gender)
				//console.log(search_names);
				var noname = true
				for (var name of loadData.artist_profile_id) {
					if (name != null) {
						noname = false;
						break;
					}
				}
				loadData.action = (noname == true ? 'filterSearchForALL' : 'centerSearchById')
				var empty = checkEmpty(loadData);
				JSON.stringify(loadData);
				if (!empty) {
					searchAndDraw(loadData, lineage_network, function(result, mainNodeCount) {
						//console.log(result);
						if (result.length == 0) {
							$('#mySidenav').hide();
							$('#search_text').html('&nbsp&nbsp' + "No Results Found. Please change your search criteria.");
							document.getElementById('NoResultWindow').style.display = 'block';
						} else {
							$('#mySidenav').hide();
							var str = '<span style="font-weight:bold">' + mainNodeCount[0].nodeCount + '</span>' + " result(s) for ";
							//var countStr = " " + mainNodeCount + " artist(s) found";
							// if(empty){
							// 	str = "The entire social network diagram is showing below:"
							// }else{
							str = handleResult(loadData, str) + ". " + '<span style="font-weight:bold">' + (lineage_network.nodes.size - parseInt(mainNodeCount[0].nodeCount)) + '</span>' + " related artist(s) found";


							str = str + ". Not what you were looking for? Try changing your search criteria!";
							// }				
							$('#search_text').html(str);
							originalText = document.getElementById('search_text').innerHTML;
						}
					})
				} else {
					if (checkInputEmpty()) {
						searchAndDraw({
							action: "centerSearchById",
							"artist_profile_id": ["534", "209", "102", "504"]
						}, lineage_network, function() {
							return undefined;
						});
						originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
						$('#search_text').html(originalText);
					} else {
						searchAndDraw({
							action: "centerSearchById",
							"artist_profile_id": ["-1"]
						}, lineage_network, function() {
							return undefined;
						});
						$('#search_text').html('&nbsp&nbsp' + "No Results Found. Please change your search criteria.");
						document.getElementById('NoResultWindow').style.display = 'block';
					}
				}
				//alert(test);	

				$("#mySidenav").hide();
			}






















			function searchAndDraw(args, network, showResult) {
				$("#mySidenav").hide();
				$("#network_display_div").show();
				$("#artistlist_display_div").hide();
				$("#familytree_display_div").hide();
				$("#network_div").css({
					"height": "75%"
				});

				var empty = isEmpty(args);
				var loginSearch = false;
				if (args.action == 'centerSearchById' && args.length == 1 && args.artist_profile_id[0] === PROFILE_ID) {
					loginSearch = true;
				}
				console.log(empty);
				var json_args = JSON.stringify(args);
				console.log("Searching " + json_args);
				var nonFound = true;
				document.getElementById(network.conatiner_id).style.display = "none";
				var loading_img = document.getElementById("spin_loading");
				loading_img.style.display = 'inline-block';

				var start = Date.now();
				console.log(start);
				$.ajax({
					type: "POST",
					url: "./artistcontroller.php",
					data: json_args,
					success: function(response) {

						console.log(response);
						loading_img.style.display = 'none';
						// document.getElementById(network.conatiner_id).style.display = "inline-block";
						document.getElementById("loadingBar").style.display = "inline-block";
						network.vis_net.on("stabilizationProgress", function(params) {
							// console.log(params.total);

							// console.log(document.getElementById("loadingBar").style.display)
							var maxWidth = 446;
							var minWidth = 20;
							var widthFactor = params.iterations / params.total;
							var width = Math.max(minWidth, maxWidth * widthFactor);

							var x = window.innerWidth / window.screen.width;

							document.getElementById("progress_bar").style.width = x * width + "px";
							document.getElementById("progress_text").innerText =
								Math.round(widthFactor * 100) + "%";
						});

						network.vis_net.on('stabilizationIterationsDone', function() {
							let net = this;
							setTimeout(function() {
								document.getElementById("loadingBar").style.display = "none";
								document.getElementById(network.conatiner_id).style.display = "inline-block";
								net.stopSimulation();
								//console.log(PROFILE_ID);
								if (typeof PROFILE_ID != 'undefined' && !empty && loginSearch) {
									var {
										x: nodeX,
										y: nodeY
									} = lineage_network.vis_net.getPositions(PROFILE_ID)[PROFILE_ID];
									var nodePos = {
										position: {
											x: nodeX,
											y: nodeY
										},
										scale: 0.8,
										offset: {
											x: 0,
											y: 0
										},
										animation: {
											duration: 1000,
											easingFunction: "easeInOutQuad"
										}

									}
									lineage_network.vis_net.moveTo(nodePos);
								} else {
									net.fit();
								}
							}, 100);
						});


						document.getElementById("progress_bar").style.width = 0 + "px";
						document.getElementById("progress_text").innerText =
							0 + "%";
						network.setDataFromArray(response["result"]);
						showResult(response["result"], response["mainNodeCount"]);
						network.draw();
					},
					error: function(xhr, status, err) {
						console.log(xhr.responseText);
					},
					dataType: "json",
					contentType: "application/json"
				});
			};
















			function searchFamilyTree() {

				$("#mySidenav").hide();
				$("#network_display_div").hide();
				$("#familytree_display_div").show();
				$("#artistlist_display_div").hide();

				$("#network_div").css({
					"height": "120%"
				});
			}



			function closeFamilyTree() {

				$("#mySidenav").hide();
				$("#network_display_div").show();
				$("#familytree_display_div").hide();
				$("#artistlist_display_div").hide();

				$("#network_div").css({
					"height": "75%"
				});

			}







			function searchEntireNet() {

				if ($(window).width() < 1024) {
					$("#filter_close_button").click();
				};

				$("#mySidenav").hide();
				$("#network_display_div").hide();
				$("#artistlist_display_div").hide();
				$("#familytree_display_div").hide();
				$("#network_div").css({
					"height": "75%"
				});

				if (document.getElementsByClassName('AddedFilter').length > 0) {
					for (var addedFilterTag of document.getElementsByClassName('AddedFilter')) {
						addedFilterTag.remove();
					}
					for (var addedFilterClose of document.getElementsByClassName('AddedFilterClose')) {
						addedFilterClose.remove();
					}
				}
				
				textArr = [];
				var loadData = {
					"action": "filterSearchForALL",
					"artist_profile_id": [],
					"artist_gender": [],
					"artist_genre": [],
					"genre": [],
					"artist_residence_city": [],
					"artist_residence_state": [],
					"artist_ethnicity": [],
					"artist_residence_country": []
				}

				searchAndDraw(loadData, lineage_network, function(result, mainNodeCount) {
					var str = '<span style="font-weight:bold">' + mainNodeCount[0].nodeCount + '</span>' + " Artists found for the entire network:";
					$('#search_text').html(str);
					originalText = document.getElementById('search_text').innerHTML;
				});

			}













			/**
			 * This function generates result text.
			 * 
			 * @param  {Array} data Data array that store the result of search.
			 * @param  {string} str Common part of result information.
			 * @return {string} Complete result information that will be display in result text div.
			 * @author Tianyu Cao
			 */
			function handleResult(data, str) {
				if (search_names[search_names.length - 2] == '-1' && search_names[search_names.length - 1] != '-1') {
					var temp = search_names[0];
					search_names[0] = search_names[search_names.length - 1];
					search_names[search_names.length - 1] = temp;
					search_names.length--;
				};

				console.log(search_names);

				var nameResult = ''
				if (data.artist_profile_id.length != 0 && data.artist_profile_id.length <= 4) {
					for (var name of search_names) {
						if (name != '-1') {
							nameResult = nameResult + '<span style="font-weight:bold">' + name + '</span>' + ', '
						}
					}
					nameResult = nameResult.substr(0, nameResult.length - 2)
					str = str + nameResult
					str = str + frontResult(data, str) + postResult(data, str)
				} else if (data.artist_profile_id.length != 0 && data.artist_profile_id.length > 4) {
					var i = 0
					while (i <= 2 && search_names[i] != '-1') {
						str = str + '<span style="font-weight:bold">' + search_names[i] + '</span>' + ", "
						i = i + 1
					}
					nameResult = nameResult.substr(0, nameResult.length - 2) + '  and other artists'
					str = str + nameResult
					str = str + frontResult(data, str) + postResult(data, str)
				} else if (data.artist_profile_id.length == 0) {
					str = str + frontResult(data, str) + postResult(data, str)
				}
				return str
			}
			/**
			 * This function handle artist name, gneder, genre, type, ethnicity result information.
			 * 
			 * @param  {Array} data Data array that store the result of search.
			 * @param  {string} str Common part of result information.
			 */
			function frontResult(data, str) {
				var frontstr = ''
				if (data.artist_profile_id.length != 0) {
					((data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0) ?
						frontstr = frontstr + ', of' :
						str)
				} else {
					//alert((data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0) );
					(data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0 ?
						frontstr = frontstr + 'artists of' :
						frontstr)
				}
				if (data.artist_gender.length != 0) {
					frontstr = frontstr + " gender: "
					for (var gend of data.artist_gender) {
						if (gend == 'prefer_not_to_disclose') {
							frontstr = frontstr + '<span style="font-weight:bold"> Prefer not to answer</span>' + ", "
						} else {
							frontstr = frontstr + '<span style="font-weight:bold">' + gend + '</span>' + ", "
						}
					}
					frontstr = frontstr.substr(0, frontstr.length - 2)
				}

				if (data.artist_genre.length != 0 && data.artist_genre.length <= 3) {
					(data.artist_gender.length != 0 ?
						frontstr = frontstr + ', type: ' :
						frontstr = frontstr + ' type: ')
					for (var type of data.artist_genre) {
						frontstr = frontstr + '<span style="font-weight:bold">' + type + '</span>' + ", "
					}
					frontstr = frontstr.substr(0, frontstr.length - 2)
				} else if (data.artist_genre.length != 0 && data.artist_genre.length > 3) {
					(data.artist_gender.length != 0 ?
						frontstr = frontstr + ', artist type: ' :
						frontstr = frontstr + ' artist type:')
					var i = 0;
					while (i <= 2) {
						frontstr = frontstr + '<span style="font-weight:bold">' + data.artist_genre[i] + '</span>' + ", "
						i = i + 1
					}
					((data.genre.length != 0 || data.artist_ethnicity.length != 0) ?
						frontstr = frontstr.substr(0, frontstr.length - 2) :
						frontstr = frontstr + ' and other types')
				}

				if (data.genre.length != 0 && data.genre.length <= 3) {
					((data.artist_gender.length != 0 || data.artist_genre.length != 0) ?
						frontstr = frontstr + ', genre: ' :
						frontstr = frontstr + ' genre: ')
					// for (var gre of data.genre){
					// 	frontstr = frontstr + '<span style="font-weight:bold">' + gre +'</span>' + ", "
					// }
					for (var gre of autocompleteLoadedData.genres) {
						if (data.genre.includes(gre.id)) {
							frontstr = frontstr + '<span style="font-weight:bold">' + gre.label + '</span>' + ", "
						}
					}
					frontstr = frontstr.substr(0, frontstr.length - 2)
				} else if (data.genre.length != 0 && data.artist_genre.length > 3) {
					((data.artist_gender.length != 0 || data.artist_genre.length != 0) ?
						frontstr = frontstr + ', genre: ' :
						frontstr = frontstr + ' genre: ')
					var i = 0;
					while (i <= 2) {
						if (data.genre.includes(autocompleteLoadedData.genres[i].id)) {
							frontstr = frontstr + '<span style="font-weight:bold">' + autocompleteLoadedData.genres[i].label + '</span>' + ", "
						}
						//frontstr = frontstr + '<span style="font-weight:bold">' + data.genre[i] +'</span>' + ", "
						i = i + 1
					}
					((data.artist_ethnicity.length != 0) ?
						frontstr = frontstr.substr(0, frontstr.length - 2) :
						frontstr = frontstr + ' and other genres')
				}

				if (data.artist_ethnicity.length != 0 && data.artist_ethnicity.length <= 3) {
					((data.artist_gender.length != 0 || data.genre.length != 0 || data.artist_genre.length != 0) ?
						frontstr = frontstr + ', ethnicity: ' :
						frontstr = frontstr + ' ethnicity: ')
					for (var race of data.artist_ethnicity) {
						frontstr = frontstr + '<span style="font-weight:bold">' + race + '</span>' + ", "
					}
					frontstr = frontstr.substr(0, frontstr.length - 2)
				} else if (data.artist_ethnicity.length != 0 && data.artist_ethnicity.length > 3) {
					((data.artist_gender.length != 0 || data.genre.length != 0 || data.artist_genre.length != 0) ?
						frontstr = frontstr + ', ethnicity: ' :
						frontstr = frontstr + ' ethnicity: ')
					var i = 0;
					while (i <= 2) {
						frontstr = frontstr + '<span style="font-weight:bold">' + data.artist_ethnicity[i] + '</span>' + ", "
						i = i + 1
					}
					frontstr = frontstr.substr(0, frontstr.length - 2) + ' and other ethnicities'
				}
				return frontstr
			}
			/**
			 * This function handle artist country, city, and state result information.
			 * 
			 * @param  {Array} data Data array that store the result of search.
			 * @param  {string} str Common part of result information.
			 */
			function postResult(data, str) {
				var poststr = ''
				if (data.artist_profile_id.length != 0 && (data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0)) {
					((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0) ?
						poststr = poststr + ', from' :
						poststr)
				} else if (data.artist_profile_id.length == 0 && data.artist_gender.length == 0 && data.artist_genre.length == 0 && data.genre.length == 0 && data.artist_ethnicity.length == 0) {
					((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0) ?
						poststr = poststr + 'artists from' :
						poststr)
				} else if (data.artist_profile_id.length == 0 && (data.artist_gender.length == 0 || data.artist_genre.length == 0 || data.genre.length == 0 || data.artist_ethnicity.length == 0)) {
					((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0) ?
						poststr = poststr + ', from' :
						poststr)
				}
				if (data.artist_residence_country.length != 0 && data.artist_residence_country.length <= 3) {
					poststr = poststr + " country: "
					for (var coun of data.artist_residence_country) {
						poststr = poststr + '<span style="font-weight:bold">' + coun + '</span>' + ", "
					}
					poststr = poststr.substr(0, poststr.length - 2)
				} else if (data.artist_residence_country.length != 0 && data.artist_residence_country.length > 3) {
					poststr = poststr + " country: "
					var i = 0
					while (i <= 2) {
						poststr = poststr + '<span style="font-weight:bold">' + data.artist_residence_country[i] + '</span>' + ", "
						i = i + 1
					}
					((data.artist_residence_state != 0 || data.artist_residence_city != 0) ?
						poststr = poststr.substr(0, poststr.length - 2) :
						poststr = poststr + ' and other countries')
				}
				if (data.artist_residence_state.length != 0 && data.artist_residence_state.length <= 3) {
					(data.artist_residence_country.length != 0 ?
						poststr = poststr + ', state: ' :
						poststr = poststr + ' state: ')
					for (var state of data.artist_residence_state) {
						poststr = poststr + '<span style="font-weight:bold">' + state + '</span>' + ", "
					}
					poststr = poststr.substr(0, poststr.length - 2)
				} else if (data.artist_residence_state.length != 0 && data.artist_residence_state.length > 3) {
					(data.artist_residence_country.length != 0 ?
						poststr = poststr + ', state: ' :
						poststr = poststr + ' state: ')
					var i = 0
					while (i <= 2) {
						poststr = poststr + '<span style="font-weight:bold">' + data.artist_residence_state[i] + '</span>' + ", "
						i = i + 1
					}
					(data.artist_residence_city != 0 ?
						poststr = poststr.substr(0, poststr.length - 2) :
						poststr = poststr + ' and other states')
				}
				if (data.artist_residence_city.length != 0 && data.artist_residence_city.length <= 3) {
					((data.artist_residence_country.length != 0 || data.artist_residence_state.length != 0) ?
						poststr = poststr + ', city: ' :
						poststr = poststr + ' city: ')
					for (var city of data.artist_residence_city) {
						poststr = poststr + '<span style="font-weight:bold">' + city + '</span>' + ", "
					}
					poststr = poststr.substr(0, poststr.length - 2)
				} else if (data.artist_residence_city.length != 0 && data.artist_residence_city.length > 3) {
					((data.artist_residence_country.length != 0 || data.artist_residence_state.length != 0) ?
						poststr = poststr + ', city: ' :
						poststr = poststr + ' city: ')
					var i = 0
					while (i <= 2) {
						poststr = poststr + '<span style="font-weight:bold">' + data.artist_residence_city[i] + '</span>' + ", "
						i = i + 1
					}
					poststr = poststr.substr(0, poststr.length - 2) + ' and other places'
				}
				return poststr
			}

			//this is called in the onNetworkChange function, listed at the bottom in "selectNode"
			function expandNode(id_selected) {
				//for nodes who have no connections, we want to tell the user that they clicked the node correctly, it is merely that no expansion was possible
				var noLineage = {}
				// we do not need to re-expand the currently selected node
				if (id_selected !== selected) {
					selected = id_selected
					$.ajax({
						type: "POST",
						url: 'artistcontroller.php',
						data: JSON.stringify({
							"action": "getArtistProfileByName",
							"artist_profile_id": id_selected
						}),
						success: function(response) {
							response = JSON.stringify(response);
							json_object = $.parseJSON(response);
							nodesFetched = json_object.artist_profile;
							if (nodesFetched) noLineage = getNetworkData(nodesFetched)
							// if they have no lineage alert user
							if (noLineage[id_selected]) $("div.alert-box").fadeIn(300).delay(1500).fadeOut(400);
							// otherwise let the user know about more functionality, like double clicking
							if (!noLineage[id_selected]) $("div.suggestion-box").fadeIn(300).delay(1500).fadeOut(400);
							// we want the selected node to become a square, the rest should stay circles
							for (var node of default_nodes) {
								if (node.id === selected) node['shape'] = selected_shape_image;
								else node['shape'] = default_shape;
							}
							for (var page of Object.keys(all_nodes)) {
								for (var node of all_nodes[page].nodes) {
									if (node.id === selected) node['shape'] = selected_shape_image;
									else node['shape'] = default_shape;
								}
							}
							// default nodes is already updated, but we want to keep all_nodes updated too
							for (var id of default_ids)
								if (id !== "-1")
									if (myNetwork.body.data.nodes._data[id] && myNetwork.body.data.nodes._data[id].artist_relation.length <= 0)
										for (var page of Object.keys(all_nodes))
											for (var node of default_nodes) {
												if (!all_nodes[page].associatedNodeIDs.has(node.id)) all_nodes[page].nodes.push(node)
												all_nodes[page].associatedNodeIDs.add(node.id)
											}
							//console.log(default_nodes)
							// how we get the network to reflect these changes in real time
							myNetwork.body.data.nodes.update(default_nodes);
							myNetwork.body.data.edges.update(default_edges);
							myNetwork.redraw();
							myNetwork.setOptions({
								physics: true
							});
							network.setOptions({
								physics: {
									timestep: 0.1
								}
							});
							network.setOptions({
								physics: {
									stabilization: {
										iterations: 0
									}
								}
							});
							// myNetwork.setOptions( { physics: false} );
							onNetworkChange(myNetwork)
							focusOnNode(id_selected, 0.5, 0, -300)
							updatePage(currentPageType)
						}
					})
				}
			}

			// this is called in the onNetworkChange function, listed at the bottom in "doubleClick"
			function collapseNode(id) {
				var reversedIDtoName = {}
				// make it so we can use the name of someone to find their id
				for (var idNode of Object.keys(idToName)) reversedIDtoName[idToName[idNode]] = idNode
				var expanded = true;
				var newNodes = []
				// artists names currently listed as "firstname-lastname" -->  change to "firstname lastname"
				for (var relation of myNetwork.body.data.nodes._data[id.toString()].artist_relation) {
					var dashedName = relation.artist_name.split("-")
					var name = dashedName[0] + " " + dashedName[1]
					if (!Object.keys(myNetwork.body.data.nodes._data).includes(reversedIDtoName[name])) expanded = false
					else newNodes.push(reversedIDtoName[name])
				}
				twoWayNode = -1
				// collapsing a node that has a two way connection means you do not want to close its to nodes, only from
				for (var edge of all_nodes["Full Network"].edges) {
					if (edge.twoWay == true) {
						if (edge.to == id) twoWayNode = edge.from
						if (edge.from == id) twoWayNode = edge.to
					}
				}
				if (twoWayNode !== -1) newNodes.splice(newNodes.indexOf(twoWayNode), 1)
				for (var node of newNodes) {
					// what updates our data structures
					getRidOfNode(node)
					// what takes them out of the network in real time
					myNetwork.body.data.nodes.remove(node.toString())
				}
				myNetwork.redraw();
				formatTabLabels(currentPageType);
			}

			// called in collapseNode, used to update default_nodes and all_nodes
			function getRidOfNode(id) {
				for (var node of default_nodes)
					if (node.id == id) default_nodes.splice(default_nodes.indexOf(node), 1)

				for (var page of Object.keys(all_nodes)) {
					for (var node of all_nodes[page].nodes)
						if (node.id == id) all_nodes[page].nodes.splice(all_nodes[page].nodes.indexOf(node), 1)
					for (var i = 0; i < all_nodes[page].associatedNodeIDs.length; i++)
						if (all_nodes[page].associatedNodeIDs[i] == id) all_nodes[page].associatedNodeIDs.splice(i, 1)
				}
				for (var i = 0; i < nodeIDs_visible.length; i++)
					if (nodeIDs_visible[i] == id) nodeIDs_visible.splice(i, 1)
			}

			// how to check if a picture has successfully loaded
			function checkIfLoaded(photo_path) {
				var imgElem = document.createElement("img")
				imgElem.src = photo_path
				if (imgElem.naturalWidth == 0) return false
				else return true
			}

			// how to make function zoom in on particular node and where;
			// id refers to the node, scale refers to how far we are zooming, and x and y refer to where on the page this node will appear
			function focusOnNode(id_selected, scale, x, y) {
				//network.redraw()
				myNetwork.focus(
					id_selected, // which node to focus on
					{
						scale: scale, // level of zoom while focussing on node
						offset: {
							x: x,
							y: y
						},
						animation: {
							duration: 1000, // animation duration in milliseconds (Number)
							easingFunction: "easeInQuad" // type of animation while focussing
						}
					})
			}


			// tells us if an edge (given and edge id) is two way based on one type of connection
			function determineTwoWay(edge) {
				var twoWayID = -1
				// goes to particular type of relationship (categories are keys in all_nodes)
				var relevantEdges = all_nodes[edge.label].edges
				for (var elem of relevantEdges) {
					if (elem.to == edge.from && elem.from == edge.to) {
						twoWayID = elem.id
						elem.twoWay = true
					}
				}
				for (var elem of default_edges)
					if (twoWayID == elem.id) elem.twoWay = true
				for (var elem of all_nodes["Full Network"].edges)
					if (twoWayID == elem.id) elem.twoWay = true
				//returns true if the connection went both ways, false otherwise
				if (twoWayID !== -1) return true
				else return false
			}

			// this is the initial setup of our data structures
			function getNetworkData(nodesFetched) {
				// where we set up which nodes have lineage or not using an object where the keys are node ids and the values are true if they have no lineage and false if they have a lineage
				var noLineage = {}
				for (var i = 0; i < nodesFetched.length; i++) {
					// nodesFetched[i].artist_relation will be "" if no lineage
					var artistRelations = nodesFetched[i].artist_relation;
					if (artistRelations) {
						noLineage[nodesFetched[i].artist_profile_id] = false
						for (var j = 0; j < artistRelations.length; j++) {
							// formats the data about the edges
							var edgeDetails_new = fillInEdgedata(artistRelations[j])
							// if the connection is two ways, we only want to show the line for one direction, but include the data (on hover) about both;
							// see onNetworkChange's "hoverEdge" for the info displayed
							var isTwoWay = determineTwoWay(edgeDetails_new)
							// network will yield an error if a node/edge is rendered twice
							if (!edgeIDs_visible.includes(edgeDetails_new.id) && !isTwoWay) {
								//data structure used to load the network
								default_edges.push(edgeDetails_new);
								//data structure used to keep track of the possible filters
								// we must push both to the filter specific data structures
								all_nodes[edgeDetails_new.label].associatedEdgeIDs.add(edgeDetails_new.id)
								all_nodes[edgeDetails_new.label].edges.push(edgeDetails_new)
								// we need to figure out which nodes we are going to include based on the edge descriptions
								all_nodes[edgeDetails_new.label].associatedNodeIDs.add(edgeDetails_new.from)
								all_nodes[edgeDetails_new.label].associatedNodeIDs.add(edgeDetails_new.to)
								// and to what the network would look like with all relationships
								all_nodes["Full Network"].associatedEdgeIDs.add(edgeDetails_new.id)
								all_nodes["Full Network"].edges.push(edgeDetails_new)
								all_nodes["Full Network"].associatedNodeIDs.add(edgeDetails_new.from)
								all_nodes["Full Network"].associatedNodeIDs.add(edgeDetails_new.to)
							}
							// keep track of what is visible
							edgeIDs_visible.push(edgeDetails_new.id)
						}
					} else noLineage[nodesFetched[i].artist_profile_id] = true // if no relationships, no lineage for this node should be true
					// now let's take care of the nodes
					var nodeDetails_new = fillInNodeData(nodesFetched[i])
					// only add node if not yet present
					if (!nodeIDs_visible.includes(nodeDetails_new.id)) {
						// add to what loads network
						default_nodes.push(nodeDetails_new)
						// add to storage data structures
						all_nodes["Full Network"].nodes.push(nodeDetails_new)
						for (var page of Object.keys(all_nodes)) {
							// we are filling in the nodes that were already specified by the edges in the correct filters
							if (page !== "Full Network")
								for (var id of all_nodes[page].associatedNodeIDs) {
									if (nodeDetails_new.id == id) all_nodes[page].nodes.push(nodeDetails_new)
								}
						}
					}
					// if we only have one node, we want to show it in all filters
					nodeIDs_visible.push(nodeDetails_new.id)
					if (nodesFetched.length === 1)
						for (var page of Object.keys(all_nodes)) {
							all_nodes[page].nodes = [nodeDetails_new]
							all_nodes[page].associatedNodeIDs.add(nodeDetails_new.id)
						}
				}
				return noLineage
			}

			// how we fill in data about our nodes based on what we got from SQL (see artistcontroller.php for where we get this data)
			function fillInNodeData(node) {
				var nodeDetails_new = {};
				nodeDetails_new['id'] = node.artist_profile_id;
				nodeDetails_new['title'] = node.artist_first_name + " " + node.artist_last_name;
				nodeDetails_new['shape'] = default_shape;
				nodeDetails_new['label'] = node.artist_first_name + " " + node.artist_last_name;

				if (node.artist_biography_text) nodeDetails_new['biography'] = node.artist_biography_text;
				else if (node.artist_biography) nodeDetails_new['biography'] = node.artist_biography;

				if (node.artist_yob) nodeDetails_new['yob'] = node.artist_yob;

				if (node.artist_dod) nodeDetails_new['dod'] = node.artist_dod;
				if (node.artist_living_status) nodeDetails_new['livingStatus'] = node.artist_living_status
				else nodeDetails_new['livingStatus'] = ""

				if (node.artist_photo_path) nodeDetails_new['image'] = node.artist_photo_path;
				else nodeDetails_new['image'] = "upload/photo_upload_data/missing_image.jpg";

				// denotes size of circle representing each node
				nodeDetails_new['size'] = "20";
				if (node.artist_profile_id === selected) {
					if (node.artist_photo_path !== "") {
						nodeDetails_new['image'] = node.artist_photo_path;
						if (checkIfLoaded(node.artist_photo_path)) nodeDetails_new['shape'] = selected_shape_image;
						else nodeDetails_new['shape'] = selected_shape;
					} else {
						if (node.is_user_artist === "artist") nodeDetails_new['image'] = "./img/profileNoPic.png";
						nodeDetails_new['shape'] = selected_shape_image;
					}
				} else {
					if (node.is_user_artist === "artist") {
						if (node.artist_photo_path !== "") nodeDetails_new['image'] = node.artist_photo_path;
						else nodeDetails_new['image'] = "./img/profileNoPic.png";
						nodeDetails_new['shape'] = default_shape
						nodeDetails_new['is_artist'] = true
						nodeDetails_new['color'] = isArtistNodeColor
					} else {
						nodeDetails_new['shape'] = default_shape
						nodeDetails_new['is_artist'] = false
						nodeDetails_new['color'] = notArtistNodeColor
					}
				}

				if (node.artist_gender) nodeDetails_new['gender'] = node.artist_gender;
				else nodeDetails_new['gender'] = "";

				if (node.artist_genre) nodeDetails_new['artistType'] = node.artist_genre;
				else nodeDetails_new['artistType'] = "";

				if (node.artist_ethnicity) nodeDetails_new['ethnicity'] = node.artist_ethnicity;
				else nodeDetails_new['ethnicity'] = "";

				if (node.artist_residence_city) nodeDetails_new['city'] = node.artist_residence_city;
				else nodeDetails_new['city'] = "";

				if (node.artist_residence_state) nodeDetails_new['state'] = node.artist_residence_state;
				else nodeDetails_new['state'] = "";

				if (node.artist_residence_country) nodeDetails_new['country'] = node.artist_residence_country;
				else nodeDetails_new['country'] = "";

				if (node.genre) nodeDetails_new['genre'] = node.genre;
				else nodeDetails_new['genre'] = "";

				if (node.artist_education) {
					eduNodes = node.artist_education;
					for (var j = 0; j < eduNodes.length; j++) {
						if (eduNodes[j].education_type === "main") {
							nodeDetails_new['university'] = eduNodes[j].institution_name;
							nodeDetails_new['degree'] = eduNodes[j].degree
							nodeDetails_new['major'] = eduNodes[j].major
						} else if (eduNodes[j].education_type === "other") nodeDetails_new['university_other'] = eduNodes[j].institution_name;
					}
				} else {
					nodeDetails_new['university'] = "";
					nodeDetails_new['degree'] = "";
					nodeDetails_new['major'] = "";
					nodeDetails_new['university_other'] = "";
				}

				if (node.artist_relation) {
					var relNodes = node.artist_relation;
					var artist_relation = [];
					for (var j = 0; j < relNodes.length; j++) {
						var relation = {};
						relation['artist_name'] = relNodes[j].artist_name_2;
						relation['relationship'] = relNodes[j].artist_relation;
						artist_relation.push(relation);
					}
					nodeDetails_new["artist_relation"] = artist_relation;
					nodeDetails_new['is_artist'] = true
					nodeDetails_new['color'] = isArtistNodeColor
					if (node.artist_photo_path !== "") nodeDetails_new['image'] = node.artist_photo_path;
					else nodeDetails_new['image'] = "./img/profileNoPic.png";
				} else nodeDetails_new["artist_relation"] = "";
				return nodeDetails_new
			}

			// how we fill in data about our edges based on what we got from SQL (see artistcontroller.php for where we get this data)
			function fillInEdgedata(artistRelation) {
				var edgeDetails_new = {};
				edgeDetails_new['id'] = artistRelation.relation_id;
				edgeDetails_new['to'] = artistRelation.artist_profile_id_1;
				edgeDetails_new['from'] = artistRelation.artist_profile_id_2;
				edgeDetails_new['width'] = "0";
				edgeDetails_new['twoWay'] = false
				edgeDetails_new['label'] = artistRelation.artist_relation;
				edgeDetails_new['color'] = edge_colors_dict[artistRelation.artist_relation]
				return edgeDetails_new
			}

			function onNetworkChange(network) {
				// set physics to false after stabilization iterations
				network.on("stabilizationIterationsDone", function() {
					network.setOptions({
						physics: false
					});

				});

				// hide the loading div after network is fully loaded
				network.on("afterDrawing", function() {
					$("#load").css("display", "none");
					//weFocused = true
					//$("body").css("overflow", "scroll");
				});

				// change the type of cursor to grabbing hand while dragging the network
				//Charul Testing
				network.on('dragging', function(obj) {
					$("#my_network").css("cursor", "-webkit-grabbing");
					//selected = -1
				});

				// change the type of cursor to hand on releasing the drag
				network.on('release', function(obj) {
					$("#my_network").css("cursor", "-webkit-grab");
					selected = -1
				});
				//CHarul Testing

				// change the type of cursor to pointing hand when hovered over a node
				network.on('hoverNode', function(obj) {
					$("#my_network").css("cursor", "pointer");
					$("#my_network").attr('title', 'No. of connections= ' + network.getConnectedEdges(obj.node).length);
					//$(".rightClickSuggestion").show()
				});

				network.on('hoverEdge', function(obj) {
					var relationship = myNetwork.body.edges[obj.edge].body.data.edges._data[obj.edge].label
					var to = idToName[myNetwork.body.edges[obj.edge].from.id]
					var from = idToName[myNetwork.body.edges[obj.edge].to.id]
					var twoWay = myNetwork.body.edges[obj.edge].body.data.edges._data[obj.edge].twoWay
					$("#my_network").css("cursor", "pointer");
					if (twoWay) {
						if (relationship.toLowerCase() === "influenced by") $("#my_network").attr('title', from + " was " + relationship.toLowerCase() + " " + to + " and " + to + " was " + relationship.toLowerCase() + " " + from);
						else $("#my_network").attr('title', from + " " + relationship.toLowerCase() + " " + to + " and " + to + " " + relationship.toLowerCase() + " " + from);
					} else {
						if (relationship.toLowerCase() === "influenced by") $("#my_network").attr('title', from + " was " + relationship.toLowerCase() + " " + to);
						else $("#my_network").attr('title', from + " " + relationship.toLowerCase() + " " + to);
					}
				});

				// change the type of cursor to hand on coming out of node hover
				network.on('blurNode', function(obj) {
					$("#my_network").css("cursor", "-webkit-grab");
					$(".rightClickSuggestion").hide()
				});

				network.on('blurEdge', function(obj) {
					$("#my_network").css("cursor", "-webkit-grab");
				});
				network.on('selectEdge', function(obj) {});

				network.on('selectNode', function(obj) {
					//console.log(myNetwork.body.data.nodes._data[obj.nodes[0]])
					if (!obj.event.srcEvent.ctrlKey) {
						var payloadForAristForm = {
							"action": "getFullProfile",
							"artist_profile_id": obj.nodes[0]
						};
						//if (myNetwork.body.data.nodes._data[obj.nodes[0]].is_artist) {
						$("#mySidenav").show();
						getUserProfile(payloadForAristForm);
						//}
						expandNode(obj.nodes[0])
						//console.log(myNetwork.body.data.nodes._data)
						myNetwork.body.data.nodes._data[obj.nodes[0]].shape = selected_shape_image
						myNetwork.body.data.nodes._data[selected].shape = selected_shape_image
						myNetwork.releaseNode()
					}
				});
			}

		}

		function filterClose() {
			document.getElementById("FilterWindow").style.display = "none";
			$("#filter_div,.topFilterClass,#relation_check,.searchTextClass, #network_container, #small-6 column, #topFilter, #topFilter_text, #navbar, .small-7, .small-5, .footer").removeClass("disabledbutton");
			document.getElementById("search_popup").disabled = false;
			document.getElementById("invalidMessage").style.display = "none";
			var nodePos = {
				position: {
					x: 0,
					y: 0
				},
				scale: 0.6,
				offset: {
					x: 0,
					y: 0
				},
				animation: {
					duration: 1000,
					easingFunction: "easeInOutQuad"
				}
			}
			lineage_network.vis_net.moveTo(nodePos);
		}

		/**
		 * draw default network
		 * @param showResult
		 */
		function drawDefaultNetwork(showResult) {
			console.log(PROFILE_ID);
			if (PROFILE_ID !== undefined) {
				searchAndDraw({
					action: "centerSearchById",
					"artist_profile_id": [PROFILE_ID]
				}, lineage_network, function(result, mainNodeCount) {
					for (var item of result) {
						if (item.artist_profile_id == PROFILE_ID) {
							originalText = 'Hello ' + '<span style="font-weight:bold">' + item.artist_first_name + '</span>, ' + '<span style="font-weight:bold">' + (lineage_network.nodes.size - parseInt(mainNodeCount[0].nodeCount)) + '</span>' + " artist(s) are currently related to you:"
							$('#search_text').html(originalText);
							break;
						}
					}
				});
				//HERE
			} else {
				searchAndDraw({
					action: "centerSearchById",
					"artist_profile_id": ["534", "209", "102", "504"]
				}, lineage_network, showResult);
				originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
				$('#search_text').html(originalText);
			}
		}

		function isEmpty(data) {
			var allEmpty = true;
			if (typeof data.artist_profile_id !== 'undefined' && data.artist_profile_id.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_gender !== 'undefined' && data.artist_gender.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_genre !== 'undefined' && data.artist_genre.length != 0) {
				allEmpty = false
			}
			if (typeof data.genre !== 'undefined' && data.genre.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_residence_country !== 'undefined' && data.artist_residence_country.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_residence_state !== 'undefined' && data.artist_residence_state.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_residence_city !== 'undefined' && data.artist_residence_city.length != 0) {
				allEmpty = false
			}
			if (typeof data.artist_ethnicity !== 'undefined' && data.artist_ethnicity.length != 0) {
				allEmpty = false
			}
			return allEmpty;
		}

		/**
		 * args {
		 *			"action": "filterSearchForALL",
		 *			"artist_profile_id": [],
		 *			"artist_gender": [],
		 *			"artist_genre": [],
		 *			"genre": [],
		 *			"artist_residence_city": [],
		 *			"artist_residence_state": [],
		 *			"artist_ethnicity": [],
		 *			"artist_residence_country": []
		 *		}
		 * args send to server
		 * @param args
		 * @param network
		 * @param showResult
		 * @author Sai Cao
		 */
		function searchAndDraw(args, network, showResult) {
			var empty = isEmpty(args);
			var loginSearch = false;
			if (args.action == 'centerSearchById' && args.length == 1 && args.artist_profile_id[0] === PROFILE_ID) {
				loginSearch = true;
			}
			console.log(empty);
			var json_args = JSON.stringify(args);
			console.log("Searching " + json_args);
			var nonFound = true;
			document.getElementById(network.conatiner_id).style.display = "none";
			var loading_img = document.getElementById("spin_loading");
			loading_img.style.display = 'inline-block';

			var start = Date.now();
			console.log(start);
			$.ajax({
				type: "POST",
				url: "./artistcontroller.php",
				data: json_args,
				success: function(response) {

					console.log(response);
					loading_img.style.display = 'none';
					// document.getElementById(network.conatiner_id).style.display = "inline-block";
					document.getElementById("loadingBar").style.display = "inline-block";
					network.vis_net.on("stabilizationProgress", function(params) {
						// console.log(params.total);

						// console.log(document.getElementById("loadingBar").style.display)
						var maxWidth = 446;
						var minWidth = 20;
						var widthFactor = params.iterations / params.total;
						var width = Math.max(minWidth, maxWidth * widthFactor);

						var x = window.innerWidth / window.screen.width;

						document.getElementById("progress_bar").style.width = x * width + "px";
						document.getElementById("progress_text").innerText =
							Math.round(widthFactor * 100) + "%";
					});


					network.vis_net.on('stabilizationIterationsDone', function() {
						let net = this;
						setTimeout(function() {
							document.getElementById("loadingBar").style.display = "none";
							document.getElementById(network.conatiner_id).style.display = "inline-block";
							net.stopSimulation();
							//console.log(PROFILE_ID);
							if (typeof PROFILE_ID != 'undefined' && !empty && loginSearch) {
								var {
									x: nodeX,
									y: nodeY
								} = lineage_network.vis_net.getPositions(PROFILE_ID)[PROFILE_ID];
								var nodePos = {
									position: {
										x: nodeX,
										y: nodeY
									},
									scale: 0.8,
									offset: {
										x: 0,
										y: 0
									},
									animation: {
										duration: 1000,
										easingFunction: "easeInOutQuad"
									}

								}
								lineage_network.vis_net.moveTo(nodePos);

							} else {
								net.fit();
								var nodePos = {
									position: {
										x: 0,
										y: 0
									},
									scale: 0.5,
									offset: {
										x: 0,
										y: 0
									},
									animation: {
										duration: 1000,
										easingFunction: "easeInOutQuad"
									}

								}
								lineage_network.vis_net.moveTo(nodePos);
							}
						}, 100);
					});


					document.getElementById("progress_bar").style.width = 0 + "px";
					document.getElementById("progress_text").innerText =
						0 + "%";
					network.setDataFromArray(response["result"]);
					showResult(response["result"], response["mainNodeCount"]);
					network.draw();
				},
				error: function(xhr, status, err) {
					console.log(xhr.responseText);
				},
				dataType: "json",
				contentType: "application/json"
			});
		};










		function searchAndReturn(args, network, showResult) {

			var empty = isEmpty(args);
			var loginSearch = false;
			if (args.action == 'centerSearchById' && args.length == 1 && args.artist_profile_id[0] === PROFILE_ID) {
				loginSearch = true;
			}
			console.log(empty);
			var json_args = JSON.stringify(args);
			console.log("Searching " + json_args);
			var nonFound = true;
			document.getElementById(network.conatiner_id).style.display = "none";
			var loading_img = document.getElementById("spin_loading");
			loading_img.style.display = 'inline-block';

			var start = Date.now();
			console.log(start);
			$.ajax({
				type: "POST",
				url: "./artistcontroller.php",
				data: json_args,
				success: function(response) {

					console.log(response);
					loading_img.style.display = 'none';
					// document.getElementById(network.conatiner_id).style.display = "inline-block";
					document.getElementById("loadingBar").style.display = "inline-block";
					network.vis_net.on("stabilizationProgress", function(params) {
						// console.log(params.total);

						// console.log(document.getElementById("loadingBar").style.display)
						var maxWidth = 446;
						var minWidth = 20;
						var widthFactor = params.iterations / params.total;
						var width = Math.max(minWidth, maxWidth * widthFactor);

						var x = window.innerWidth / window.screen.width;

						document.getElementById("progress_bar").style.width = x * width + "px";
						document.getElementById("progress_text").innerText =
							Math.round(widthFactor * 100) + "%";
					});

					network.vis_net.on('stabilizationIterationsDone', function() {
						let net = this;
						setTimeout(function() {
							document.getElementById("loadingBar").style.display = "none";
							document.getElementById(network.conatiner_id).style.display = "inline-block";
							net.stopSimulation();
							//console.log(PROFILE_ID);
							if (typeof PROFILE_ID != 'undefined' && !empty && loginSearch) {
								var {
									x: nodeX,
									y: nodeY
								} = lineage_network.vis_net.getPositions(PROFILE_ID)[PROFILE_ID];
								var nodePos = {
									position: {
										x: nodeX,
										y: nodeY
									},
									scale: 0.8,
									offset: {
										x: 0,
										y: 0
									},
									animation: {
										duration: 1000,
										easingFunction: "easeInOutQuad"
									}

								}
								lineage_network.vis_net.moveTo(nodePos);
							} else {
								net.fit();
							}
						}, 100);
					});


					// const first_names = []
					// const last_names = []
					// for (const element of network.nodes.values()) {
					//     console.log(element["artist_first_name"]);
					//     first_names.push(element["artist_first_name"]);
					//     last_names.push(element["artist_last_name"]);
					// };

					// console.log(first_names);
					// console.log(last_names);
					// console.log(originalText);


					document.getElementById("progress_bar").style.width = 0 + "px";
					document.getElementById("progress_text").innerText =
						0 + "%";
					network.setDataFromArray(response["result"]);
					showResult(response["result"], response["mainNodeCount"]);
					network.draw();
				},
				error: function(xhr, status, err) {
					console.log(xhr.responseText);
				},
				dataType: "json",
				contentType: "application/json"
			});
		};





		/**
		 * create box to show relationship  when hovered on edge
		 * @param id id of node
		 * @param self binding with vis_net
		 * @returns {HTMLDivElement}
		 */
		function htmlTitle(id, self) {
			let edge = self.edges.get(id)
			var from = self.nodes.get(edge["artist_profile_id_1"]).artist_first_name;
			var to = self.nodes.get(edge["artist_profile_id_2"]).artist_first_name;
			const container = document.createElement("div");
			container.innerHTML = from + " " + edge["artist_relation"] + " " + to;
			return container;
		}
		/**
		 * @param {*} relations_arr 
		 * @param {*} node 
		 * @author Sai Cao
		 */
		function loadAddToMyNetworkPopUPData(relations_arr, node) {


			$('#studied').prop('checked', false);
			$('#danced').prop('checked', false);
			$('#collaborated').prop('checked', false);
			$('#influenced').prop('checked', false);
			let title = $("#AddRelationPerson div").eq(0);
			title.html("Add " + node["artist_first_name"] + " to my network");


			let eventButton = $("#add_relation_button");
			eventButton.off();
			console.log(relations_arr);


			for (let i = 0; i < relations_arr.length; i++) {
				let relation = relations_arr[i]
				switch (relation["artist_relation"]) {
					case "Studied Under":
						$('#studied').prop('checked', true);
						break
					case "Danced in the Work of":
						$('#danced').prop('checked', true);
						console.log("js on");
						$('#danced_options').show();
						$('#danced_titles').val(relation["works"]);
						break
					case "Collaborated With":
						$('#collaborated').prop('checked', true);
						break
					case "Influenced By":
						$('#influenced').prop('checked', true);
						break
				}
			}

			eventButton.on("click", function() {
				addRelationToArtistBy(node["artist_profile_id"]);
			});
			$("#spin_loading_relation").hide();
			$("#AddRelationWindow_content").show();
		}

		/**
		 * event to show add relation pop up
		 * @param node
		 * @constructor
		 */
		function ShowAddRelationPopUp(node) {
			$("#AddRelationWindow").show();
			$("#spin_loading_relation").show();

			let sending_json = {
				action: "getLoginRelatedArtistWithId",
				artist_profile_id: node["artist_profile_id"]
			};

			$.ajax({
				type: "POST",
				url: "artistrelationcontroller.php",
				data: JSON.stringify(sending_json),
				success: function(data) {
					console.log(data);
					switch (data['Exception']) {
						case 100:
							alert("Please Login to add this artist");
							window.location.href = './login.php'
							break;
						case 300:
							alert("You can not add yourself! ");
							closeAddRelationPopUp();
							break;
						case undefined:
							loadAddToMyNetworkPopUPData(data["result"], node);
							break;
						case 200:
							alert("Please add your profile!");
							break;

						default:
							console.log(data);
							alert("error, please report this bug!");
							break;
					}
				},
				error: function(xhr, status, err) {
					console.log(xhr.responseText);
				},
				dataType: "json",
				contentType: "application/json"
			});

		}

		/**
		 * add relation to login users from network
		 * @param nid
		 * @author Sai Cao
		 */
		function addRelationToArtistBy(nid) {
			console.log("add nid");
			console.log(nid);



			var relations_arr = []
			if ($('#studied').prop('checked')) {
				relations_arr.push({
					artist_relation: "Studied Under",
					works: null
				});
			}

			if ($('#danced').prop('checked')) {
				relations_arr.push({
					artist_relation: "Danced in the Work of",
					works: $('#danced_titles').val()
				});
			}
			if ($('#collaborated').prop('checked')) {
				relations_arr.push({
					artist_relation: "Collaborated With",
					works: null
				});
			}
			if ($('#influenced').prop('checked')) {
				relations_arr.push({
					artist_relation: "Influenced By",
					works: null
				});
			}


			let sending_json = JSON.stringify({
				action: "addEditArtistRelationById",
				relations: relations_arr,
				artist_profile_id: nid
			});
			console.log(sending_json);
			$.ajax({
				type: "POST",
				url: "artistrelationcontroller.php",
				data: sending_json,
				success: function(data) {
					switch (data['Exception']) {
						case 100:
							alert("Please Login to add this artist");
							window.location.href = './login.php'
							break;
						case undefined:
							closeAddRelationPopUp();
							drawDefaultNetwork(function() {
								return undefined;
							});

							$("#AddRelationWindow").hide();
						default:
							console.log(data['Exception']);
					}
				},
				error: function(xhr, status, err) {
					console.log(xhr.responseText);
				},
				dataType: "json",
				contentType: "application/json"
			});
		}

		function closeAddRelationPopUp() {
			$("#AddRelationWindow").hide();
		}

		/**
		 * close context menu of network
		 * @param e
		 */
		function closeNodeMenuEvent(e) {
			// If the clicked element is not the menu
			if (!$(e.target).parents(".custom-menu").length > 0) {
				$(".custom-menu").hide();
			}
		}

		/**
		 * Draw network and apply applyFilters and manage events of network
		 * @author Sai Cao
		 *  */
		class LineageNetwork {
			constructor(conatiner_id, options) {
				this.vis_nodes = new vis.DataSet();
				this.vis_edges = new vis.DataSet();
				this.nodes = new Map();
				this.edges = new Map();
				this.VE = new Map();
				this.conatiner_id = conatiner_id;
				this.options = options;
				this.container = document.getElementById(conatiner_id);
				this.mode = 'cache';
				this.n_cache = 0;

				this.vis_net = new vis.Network(this.container, {
					nodes: this.vis_nodes,
					edges: this.vis_edges
				}, options);
				this.colorMap = new Map();
				this.initColor();

				var self = this
				this.vis_net.on('selectNode', function(obj) {
					self.leftClickEvent(obj);

				});
				this.vis_net.on("oncontext", function(Object) {
					self.rightMeunEvent(Object);
				});
				this.vis_net.on("hold", function(Object) {
					self.rightMeunEvent(Object);
				});

			}

			/**
			 * Event for user hover on edge show relationship
			 * @param obj {Object} Pointer Event network
			 * @author Sai Cao
			 */
			hoverEdgeEvent(obj) {
				var rel = this.edges.get(obj.edge);
				$("#" + this.conatiner_id).css("cursor", "pointer");
				// console.log(rel);
				var relationship = rel["artist_relation"];
				var from = this.nodes.get(rel["artist_profile_id_1"])["artist_first_name"];
				var to = this.nodes.get(rel["artist_profile_id_2"])["artist_first_name"];
				$("#my_network").attr('title', from + " " + relationship.toLowerCase() + " " + to);
			}
			/**
			 * Left click event for node
			 * @param obj {Object} Pointer Event network
			 * @author Sai Cao
			 */
			leftClickEvent(obj) {
				//console.log(obj.nodes);
				var {
					x: nodeX,
					y: nodeY
				} = this.vis_net.getPositions(obj.nodes[0])[obj.nodes[0]];
				var nodePos = {
					position: {
						x: nodeX,
						y: nodeY
					},
					scale: 0.6,
					offset: {
						x: 0,
						y: 0
					},
					animation: {
						duration: 1000,
						easingFunction: "easeInOutQuad"
					}
				}
				this.vis_net.moveTo(nodePos);

				var payloadForAristForm = {
					"action": "getFullProfile",
					"artist_profile_id": obj.nodes[0]
				};
				$("#mySidenav").show();
				getUserProfile(payloadForAristForm);
			}

			/**
			 * Event to bring new artist for networks
			 * @param args
			 */
			searchAndAddArtistEvent(args) {
				var json_args = JSON.stringify(args);
				console.log("Searching " + args);
				var nonFound = true;
				document.getElementById(this.conatiner_id).style.display = "none";
				var loading_img = document.getElementById("spin_loading");
				loading_img.style.display = '';
				var self = this;
				$.ajax({
					type: "POST",
					url: "./artistcontroller.php",
					data: json_args,
					success: function(response) {
						console.log(response);
						loading_img.style.display = 'none';
						document.getElementById(self.conatiner_id).style.display = "";
						self.addDataFromArray(response["result"]);

					},
					error: function(xhr, status, err) {
						console.log(xhr.responseText);
					},
					dataType: "json",
					contentType: "application/json"
				});
			}


			

			rightMeunEvent(Object) {
				Object.event.preventDefault();
				$(".custom-menu").hide();
				$("#mySidenav").hide();
				var self = this;
				var selected = this.vis_net.getNodeAt(Object.pointer.DOM);
				var menu = undefined;
				if (selected !== undefined) {
					let node = this.nodes.get(selected);

					menu = "#network_node_menu";
					let add_relation = $("#network_node_menu li").eq(0);
					add_relation.html("Show related artists of " + node["artist_first_name"]);
					add_relation.off();
					document.getElementById('searchRelation').onclick = function(obj) {
						console.log("click add relations");
						$(".custom-menu").hide();
						self.showRelationships(selected);
						self.searchAndAddArtistEvent({
							action: "centerSearchById",
							"artist_profile_id": [selected]
						});

					};

					let hide_item = $("#network_node_menu li").eq(1)


					hide_item.html("Hide related artists of " + node["artist_first_name"]);

					hide_item.off();
					hide_item.on("click", function() {
						$(".custom-menu").hide();
						self.hideRelationships(selected);
					});
					let event1 = $("#network_node_menu li").eq(2);
					event1.html("Show Events");
					event1.off();
					event1.on("click", function() {
						$('#EventPopUp').show();
						$('#eventTable').hide();
						$('#spin_loading_event').show();

						$(".custom-menu").hide();
						loadArtistEvent(node);
					});
					let family_tree_menu = $("#network_node_menu li").eq(4);
					family_tree_menu.html("Show Family Lineage");
					family_tree_menu.on("click", function() {
						var payloadForAristForm = {
							"action": "getFullProfile",
							"artist_profile_id": selected
						};

						console.log(family_tree_menu);
						console.log(typeof(family_tree_menu));
						family_tree_menu = "";
						getFamilyProfile(payloadForAristForm);
						$('#familytree').click();

					});
					let addnewrelation = $("#network_node_menu li").eq(3);
					addnewrelation.html("Add " + node["artist_first_name"] + " to my network");
					addnewrelation.off();
					$("#AddRelationWindow_content").hide();
					document.getElementById('AddRelation').onclick = function(obj) {
						console.log("click add new relations");
						ShowAddRelationPopUp(node);
						$(".custom-menu").hide();
					};

				} else {
					console.log("click empty")
					$(".custom-menu").hide();
					return
				}

				$(menu).finish().toggle();
				$(menu).css({
					top: Object.event.offsetY + "px",
					left: Object.event.offsetX + "px",
				});


				$(document).one("click", closeNodeMenuEvent);
				$(".custom-menu").show();
			}

			/**
			 * Hide Relationships for artist
			 * @param select
			 * @author Sai Cao
			 */
			hideRelationships(select) {
				console.log(select);
				let neighbors = this.vis_net.getConnectedNodes(select, 'to');
				console.log(neighbors);
				for (let i = 0; i < neighbors.length; i++) {
					let id = neighbors[i]
					if (this.vis_net.getConnectedNodes(id).length < 2) {
						this.hiddenNode(id);
					}
				}
			}
			/**
			 * Hide Relationships for artist
			 * @param select
			 * @author Sai Cao
			 */
			showRelationships(select) {
				let neighbors = this.vis_net.getConnectedNodes(select, 'to');

				for (let i = 0; i < neighbors.length; i++) {

					this.showNode(neighbors[i]);
				}
			}

			/**
			 * not used
			 */
			initColor() {
				this.colorMap.set("Studied Under", "#7FFF00");
				this.colorMap.set("Danced in the Work of", "#00FFFF");
				this.colorMap.set("Influenced By", "#FFFF00");
				this.colorMap.set("Collaborated With", "#708090");
			}

			/**
			 * parse query result to node add to network
			 * @param node
			 * @author Sai Cao
			 */
			addNode(node) {
				let isArtistNodeColor = "#1A3263";
				let notArtistNodeColor = "#FFFFFF";
				let img = node["artist_photo_path"];
				let col = "#FFFFFF"
				if (img == "") {
					if (node['is_user_artist'] == "artist") {
						col = isArtistNodeColor
						img = "img/profileNoPic.png";
					} else {
						col = notArtistNodeColor
						img = "img/noProfile.png";
					}
				}

				var vis_node = {

					id: node["artist_profile_id"],
					label: node["artist_first_name"] + " " + node["artist_last_name"],
					image: img,
					color: col
				};
				if (this.mode == 'cache') {
					vis_node.x = node.x;
					vis_node.y = node.y;
				}
				this.vis_nodes.update(vis_node);
			}

			/**
			 * convert node data to network edge data structure
			 * @param edge
			 * @author
			 */
			addEdge(edge) {

				var vis_edge = {
					id: edge["relation_id"],
					from: edge["artist_profile_id_1"],
					to: edge["artist_profile_id_2"],
					label: edge["artist_relation"] + " " + edge["relation_id"] + " " + edge["artist_profile_id_1"] + " " + edge["artist_profile_id_2"],
					// color: this.colorMap.get(edge["artist_relation"]),
					title: htmlTitle(edge["relation_id"], this)
				};
				this.vis_edges.update(vis_edge);
			}

			/**
			 *
			 */
			draw() {
				var n_total = 1000;
				var cached = this.n_cache;
				console.log(this.n_cache);
				this.vis_net.setOptions({
					physics: {
						stabilization: {
							iterations: Math.max(n_total - cached, 20),
							updateInterval: 10,
						}
					}
				});
				this.vis_net.setData({
					nodes: this.vis_nodes,
					edges: this.vis_edges
				});
				if (this.vis_nodes.length == 0) {
					document.getElementById("loadingBar").style.display = "none";
					document.getElementById(this.conatiner_id).style.display = "inline-block";
					document.getElementById("NoResultWindow")
				}
			}

			/**
			 * Not used
			 * @param i
			 */
			drawIterations(i) {

				this.vis_net.setOptions({
					physics: {
						stabilization: {
							iterations: i,
							updateInterval: 10,
						}
					}
				});
				this.vis_net.setData({
					nodes: this.vis_nodes,
					edges: this.vis_edges
				});
			}

			/**
			 * Not Used
			 * Same with search logic just filter the center nodes
			 * @param nodeConditions
			 * @param edgeConditions
			 */
			applyCenterFilters(nodeConditions, edgeConditions) {
				var result = this.dataFilter(this.nodes, nodeConditions);
				var center = result["inliers"];
				var mates = [];
				let self = this;
				for (var i = 0; i < center.length; i++) {
					this.edges.forEach(function(value, key) {
						var mate = self.getMate(center[i], value);
						if (mate !== undefined && self.nodes.get(mate) !== undefined) {

							mates.push(self.nodes.get(mate));
						}

					});
				}

				this.vis_nodes.clear();

				for (var i = 0; i < center.length; i++) {
					this.addNode(center[i]);
				}
				for (var i = 0; i < mates.length; i++) {
					this.addNode(mates[i]);
				}
				var edges = this.dataFilter(this.edges, edgeConditions);
				this.draw();
			}

			/**
			 * Hide the node by id
			 * @param id id of node
			 */
			hiddenNode(id) {

				if (this.vis_nodes.get(id)) {
					this.vis_nodes.update({
						id: id,
						hidden: true
					});
				}
			}
			/**
			 * Show the node by id
			 * @param id id of node
			 */
			showNode(id) {
				// console.log(id);
				if (this.vis_nodes.get(id)) {
					this.vis_nodes.update({
						id: id,
						hidden: false
					});
				}
			}

			/**
			 * show the edge by id
			 * @param id
			 */
			showEdge(id) {

				if (this.vis_edges.get(id)) {
					this.vis_edges.update({
						id: id,
						hidden: false
					});
				}
			}

			/**
			 * hide edge by id
			 * @param id
			 */
			hiddenEdge(id) {

				if (this.vis_edges.get(id)) {
					this.vis_edges.update({
						id: id,
						hidden: true
					});
				}
			}

			/**
			 * Filter the nodes in network by following condition
			 * same categories combine with OR
			 * different categories combined with AND
			 * { "artist_genre":[]
			 * "genre": [],
			 * "artist_residence_city":[]
			 * "artist_residence_state":[]
			 * "artist_residence_country":[]
			 * }
			 * @param {Object} nodeConditions
			 * @returns {number} number of nodes in network after filtered
			 */
			applyNodeFilters(nodeConditions) {
				console.log(nodeConditions);

				var result = this.dataFilter(this.nodes, nodeConditions);


				for (var i = 0; i < result["outliers"].length; i++) {
					this.hiddenNode(result["outliers"][i]["artist_profile_id"]);
				}
				for (var i = 0; i < result["inliers"].length; i++) {
					this.showNode(result["inliers"][i]["artist_profile_id"]);
				}
				document.getElementById("spin_loading").style.display = 'none';
				document.getElementById(lineage_network.conatiner_id).style.display = "inline-block";
				return result["inliers"].length
			}


			
			applyEdgeFilters(edgeConditions) {
				var result = this.dataFilter(this.edges, edgeConditions);
				for (var i = 0; i < result["outliers"].length; i++) {
					this.hiddenEdge(result["outliers"][i]["relation_id"]);
				}
				for (var i = 0; i < result["inliers"].length; i++) {
					this.showEdge(result["inliers"][i]["relation_id"]);
				}
				return result["inliers"].length;
			}


			
			applyAllFilters(nodeConditions, edgeConditions) {
				var result = this.dataFilter(this.nodes, nodeConditions);
				var all_node = result["inliers"];
				for (var i = 0; i < all_node.length; i++) {
					this.addNode(all_node[i]);
				}
				var result = this.dataFilter(this.nodes, edgeConditions);
				this.draw();
			}


			
			getMate(node, edge) {
				if (node["artist_profile_id"] == edge["artist_profile_id_1"]) {
					return edge["artist_profile_id_2"];
				}
				if (node["artist_profile_id"] == edge["artist_profile_id_2"]) {
					return edge["artist_profile_id_1"];
				}
				return undefined;
			}

			
			dataFilter(data, conditions) {
				var result = {
					inliers: [],
					outliers: []
				}

				let self = this;
				data.forEach(function(value, key) {

					if (self.chcekConditions(conditions, value)) {

						result["inliers"].push(value);
					} else {
						result["outliers"].push(value);
					}
				});
				return result;
			}
			checkConditionEqual(values, value) {
				if (values.length == 0) {
					return true;
				}
				for (var i = 0; i < values.length; i++) {

					if (values[i] == value) {
						return true;
					}
				}
				return false;
			}

			checkConditionStringMatch(values, value) {
				if (values.length == 0) {
					return true;
				}
				for (var i = 0; i < values.length; i++) {

					if (value.toLowerCase().includes(values[i].toLowerCase())) {
						return true;
					}
				}
				return false;
			}

			checkConditionArrayMatch(values, value) {
				if (values.length == 0) {
					return true;
				}
				for (var i = 0; i < values.length; i++) {
					if (value.includes(parseInt(values[i]))) {
						return true;
					}
				}
				return false;

			}


			/**
			 * check item is satisfy the condition
			 * @param conditions
			 * @param value
			 * @returns {boolean}
			 */
			chcekConditions(conditions, value) {
				for (var key in conditions) {
					var values = conditions[key];
					if (key == "institution_name") {

						if (!this.checkConditionStringMatch(values, value[key])) {
							return false;
						}
					} else if (key == "genre") {

						if (!this.checkConditionArrayMatch(values, value[key])) {
							return false;
						}
					} else if (key == "artist_genre") {
						if (!this.checkConditionStringMatch(values, value[key])) {
							return false;
						}
					} else {
						if (!this.checkConditionStringMatch(values, value[key])) {
							return false;
						}
					}
				}
				return true;
			}

			/**
			 * set data for network
			 * it will redraw the network
			 * @param {[Object]} r_arr Decode by JSON from query result.
			 */
			setDataFromArray(r_arr) {
				this.nodes.clear();
				this.edges.clear();
				this.vis_nodes = new vis.DataSet();
				this.vis_edges = new vis.DataSet();
				this.vis_net.setData({
					node: new vis.DataSet(),
					edges: new vis.DataSet()
				});
				this.vis_edges.clear();
				this.vis_nodes.clear();
				this.n_cache = 0;
				this.addDataFromArray(r_arr);
			}

			/**
			 *Not used
			 * @param edge
			 */
			addNeighbor(edge) {
				var nid = edge["artist_profile_id_1"];
				if (this.nodes.has(nid)) {
					if (this.VE.has(nid)) {
						this.VE.get(nid).add(edge["relation_id"]);
					} else {
						var s = new Set();
						s.add(edge["relation_id"]);
						this.VE.set(nid, s);

					}
				}


				var nid = edge["artist_profile_id_2"];
				if (this.nodes.has(nid)) {
					if (this.VE.has(nid)) {
						this.VE.get(nid).add(edge["relation_id"]);
					} else {
						var s = new Set();
						s.add(edge["relation_id"]);
						this.VE.set(nid, s);
					}
				}
			}

			/**
			 * Convert genre string to array
			 * @param genre string contain artists genre
			 * @returns {*|[]|*[]} array of genre
			 * @author Sai Cao
			 */
			parseGenre(genre) {
				// console.log(genre);
				// if (genre.charAt(0)==','){
				// 	genre = genre.substring(1);
				// }
				var arr = [];
				try {
					arr = JSON.parse('[' + genre + ']')
				} catch (e) {
					return [];
				}
				return arr;
			}

			/**
			 *add data to network this will redraw the network
			 */
			addDataFromArray(r_arr) {

				for (var i = 0; i < r_arr.length; i++) {

					let relation = r_arr[i]
					let node = {}
					if (relation["nid"] != undefined) {
						node["artist_profile_id"] = relation["nid"];
						node["artist_first_name"] = relation["artist_first_name"];
						node["artist_last_name"] = relation["artist_last_name"];
						node["artist_genre"] = relation["artist_genre"];
						node["institution_name"] = relation["institution_name"];
						node["is_user_artist"] = relation["is_user_artist"];
						node["artist_gender"] = relation["artist_gender"];
						node["genre"] = this.parseGenre(relation["genre"]);
						node["artist_residence_city"] = relation["artist_residence_city"];
						node["artist_residence_country"] = relation["artist_residence_country"];
						node["artist_residence_state"] = relation["artist_residence_state"];
						node["artist_photo_path"] = relation["artist_photo_path"];
						node["artist_email_address"] = relation["artist_email_address"];
						node["show_neighbor"] = true;
						if (!this.nodes.has(relation["nid"])) {
							if (relation["x"] != null) {
								node["x"] = relation["x"];
								node["y"] = relation["y"];
								this.n_cache = this.n_cache + 1;
							}
							this.nodes.set(node["artist_profile_id"], node);
							this.addNode(node);
						}
					}
					if (relation["relation_id"] != undefined) {
						let edge = {}
						edge["relation_id"] = relation["relation_id"];

						edge["artist_relation"] = relation["artist_relation"];
						edge["artist_profile_id_1"] = relation["artist_profile_id_1"];
						edge["artist_profile_id_2"] = relation["artist_profile_id_2"];

						if (this.nodes.get(edge["artist_profile_id_1"]) !== undefined && this.nodes.get(edge["artist_profile_id_2"]) !== undefined) {
							this.edges.set(edge["relation_id"], edge);
							this.addEdge(edge);
						}
					}
				}

			}
		}


		const artist_ids = []
		const first_names = []
		const last_names = []
		for (const element of lineage_network.nodes.values()) {
			artist_ids.push(element["artist_profile_id"]);
			first_names.push(element["artist_first_name"]);
			last_names.push(element["artist_last_name"]);


			const name = "artist_" + element["artist_profile_id"] + "_click";
			const fn = {
				[name]() {
					console.log("");
				}
			} [name];

			console.log(name);

		};
	</script>





























	<script>
		function searchAllArtists() {

			if ($(window).width() < 1024) {
				$("#filter_close_button").click();
			};

			$("#mySidenav").hide();
			$("#network_display_div").hide();
			$("#artistlist_display_div").show();
			$("#familytree_display_div").hide();
			$("#network_div").css({
				"height": "75%"
			});

			console.log(allartistnames);
			const artist_ids = []
			const first_names = []
			const last_names = []
			for (const element of allartistnames.values()) {
				artist_ids.push(element["artist_profile_id"]);
				first_names.push(element["artist_first_name"]);
				last_names.push(element["artist_last_name"]);

			};

			$(document).on('click', 'a', function() {
				var z = this.id.replace("letter_", "");
				var i, len;
				var my_html = '';
				for (i = 0, len = first_names.length; i < len; i++) {
					if (first_names[i].charAt(0) == z) {
						var name = first_names[i] + ' ' + last_names[i]
						my_html += '<a id="artist_' + first_names[i] + ' ' + last_names[i] + '" onclick="artistClick(\'' + name + '\')">' + '&nbsp;' + first_names[i] + '&nbsp;' + last_names[i] + '</a><br/>';

					}
				};
				document.getElementById('my_artists').innerHTML = my_html;
			});

			var html = '<div>&emsp;&nbsp;Name Starting With: <ul>';
			var c;
			for (var i = 65; 90 >= i; i++) {
				c = String.fromCharCode(i);
				html += '<ul style="float: left; padding: 0; margin: 0;"><a id="letter_' + c + '">' + '&nbsp;' + c + '&nbsp;</a></ul>';
			}
			html += '</ul></div>';
			document.getElementById('artist_alphabets').innerHTML = html;
		}
	</script>











	</div>





	<div class="footer">
		<?php
		include 'footer.php';
		?>
	</div>












	<!-- UserPopup Code -->

	<script>
		var urlVal = '';
		var docVal = '';

		var add_first_name_lineage = "<button onclick='displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
		var remove_first_name_lineage = "<button onclick='hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";

		var su_add_first_name_lineage = "<button onclick='su_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
		var su_remove_first_name_lineage = "<button onclick='su_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";

		var di_add_first_name_lineage = "<button onclick='di_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
		var di_remove_first_name_lineage = "<button onclick='di_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";

		var ib_add_first_name_lineage = "<button onclick='ib_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
		var ib_remove_first_name_lineage = "<button onclick='ib_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";

		var cw_add_first_name_lineage = "<button onclick='cw_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name's Lineage: </button>";
		var cw_remove_first_name_lineage = "<button onclick='cw_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name's Lineage: </button>";


		var add_rev_first_name_lineage = "<button onclick='displayRevFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> First Name is listed in the following Artists' Lineage: </button>";
		var remove_rev_first_name_lineage = "<button onclick='hideRevdisplayFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> First Name is listed in the following Artists' Lineage: </button>";
		const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


		// TODO : this is broken.  It is called from the biography popup, but doesn't load the artist bio.
		function openBioPopUp() {

			console.log('urlVal = ' + urlVal);
			console.log('docVal = ' + docVal);

			if (docVal != "") {
				window.open(docVal);
			} else {
				if (urlVal.startsWith("http") || urlVal.startsWith("https") || urlVal.startsWith("ftp")) {
					window.open(urlVal, '_blank', 'width=400,height=400');
				} else {
					var myWindow = window.open('', '_blank', 'width=400,height=400');
					myWindow.document.write(urlVal);
				}
			}
		}

		function displayFirstNameLinage() {
			$("#div_lineal_lines").show();
			$("#artist_lineage_text").html(remove_first_name_lineage);
		}

		function hideFirstNameLinage() {
			$("#div_lineal_lines").hide();
			$("#artist_lineage_text").html(add_first_name_lineage);
		}




		function su_displayFirstNameLinage() {
			$("#su_div_lineal_lines").show();
			$("#su_artist_lineage_text").html(su_remove_first_name_lineage);
		}

		function su_hideFirstNameLinage() {
			$("#su_div_lineal_lines").hide();
			$("#su_artist_lineage_text").html(su_add_first_name_lineage);
		}

		function di_displayFirstNameLinage() {
			$("#di_div_lineal_lines").show();
			$("#di_artist_lineage_text").html(di_remove_first_name_lineage);
		}

		function di_hideFirstNameLinage() {
			$("#di_div_lineal_lines").hide();
			$("#di_artist_lineage_text").html(di_add_first_name_lineage);
		}

		function ib_displayFirstNameLinage() {
			$("#ib_div_lineal_lines").show();
			$("#ib_artist_lineage_text").html(ib_remove_first_name_lineage);
		}

		function ib_hideFirstNameLinage() {
			$("#ib_div_lineal_lines").hide();
			$("#ib_artist_lineage_text").html(ib_add_first_name_lineage);
		}

		function cw_displayFirstNameLinage() {
			$("#cw_div_lineal_lines").show();
			$("#cw_artist_lineage_text").html(cw_remove_first_name_lineage);
		}

		function cw_hideFirstNameLinage() {
			$("#cw_div_lineal_lines").hide();
			$("#cw_artist_lineage_text").html(cw_add_first_name_lineage);
		}


		function displayRevFirstNameLinage() {
			$("#div_rev_lineal_lines").show();
			$("#rev_artist_lineage_text").html(remove_rev_first_name_lineage);
		}

		function hideRevdisplayFirstNameLinage() {
			$("#div_rev_lineal_lines").hide();
			$("#rev_artist_lineage_text").html(add_rev_first_name_lineage);
		}

		function getUserProfile(artist_profile_data) {

			artist_profile_id = artist_profile_data.artist_profile_id;
			$.ajax({
				url: "artistrelationcontroller.php",
				type: 'POST',
				data: JSON.stringify(artist_profile_data),
				success: function(res) {

					urlVal = res.profileDetails[0]['artist_biography_text'];
					docVal = res.profileDetails[0]['artist_biography'];

					add_first_name_lineage = "<button onclick='displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> " + res.profileDetails[0]['artist_first_name'] + "'s Lineage: </button>";
					remove_first_name_lineage = "<button onclick='hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> " + res.profileDetails[0]['artist_first_name'] + "'s Lineage: </button>";

					su_add_first_name_lineage = "<button onclick='su_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> Studied Under: </button>";
					su_remove_first_name_lineage = "<button onclick='su_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> Studied Under: </button>";

					di_add_first_name_lineage = "<button onclick='di_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> Danced in the Work of: </button>";
					di_remove_first_name_lineage = "<button onclick='di_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> Danced in the Work of: </button>";

					ib_add_first_name_lineage = "<button onclick='ib_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> Influenced By: </button>";
					ib_remove_first_name_lineage = "<button onclick='ib_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> Influenced By: </button>";

					cw_add_first_name_lineage = "<button onclick='cw_displayFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> Collaborated With: </button>";
					cw_remove_first_name_lineage = "<button onclick='cw_hideFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> Collaborated With: </button>";


					add_rev_first_name_lineage = "<button onclick='displayRevFirstNameLinage()' class='tal'><i class='fa fa-plus'></i> " + res.profileDetails[0]['artist_first_name'] + " is listed in the following Artists' Lineage: </button>";
					remove_rev_first_name_lineage = "<button onclick='hideRevdisplayFirstNameLinage()' class='tal'><i class='fa fa-minus'></i> " + res.profileDetails[0]['artist_first_name'] + " is listed in the following Artists' Lineage: </button>";





					// Bio Image Link
					var photo = "./img/profileNoPic.png";
					// if(res.profileDetails[0]['is_user_artist'] !== "artist") photo = "./img/noProfile.png";
					if (res.profileDetails[0]['artist_photo_path'] != "") photo = res.profileDetails[0]['artist_photo_path'];




					// First Name, Last Name and Email
					var code = '' +
						'<div hidden id="linkfirstname">' + res.profileDetails[0]['artist_first_name'] + '</div>' +
						'<div hidden id="linklastname">' + res.profileDetails[0]['artist_last_name'] + '</div>' +
						'<div hidden id="linkemail">' + res.profileDetails[0]['artist_email_address'] + '</div>';




					if (res.profileDetails[0]['artist_website'] !== 'null') {
						code += '<div hidden id="linkwebsite">' + res.profileDetails[0]['artist_website'] + '</div>';
					}



					// Bio Image, Artist First Name, Artist Last Name
					code += '<div id="mySidenav_div" style="overflow: hidden;">' +
						'<div hidden id="bioTextValue">' + res.profileDetails[0]['artist_biography_text'] + '</div>' +
						'<div hidden id="bioDocValue">' + res.profileDetails[0]['artist_biography'] + '</div>' +
						'<div id="artist_name" class="name">' + res.profileDetails[0]['artist_first_name'] + ' ' + res.profileDetails[0]['artist_last_name'] + '</div>';



					for (var i = 0; i < admin_result.length; i++) {
						if (admin_result[i]["feature_id"] == 14) {
							if (admin_result[i]["feature_enabled"] == 1) {

								code += ' <img class="pic" id="artist_pic" src = "' + photo + '"/>';

							}
						}
					};

					code += '<div class="info"></div>';

					code += '<button class="close-button" style="font-size: 24px; background-color: transparent;" onclick="close_user_popup()">X</button>';









					for (var i = 0; i < admin_result.length; i++) {
						if (admin_result[i]["feature_id"] == 2) {
							if (admin_result[i]["feature_enabled"] == 1) {
								if ((res.profileDetails[0]['artist_yob'] !== '') && (res.profileDetails[0]['artist_yob'] != '0') && (res.profileDetails[0]['artist_yob'] != 0)) {
									var yearOfBirth = new Date(res.profileDetails[0]['artist_yob']);
									code += '<div id="artist_yob" class="yob" ><b> Year of Birth: </b>' + res.profileDetails[0]['artist_yob'] + '</div>';
								}
							}
						}
					};





					for (var i = 0; i < admin_result.length; i++) {
						if (admin_result[i]["feature_id"] == 6) {
							if (admin_result[i]["feature_enabled"] == 1) {
								if (res.profileDetails[0]['genre'] !== '') {
									if (res.profileDetails[0]['user_genres'] && res.profileDetails[0]['user_genres'] != "") {
										res.profileDetails[0]['genre'] += ", " + res.profileDetails[0]['user_genres'];
									}
									code += '<div id="artist_genre" class="genre" ><b> Genre: </b>   ' + res.profileDetails[0]['genre'] + '</div>';
								}
							}
						}
					};








					code += '<b style="float:left;left:0px;">' + res.profileDetails[0]['artist_first_name'] + '\'s Lineage: </b><br/>';



					var su_flag = 0;
					var di_flag = 0;
					var ib_flag = 0;
					var cw_flag = 0;

					res.relations.forEach(function(i) {
						if (i.artist_relation == "Studied Under") {
							su_flag = 1;
						};
						if (i.artist_relation == "Danced in the Work of") {
							di_flag = 1;
						};
						if (i.artist_relation == "Influenced By") {
							ib_flag = 1;
						};
						if (i.artist_relation == "Collaborated With") {
							cw_flag = 1;
						}
					});
					res.added_by_relations.forEach(function(i) {
						if (i.artist_relation == "Studied Under") {
							su_flag = 1;
						};
						if (i.artist_relation == "Danced in the Work of") {
							di_flag = 1;
						};
						if (i.artist_relation == "Influenced By") {
							ib_flag = 1;
						};
						if (i.artist_relation == "Collaborated With") {
							cw_flag = 1;
						}
					});

					if (su_flag == 1) {
						code = code + '<div id="su_artist_lineage_text" class="lineage_table_text mrt10">';
						code = code + su_add_first_name_lineage;
						code = code + '</div>';
						code = code + ' <div class="row" id="su_div_lineal_lines" hidden> ';
						code = code + '   <table id="su_artist_lineals" class="display table_lineage">';
						res.relations.forEach(function(i) {
							if (i.artist_relation == "Studied Under") {
								code = code + '   <tr><td class="large-12 column"><b>&rarr;  </b>' + i.artist_name_2 + '</td></tr>';
							}
						});
						res.added_by_relations.forEach(function(i) {
							if (i.artist_relation == "Studied Under") {
								code = code + '    <tr><td class="large-12 column"><b>&larr;  </b>' + i.artist_name_1 + '</td></tr>';
							}
						});
						code = code + '   </table>';
						code = code + ' </div>';
					}



					if (di_flag == 1) {
						code = code + '<div id="di_artist_lineage_text" class="lineage_table_text mrt10">';
						code = code + di_add_first_name_lineage;
						code = code + '</div>';
						code = code + ' <div class="row" id="di_div_lineal_lines" hidden> ';
						code = code + '   <table id="di_artist_lineals" class="display table_lineage">';
						res.relations.forEach(function(i) {
							if (i.artist_relation == "Danced in the Work of") {
								code = code + '   <tr><td class="large-12 column"><b>&rarr;  </b>' + i.artist_name_2 + '</td></tr>';
							}
						});
						res.added_by_relations.forEach(function(i) {
							if (i.artist_relation == "Danced in the Work of") {
								code = code + '    <tr><td class="large-12 column"><b>&larr;  </b>' + i.artist_name_1 + '</td></tr>';
							}
						});
						code = code + '   </table>';
						code = code + ' </div>';
					}



					if (ib_flag == 1) {
						code = code + '<div id="ib_artist_lineage_text" class="lineage_table_text mrt10">';
						code = code + ib_add_first_name_lineage;
						code = code + '</div>';
						code = code + ' <div class="row" id="ib_div_lineal_lines" hidden> ';
						code = code + '   <table id="ib_artist_lineals" class="display table_lineage">';
						res.relations.forEach(function(i) {
							if (i.artist_relation == "Influenced By") {
								code = code + '   <tr><td class="large-12 column"><b>&rarr;  </b>' + i.artist_name_2 + '</td></tr>';
							}
						});
						res.added_by_relations.forEach(function(i) {
							if (i.artist_relation == "Influenced By") {
								code = code + '    <tr><td class="large-12 column"><b>&larr;  </b>' + i.artist_name_1 + '</td></tr>';
							}
						});
						code = code + '   </table>';
						code = code + ' </div>';
					}



					if (cw_flag == 1) {
						code = code + '<div id="cw_artist_lineage_text" class="lineage_table_text mrt10">';
						code = code + cw_add_first_name_lineage;
						code = code + '</div>';
						code = code + ' <div class="row" id="cw_div_lineal_lines" hidden> ';
						code = code + '   <table id="cw_artist_lineals" class="display table_lineage">';
						res.relations.forEach(function(i) {
							if (i.artist_relation == "Collaborated With") {
								code = code + '   <tr><td class="large-12 column"><b>&rarr;  </b>' + i.artist_name_2 + '</td></tr>';
							}
						});
						res.added_by_relations.forEach(function(i) {
							if (i.artist_relation == "Collaborated With") {
								code = code + '    <tr><td class="large-12 column"><b>&larr;  </b>' + i.artist_name_1 + '</td></tr>';
							}
						});
						code = code + '   </table>';
						code = code + ' </div>';
					}

					code = code + '</div>';





					for (var i = 0; i < admin_result.length; i++) {
						if (admin_result[i]["feature_id"] == 12) {
							if (admin_result[i]["feature_enabled"] == 1) {

								code = code + '<div id="artist_bio_div"> ';

								if ($(window).width() > 1024) {
									code = code + ' <b></br>Biography: </b><a href="javascript:void(0)" onclick="openBioPopUp()" id="artist_biography" class="biography" style="position:relative; top: -30px;">Click here</a>';
								} else {
									code = code + ' <b></br>Biography: </b><a href="javascript:void(0)" onclick="openBioPopUp()" id="artist_biography" class="biography" style="position:relative; top: 0px;">Click here</a>';
								}

							}
						}
					};





					code = code + '</div>';

					code += '<center><div class="links" style="margin-top: -20px;"><br/>'

					var socialDetails = socialMediaResults.filter(function(i) {
						return i.artist_profile_id == artist_profile_id;
					});

					if (socialDetails.length > 0) {
						var socialList = ["Twitter", "LinkedIn", "Facebook", "Tik-Tok", "Instagram"];
						for (var i = 0; i < socialDetails.length; i++) {
							if (socialDetails[i].social_platform != undefined) {
								code += '<a style="display:inline;box-shadow: 0px 0px; padding:10;" href="' + socialDetails[i].url + '"><img class="socialIcon" src="data/icons/' + socialDetails[i].social_platform + '.png" style="width:30"></a>';
							}
						}
					}
					code += '</div></center>';




					if (res.profileDetails[0]['is_user_artist'] !== "artist" && res.relations.length == 0) code = '<div style="padding-top: 20px"><p>This artist does not yet have a profile in Dancestry.</p></div>'

					$(".profile-details-class").css("display", "block");



					if ($(window).width() < 1024) {
						$("#mySidenav").hide();
						$('#prof_space_modal').html(code);
						
						$("#prof_space_modal").dialog({
							modal: true,
							backdrop: 'static',
    						keyboard: false
						});
					} else {
						$('#prof_space').html(code);
					}

					if($('#prof_space_modal').is(':visible')){
						document.getElementById("lineage_opacity").style.height = "100%";
					}

					if($('#prof_space').is(':visible')){
						document.getElementById("lineage_opacity").style.height = "0%";
					}

				}
			});
		}


		function close_user_popup(){
			$("#mySidenav").hide();
			$("#prof_space_modal").dialog("close");
			document.getElementById("lineage_opacity").style.height = "0%";
		}







		function getFamilyProfile(artist_profile_data) {

			artist_profile_id = artist_profile_data.artist_profile_id;

			$.ajax({
				url: "artistrelationcontroller.php",
				type: 'POST',
				data: JSON.stringify(artist_profile_data),
				success: function(res) {


					var ib_u = [];
					var su_u = [];
					var dw_u = [];
					var cw_l = [];

					var ib_d = [];
					var su_d = [];
					var dw_d = [];
					var cw_r = [];

					for (let i = 0; i < res.relations.length; i++) {
						if (res.relations[i].artist_relation == "Influenced By") {
							ib_u.push(res.relations[i].artist_name_2)
						};
						if (res.relations[i].artist_relation == "Studied Under") {
							su_u.push(res.relations[i].artist_name_2)
						};
						if (res.relations[i].artist_relation == "Danced in the Work of") {
							dw_u.push(res.relations[i].artist_name_2)
						};
						if (res.relations[i].artist_relation == "Collaborated With") {
							cw_l.push(res.relations[i].artist_name_2)
						};
					};



					for (let i = 0; i < res.added_by_relations.length; i++) {
						if (res.added_by_relations[i].artist_relation == "Influenced By") {
							ib_d.push(res.added_by_relations[i].artist_name_1)
						};
						if (res.added_by_relations[i].artist_relation == "Studied Under") {
							su_d.push(res.added_by_relations[i].artist_name_1)
						};
						if (res.added_by_relations[i].artist_relation == "Danced in the Work of") {
							dw_d.push(res.added_by_relations[i].artist_name_1)
						};
						if (res.added_by_relations[i].artist_relation == "Collaborated With") {
							cw_r.push(res.added_by_relations[i].artist_name_1)
						};
					};




					nodes = [{
						id: 1,
						label: res.profileDetails[0]['artist_first_name'] + " " + res.profileDetails[0]['artist_last_name'],
						fixed: true,
						x: 0,
						y: 0,
						color: '#8B4513'
					}];


					if (typeof ib_u[0] != 'undefined') {
						nodes.push({
							id: 2,
							label: '',
							fixed: true,
							x: -300,
							y: -300,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof su_u[0] != 'undefined') {
						nodes.push({
							id: 3,
							label: '',
							fixed: true,
							x: 0,
							y: -400,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof dw_u[0] != 'undefined') {
						nodes.push({
							id: 4,
							label: '',
							fixed: true,
							x: 300,
							y: -300,
							color: 'rgba(255,255,255,0)'
						})
					};

					if (typeof ib_d[0] != 'undefined') {
						nodes.push({
							id: 5,
							label: '',
							fixed: true,
							x: -300,
							y: 300,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof su_d[0] != 'undefined') {
						nodes.push({
							id: 6,
							label: '',
							fixed: true,
							x: 0,
							y: 400,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof dw_d[0] != 'undefined') {
						nodes.push({
							id: 7,
							label: '',
							fixed: true,
							x: 300,
							y: 300,
							color: 'rgba(255,255,255,0)'
						})
					};

					if (typeof cw_l[0] != 'undefined') {
						nodes.push({
							id: 8,
							label: '',
							fixed: true,
							x: -400,
							y: 0,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof cw_l[0] != 'undefined') {
						nodes.push({
							id: "8.1",
							label: '',
							fixed: true,
							x: -100,
							y: 0,
							color: 'rgba(255,255,255,0)'
						})
					};

					if (typeof cw_r[0] != 'undefined') {
						nodes.push({
							id: 9,
							label: '',
							fixed: true,
							x: 400,
							y: 0,
							color: 'rgba(255,255,255,0)'
						})
					};
					if (typeof cw_r[0] != 'undefined') {
						nodes.push({
							id: "9.1",
							label: '',
							fixed: true,
							x: 100,
							y: 0,
							color: 'rgba(255,255,255,0)'
						})
					};



					if (typeof ib_u[0] != 'undefined') {
						nodes.push({
							id: 100,
							label: ib_u[0],
							fixed: true,
							x: 600 * Math.cos((130 / 180) * Math.PI),
							y: -500 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[1] != 'undefined') {
						nodes.push({
							id: 101,
							label: ib_u[1],
							fixed: true,
							x: 600 * Math.cos((140 / 180) * Math.PI),
							y: -500 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[2] != 'undefined') {
						nodes.push({
							id: 102,
							label: ib_u[2],
							fixed: true,
							x: 600 * Math.cos((150 / 180) * Math.PI),
							y: -500 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[3] != 'undefined') {
						nodes.push({
							id: 103,
							label: ib_u[3],
							fixed: true,
							x: 800 * Math.cos((130 / 180) * Math.PI),
							y: -700 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[4] != 'undefined') {
						nodes.push({
							id: 104,
							label: ib_u[4],
							fixed: true,
							x: 800 * Math.cos((140 / 180) * Math.PI),
							y: -700 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[5] != 'undefined') {
						nodes.push({
							id: 105,
							label: ib_u[5],
							fixed: true,
							x: 800 * Math.cos((150 / 180) * Math.PI),
							y: -700 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[6] != 'undefined') {
						nodes.push({
							id: 106,
							label: ib_u[6],
							fixed: true,
							x: 1000 * Math.cos((130 / 180) * Math.PI),
							y: -900 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[7] != 'undefined') {
						nodes.push({
							id: 107,
							label: ib_u[7],
							fixed: true,
							x: 1000 * Math.cos((140 / 180) * Math.PI),
							y: -900 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[8] != 'undefined') {
						nodes.push({
							id: 108,
							label: ib_u[8],
							fixed: true,
							x: 1000 * Math.cos((150 / 180) * Math.PI),
							y: -900 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_u[9] != 'undefined') {
						nodes.push({
							id: 109,
							label: ib_u[9],
							fixed: true,
							x: 1000 * Math.cos((160 / 180) * Math.PI),
							y: -900 * Math.sin((160 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};

					if (typeof su_u[0] != 'undefined') {
						nodes.push({
							id: 200,
							label: su_u[0],
							fixed: true,
							x: 600 * Math.cos((70 / 180) * Math.PI),
							y: -500 * Math.sin((70 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[1] != 'undefined') {
						nodes.push({
							id: 201,
							label: su_u[1],
							fixed: true,
							x: 600 * Math.cos((90 / 180) * Math.PI),
							y: -500 * Math.sin((90 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[2] != 'undefined') {
						nodes.push({
							id: 202,
							label: su_u[2],
							fixed: true,
							x: 600 * Math.cos((110 / 180) * Math.PI),
							y: -500 * Math.sin((110 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[3] != 'undefined') {
						nodes.push({
							id: 203,
							label: su_u[3],
							fixed: true,
							x: 700 * Math.cos((80 / 180) * Math.PI),
							y: -600 * Math.sin((80 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[4] != 'undefined') {
						nodes.push({
							id: 204,
							label: su_u[4],
							fixed: true,
							x: 700 * Math.cos((100 / 180) * Math.PI),
							y: -600 * Math.sin((100 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[5] != 'undefined') {
						nodes.push({
							id: 205,
							label: su_u[5],
							fixed: true,
							x: 800 * Math.cos((70 / 180) * Math.PI),
							y: -700 * Math.sin((70 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[6] != 'undefined') {
						nodes.push({
							id: 206,
							label: su_u[6],
							fixed: true,
							x: 800 * Math.cos((90 / 180) * Math.PI),
							y: -700 * Math.sin((90 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[7] != 'undefined') {
						nodes.push({
							id: 207,
							label: su_u[7],
							fixed: true,
							x: 800 * Math.cos((110 / 180) * Math.PI),
							y: -700 * Math.sin((110 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[8] != 'undefined') {
						nodes.push({
							id: 208,
							label: su_u[8],
							fixed: true,
							x: 900 * Math.cos((80 / 180) * Math.PI),
							y: -800 * Math.sin((80 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_u[9] != 'undefined') {
						nodes.push({
							id: 209,
							label: su_u[9],
							fixed: true,
							x: 900 * Math.cos((100 / 180) * Math.PI),
							y: -800 * Math.sin((100 / 180) * Math.PI),
							color: '#34764E'
						})
					};

					if (typeof dw_u[0] != 'undefined') {
						nodes.push({
							id: 300,
							label: dw_u[0],
							fixed: true,
							x: 600 * Math.cos((50 / 180) * Math.PI),
							y: -500 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[1] != 'undefined') {
						nodes.push({
							id: 301,
							label: dw_u[1],
							fixed: true,
							x: 600 * Math.cos((40 / 180) * Math.PI),
							y: -500 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[2] != 'undefined') {
						nodes.push({
							id: 302,
							label: dw_u[2],
							fixed: true,
							x: 600 * Math.cos((30 / 180) * Math.PI),
							y: -500 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[3] != 'undefined') {
						nodes.push({
							id: 303,
							label: dw_u[3],
							fixed: true,
							x: 800 * Math.cos((50 / 180) * Math.PI),
							y: -700 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[4] != 'undefined') {
						nodes.push({
							id: 304,
							label: dw_u[4],
							fixed: true,
							x: 800 * Math.cos((40 / 180) * Math.PI),
							y: -700 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[5] != 'undefined') {
						nodes.push({
							id: 305,
							label: dw_u[5],
							fixed: true,
							x: 800 * Math.cos((30 / 180) * Math.PI),
							y: -700 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[6] != 'undefined') {
						nodes.push({
							id: 306,
							label: dw_u[6],
							fixed: true,
							x: 1000 * Math.cos((50 / 180) * Math.PI),
							y: -900 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[7] != 'undefined') {
						nodes.push({
							id: 307,
							label: dw_u[7],
							fixed: true,
							x: 1000 * Math.cos((40 / 180) * Math.PI),
							y: -900 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[8] != 'undefined') {
						nodes.push({
							id: 308,
							label: dw_u[8],
							fixed: true,
							x: 1000 * Math.cos((30 / 180) * Math.PI),
							y: -900 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_u[9] != 'undefined') {
						nodes.push({
							id: 309,
							label: dw_u[9],
							fixed: true,
							x: 1000 * Math.cos((20 / 180) * Math.PI),
							y: -900 * Math.sin((20 / 180) * Math.PI),
							color: '#31B431'
						})
					};



					if (typeof cw_l[0] != 'undefined') {
						nodes.push({
							id: 400,
							label: cw_l[0],
							fixed: true,
							x: -550,
							y: -50,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[1] != 'undefined') {
						nodes.push({
							id: 401,
							label: cw_l[1],
							fixed: true,
							x: -600,
							y: 0,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[2] != 'undefined') {
						nodes.push({
							id: 402,
							label: cw_l[2],
							fixed: true,
							x: -550,
							y: 50,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[3] != 'undefined') {
						nodes.push({
							id: 403,
							label: cw_l[3],
							fixed: true,
							x: -750,
							y: -25,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[4] != 'undefined') {
						nodes.push({
							id: 404,
							label: cw_l[4],
							fixed: true,
							x: -750,
							y: 25,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[5] != 'undefined') {
						nodes.push({
							id: 405,
							label: cw_l[5],
							fixed: true,
							x: -900,
							y: -50,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[6] != 'undefined') {
						nodes.push({
							id: 406,
							label: cw_l[6],
							fixed: true,
							x: -950,
							y: 0,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[7] != 'undefined') {
						nodes.push({
							id: 407,
							label: cw_l[7],
							fixed: true,
							x: -900,
							y: 50,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[8] != 'undefined') {
						nodes.push({
							id: 408,
							label: cw_l[8],
							fixed: true,
							x: -1100,
							y: -25,
							color: '#60CC48'
						})
					};
					if (typeof cw_l[9] != 'undefined') {
						nodes.push({
							id: 409,
							label: cw_l[9],
							fixed: true,
							x: -1100,
							y: 25,
							color: '#60CC48'
						})
					};

					if (typeof cw_r[0] != 'undefined') {
						nodes.push({
							id: 500,
							label: cw_r[0],
							fixed: true,
							x: 550,
							y: -50,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[1] != 'undefined') {
						nodes.push({
							id: 501,
							label: cw_r[1],
							fixed: true,
							x: 600,
							y: 0,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[2] != 'undefined') {
						nodes.push({
							id: 502,
							label: cw_r[2],
							fixed: true,
							x: 550,
							y: 50,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[3] != 'undefined') {
						nodes.push({
							id: 503,
							label: cw_r[3],
							fixed: true,
							x: 800,
							y: -25,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[4] != 'undefined') {
						nodes.push({
							id: 504,
							label: cw_r[4],
							fixed: true,
							x: 800,
							y: 25,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[5] != 'undefined') {
						nodes.push({
							id: 505,
							label: cw_r[5],
							fixed: true,
							x: 950,
							y: -50,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[6] != 'undefined') {
						nodes.push({
							id: 506,
							label: cw_r[6],
							fixed: true,
							x: 950,
							y: 0,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[7] != 'undefined') {
						nodes.push({
							id: 507,
							label: cw_r[7],
							fixed: true,
							x: 900,
							y: 50,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[8] != 'undefined') {
						nodes.push({
							id: 508,
							label: cw_r[8],
							fixed: true,
							x: 1100,
							y: -25,
							color: '#60CC48'
						})
					};
					if (typeof cw_r[9] != 'undefined') {
						nodes.push({
							id: 509,
							label: cw_r[9],
							fixed: true,
							x: 1100,
							y: 25,
							color: '#60CC48'
						})
					};



					if (typeof ib_d[0] != 'undefined') {
						nodes.push({
							id: 600,
							label: ib_d[0],
							fixed: true,
							x: 600 * Math.cos((130 / 180) * Math.PI),
							y: 500 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[1] != 'undefined') {
						nodes.push({
							id: 601,
							label: ib_d[1],
							fixed: true,
							x: 600 * Math.cos((140 / 180) * Math.PI),
							y: 500 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[2] != 'undefined') {
						nodes.push({
							id: 602,
							label: ib_d[2],
							fixed: true,
							x: 600 * Math.cos((150 / 180) * Math.PI),
							y: 500 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[3] != 'undefined') {
						nodes.push({
							id: 603,
							label: ib_d[3],
							fixed: true,
							x: 800 * Math.cos((130 / 180) * Math.PI),
							y: 700 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[4] != 'undefined') {
						nodes.push({
							id: 604,
							label: ib_d[4],
							fixed: true,
							x: 800 * Math.cos((140 / 180) * Math.PI),
							y: 700 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[5] != 'undefined') {
						nodes.push({
							id: 605,
							label: ib_d[5],
							fixed: true,
							x: 800 * Math.cos((150 / 180) * Math.PI),
							y: 700 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[6] != 'undefined') {
						nodes.push({
							id: 606,
							label: ib_d[6],
							fixed: true,
							x: 1000 * Math.cos((130 / 180) * Math.PI),
							y: 900 * Math.sin((130 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[7] != 'undefined') {
						nodes.push({
							id: 607,
							label: ib_d[7],
							fixed: true,
							x: 1000 * Math.cos((140 / 180) * Math.PI),
							y: 900 * Math.sin((140 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[8] != 'undefined') {
						nodes.push({
							id: 608,
							label: ib_d[8],
							fixed: true,
							x: 1000 * Math.cos((150 / 180) * Math.PI),
							y: 900 * Math.sin((150 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};
					if (typeof ib_d[9] != 'undefined') {
						nodes.push({
							id: 609,
							label: ib_d[9],
							fixed: true,
							x: 1000 * Math.cos((160 / 180) * Math.PI),
							y: 900 * Math.sin((160 / 180) * Math.PI),
							color: '#7EC21E'
						})
					};

					if (typeof su_d[0] != 'undefined') {
						nodes.push({
							id: 700,
							label: su_d[0],
							fixed: true,
							x: 600 * Math.cos((70 / 180) * Math.PI),
							y: 500 * Math.sin((70 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[1] != 'undefined') {
						nodes.push({
							id: 701,
							label: su_d[1],
							fixed: true,
							x: 600 * Math.cos((90 / 180) * Math.PI),
							y: 500 * Math.sin((90 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[2] != 'undefined') {
						nodes.push({
							id: 702,
							label: su_d[2],
							fixed: true,
							x: 600 * Math.cos((110 / 180) * Math.PI),
							y: 500 * Math.sin((110 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[3] != 'undefined') {
						nodes.push({
							id: 703,
							label: su_d[3],
							fixed: true,
							x: 700 * Math.cos((80 / 180) * Math.PI),
							y: 600 * Math.sin((80 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[4] != 'undefined') {
						nodes.push({
							id: 704,
							label: su_d[4],
							fixed: true,
							x: 700 * Math.cos((100 / 180) * Math.PI),
							y: 600 * Math.sin((100 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[5] != 'undefined') {
						nodes.push({
							id: 705,
							label: su_d[5],
							fixed: true,
							x: 800 * Math.cos((70 / 180) * Math.PI),
							y: 700 * Math.sin((70 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[6] != 'undefined') {
						nodes.push({
							id: 706,
							label: su_d[6],
							fixed: true,
							x: 800 * Math.cos((90 / 180) * Math.PI),
							y: 700 * Math.sin((90 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[7] != 'undefined') {
						nodes.push({
							id: 707,
							label: su_d[7],
							fixed: true,
							x: 800 * Math.cos((110 / 180) * Math.PI),
							y: 700 * Math.sin((110 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[8] != 'undefined') {
						nodes.push({
							id: 708,
							label: su_d[8],
							fixed: true,
							x: 900 * Math.cos((80 / 180) * Math.PI),
							y: 800 * Math.sin((80 / 180) * Math.PI),
							color: '#34764E'
						})
					};
					if (typeof su_d[9] != 'undefined') {
						nodes.push({
							id: 709,
							label: su_d[9],
							fixed: true,
							x: 900 * Math.cos((100 / 180) * Math.PI),
							y: 800 * Math.sin((100 / 180) * Math.PI),
							color: '#34764E'
						})
					};

					if (typeof dw_d[0] != 'undefined') {
						nodes.push({
							id: 800,
							label: dw_d[0],
							fixed: true,
							x: 600 * Math.cos((50 / 180) * Math.PI),
							y: 500 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[1] != 'undefined') {
						nodes.push({
							id: 801,
							label: dw_d[1],
							fixed: true,
							x: 600 * Math.cos((40 / 180) * Math.PI),
							y: 500 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[2] != 'undefined') {
						nodes.push({
							id: 802,
							label: dw_d[2],
							fixed: true,
							x: 600 * Math.cos((30 / 180) * Math.PI),
							y: 500 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[3] != 'undefined') {
						nodes.push({
							id: 803,
							label: dw_d[3],
							fixed: true,
							x: 800 * Math.cos((50 / 180) * Math.PI),
							y: 700 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[4] != 'undefined') {
						nodes.push({
							id: 804,
							label: dw_d[4],
							fixed: true,
							x: 800 * Math.cos((40 / 180) * Math.PI),
							y: 700 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[5] != 'undefined') {
						nodes.push({
							id: 805,
							label: dw_d[5],
							fixed: true,
							x: 800 * Math.cos((30 / 180) * Math.PI),
							y: 700 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[6] != 'undefined') {
						nodes.push({
							id: 806,
							label: dw_d[6],
							fixed: true,
							x: 1000 * Math.cos((50 / 180) * Math.PI),
							y: 900 * Math.sin((50 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[7] != 'undefined') {
						nodes.push({
							id: 807,
							label: dw_d[7],
							fixed: true,
							x: 1000 * Math.cos((40 / 180) * Math.PI),
							y: 900 * Math.sin((40 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[8] != 'undefined') {
						nodes.push({
							id: 808,
							label: dw_d[8],
							fixed: true,
							x: 1000 * Math.cos((30 / 180) * Math.PI),
							y: 900 * Math.sin((30 / 180) * Math.PI),
							color: '#31B431'
						})
					};
					if (typeof dw_d[9] != 'undefined') {
						nodes.push({
							id: 809,
							label: dw_d[9],
							fixed: true,
							x: 1000 * Math.cos((20 / 180) * Math.PI),
							y: 900 * Math.sin((20 / 180) * Math.PI),
							color: '#31B431'
						})
					};




					var nodes = new vis.DataSet(nodes);





					var edges = new vis.DataSet([

						{
							from: 1,
							to: 2,
							label: res.profileDetails[0]['artist_first_name'] + " Influenced By",
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},
						{
							from: 1,
							to: 3,
							label: res.profileDetails[0]['artist_first_name'] + " Studied Under",
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},
						{
							from: 1,
							to: 4,
							label: res.profileDetails[0]['artist_first_name'] + " Danced in the Work of",
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},
						{
							from: 1,
							to: 5,
							label: "Influenced By " + res.profileDetails[0]['artist_first_name'],
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},
						{
							from: 1,
							to: 6,
							label: "Studied Under " + res.profileDetails[0]['artist_first_name'],
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},
						{
							from: 1,
							to: 7,
							label: "Danced in the Work of " + res.profileDetails[0]['artist_first_name'],
							font: {
								background: "white",
								size: 25,
								align: "middle"
							}
						},

						{
							from: 1,
							to: "8.1"
						},
						{
							from: "8.1",
							to: 8,
							label: res.profileDetails[0]['artist_first_name'] + " Collaborated With",
							font: {
								background: "white",
								size: 25,
								align: "horizontal"
							}
						},

						{
							from: 1,
							to: "9.1"
						},
						{
							from: "9.1",
							to: 9,
							label: res.profileDetails[0]['artist_first_name'] + " Collaborated With",
							font: {
								background: "white",
								size: 25,
								align: "horizontal"
							}
						},

						{
							from: 2,
							to: 100
						},
						{
							from: 2,
							to: 101
						},
						{
							from: 2,
							to: 102
						},
						{
							from: 2,
							to: 103
						},
						{
							from: 2,
							to: 104
						},
						{
							from: 2,
							to: 105
						},
						{
							from: 2,
							to: 106
						},
						{
							from: 2,
							to: 107
						},
						{
							from: 2,
							to: 108
						},
						{
							from: 2,
							to: 109
						},

						{
							from: 3,
							to: 200
						},
						{
							from: 3,
							to: 201
						},
						{
							from: 3,
							to: 202
						},
						{
							from: 3,
							to: 203
						},
						{
							from: 3,
							to: 204
						},
						{
							from: 3,
							to: 205
						},
						{
							from: 3,
							to: 206
						},
						{
							from: 3,
							to: 207
						},
						{
							from: 3,
							to: 208
						},
						{
							from: 3,
							to: 209
						},

						{
							from: 4,
							to: 300
						},
						{
							from: 4,
							to: 301
						},
						{
							from: 4,
							to: 302
						},
						{
							from: 4,
							to: 303
						},
						{
							from: 4,
							to: 304
						},
						{
							from: 4,
							to: 305
						},
						{
							from: 4,
							to: 306
						},
						{
							from: 4,
							to: 307
						},
						{
							from: 4,
							to: 308
						},
						{
							from: 4,
							to: 309
						},

						{
							from: 8,
							to: 400
						},
						{
							from: 8,
							to: 401
						},
						{
							from: 8,
							to: 402
						},
						{
							from: 8,
							to: 403
						},
						{
							from: 8,
							to: 404
						},
						{
							from: 8,
							to: 405
						},
						{
							from: 8,
							to: 406
						},
						{
							from: 8,
							to: 407
						},
						{
							from: 8,
							to: 408
						},
						{
							from: 8,
							to: 409
						},

						{
							from: 9,
							to: 500
						},
						{
							from: 9,
							to: 501
						},
						{
							from: 9,
							to: 502
						},
						{
							from: 9,
							to: 503
						},
						{
							from: 9,
							to: 504
						},
						{
							from: 9,
							to: 505
						},
						{
							from: 9,
							to: 506
						},
						{
							from: 9,
							to: 507
						},
						{
							from: 9,
							to: 508
						},
						{
							from: 9,
							to: 509
						},

						{
							from: 5,
							to: 600
						},
						{
							from: 5,
							to: 601
						},
						{
							from: 5,
							to: 602
						},
						{
							from: 5,
							to: 603
						},
						{
							from: 5,
							to: 604
						},
						{
							from: 5,
							to: 605
						},
						{
							from: 5,
							to: 606
						},
						{
							from: 5,
							to: 607
						},
						{
							from: 5,
							to: 608
						},
						{
							from: 5,
							to: 609
						},

						{
							from: 6,
							to: 700
						},
						{
							from: 6,
							to: 701
						},
						{
							from: 6,
							to: 702
						},
						{
							from: 6,
							to: 703
						},
						{
							from: 6,
							to: 704
						},
						{
							from: 6,
							to: 705
						},
						{
							from: 6,
							to: 706
						},
						{
							from: 6,
							to: 707
						},
						{
							from: 6,
							to: 708
						},
						{
							from: 6,
							to: 709
						},

						{
							from: 7,
							to: 800
						},
						{
							from: 7,
							to: 801
						},
						{
							from: 7,
							to: 802
						},
						{
							from: 7,
							to: 803
						},
						{
							from: 7,
							to: 804
						},
						{
							from: 7,
							to: 805
						},
						{
							from: 7,
							to: 806
						},
						{
							from: 7,
							to: 807
						},
						{
							from: 7,
							to: 808
						},
						{
							from: 7,
							to: 809
						}

					]);



					// create a network
					var familytree_container = document.getElementById('familytreenetwork');
					var family_data = {
						nodes: nodes,
						edges: edges
					};
					var options = {
						physics: false,
						nodes: {
							shape: "box",
							color: {
								background: '#90EE90'
							},
							font: {
								size: 24,
								color: 'white'
							},
						},
						edges: {
							// arrows: 'to',
							color: 'brown'
						},

					};
					var family_network = new vis.Network(familytree_container, family_data, options);
					family_network.body.nodes[1].options.font.size = 32;
					family_network.body.nodes[1].options.shape = "ellipse";
					family_network.body.nodes[1].options.font.color = 'white';

					family_network.setOptions(options);

					$("#familytree_download_name").val(res.profileDetails[0]['artist_first_name'] + " " + res.profileDetails[0]['artist_last_name']);


				}
			});
		}
	</script>






	<script>
		$("#filter_toggle").click(function() {

			$("#filter_toggle").hide();

			$("#filter_div").show();

			document.body.style.overflow = "hidden";

			$("#filter_div").css("z-index", "10");
			$("#filter_div").css("width", "320px");
			$("#filter_div").css("position", "absolute");
			$("#filter_div").css("top", "-70px");
			$("#filter_div").css("min-height", "100vh");
			$("#filter_div").css("background", "white");

			document.getElementById("lineage_opacity").style.height = "100%";

			$("#network_display_div").css("z-index", "0");
			$("#network_display_div").css("position", "absolute");
			$("#network_display_div").css("top", "40px");

			$("#network_display_div").css("top", "40px");
			$("#familytree_display_div").css("top", "400px");
			$("#artistlist_display_div").css("top", "400px");

		});
	</script>


	<script>
		$("#filter_close_button").click(function() {
			
			$("#filter_div").hide();

			document.body.style.overflow = "auto";

			$("#filter_toggle").show();

			$("#filter_div").css("z-index", "");
			$("#filter_div").css("width", "");
			$("#filter_div").css("position", "");
			$("#filter_div").css("top", "");
			$("#filter_div").css("background", "");
			
			document.getElementById("lineage_opacity").style.height = "0%";


			$("#network_display_div").css("z-index", "");
			$("#network_display_div").css("position", "");
			$("#network_display_div").css("top", "");

			$("#network_display_div").css("top", "40px");
			$("#familytree_display_div").css("top", "40px");
			$("#artistlist_display_div").css("margin-top", "40px");
		});
	</script>


	<script>
		if ($(window).width() > 1024) {
			$('#filter_toggle').hide();
			$('#filter_close_button').hide();
			$('#network_display_div').css("margin-left", "0px");
			$('#network_display_div').css("padding-left", "0%");
			$('#download_family').css("margin-left", "0px");

			$("#familytree_buttons").css("margin-top", "0px");
			$("#download_family").css("font-size", "16px");
			$("#close_family").css("font-size", "16px");

			$("#network_display_div").css("top", "0px");

			$("#filter_div").show();

		} else {
			$('#filter_toggle').show();
			$('#filter_close_button').show();
			$('#network_display_div').css("padding-left", "5%");
			$('#download_family').css("margin-left", "0px");

			$("#familytree_buttons").css("margin-top", "40px");
			$("#download_family").css("font-size", "14px");
			$("#close_family").css("font-size", "14px");


			$("#filter_div").hide();

			$("#network_display_div").css("top", "40px");
			$("#familytree_display_div").css("top", "400px");
			$("#artistlist_display_div").css("top", "400px");

		}

		$(window).on('resize', function() {
			if ($(window).width() > 1024) {
				$('#filter_toggle').hide();
				$('#filter_close_button').hide();
				$('#network_display_div').css("margin-left", "0px");
				$('#network_display_div').css("padding-left", "0%");
				$('#download_family').css("margin-left", "0px");

				$("#familytree_buttons").css("margin-top", "0px");
				$("#download_family").css("font-size", "16px");
				$("#close_family").css("font-size", "16px");

				$("#network_display_div").css("top", "0px");

				$("#filter_div").show();

			} else {
				$('#filter_toggle').show();
				$('#filter_close_button').show();
				$('#network_display_div').css("padding-left", "5%");
				$('#download_family').css("margin-left", "0px");

				$("#familytree_buttons").css("margin-top", "40px");
				$("#download_family").css("font-size", "14px");
				$("#close_family").css("font-size", "14px");

				$("#filter_div").hide();

				$("#network_display_div").css("top", "40px");
				$("#familytree_display_div").css("top", "400px");
				$("#artistlist_display_div").css("top", "400px");

			}
		})
	</script>


	<style>
		.ui-dialog {
			overflow: auto;
			position: fixed;
			top: 0;
			left: 0;
			padding: 5px;
			outline: 0;
			max-height: 500px;
			min-height: 500px;
			max-width: 300px;
			min-width: 300px;
		}

		.ui-dialog .ui-dialog-titlebar {
			padding: 10px;
			position: fixed;
			display: none;
		}

		.ui-dialog .ui-dialog-title {
			position: absolute;
			float: left;
			margin: .1em 0;
			white-space: nowrap;
			width: 90%;
			overflow: hidden;
			text-overflow: ellipsis;
			display: none;
		}

		.ui-dialog .ui-dialog-titlebar-close {
			position: absolute;
			right: .3em;
			top: 50%;
			width: 20px;
			margin: -10px 0 0 0;
			padding: 1px;
			height: 20px;
			display: none;
		}

		.ui-dialog .ui-dialog-content {
			top: 50px;
			position: relative;
			border: 0px;
			background: none;
			overflow: hidden;
			padding-bottom: 50px;
		}

		.ui-dialog .ui-dialog-buttonpane {
			text-align: left;
			background-image: none;
			position: fixed;
			background: none;
			top: 595px;
		}

		.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
			float: left;
		}

		.ui-dialog .ui-dialog-buttonpane button {
			left: -13px;
			cursor: pointer;
			width: 300px;
			background-color: #137525;
			color: white;
		}

		.ui-dialog .ui-resizable-se {
			width: 12px;
			height: 12px;
			right: -5px;
			bottom: -5px;
			background-position: 16px 16px;
		}

		.ui-draggable .ui-dialog-titlebar {
			cursor: move;
		}
	</style>












	<script>
		function fam_down() {
			var element = document.getElementById('familytreenetwork');
			var opt = {
				filename: $("#familytree_download_name").val() + ' - Dancestry Family Lineage.pdf',
				jsPDF: {
					unit: 'in',
					format: 'letter',
					orientation: 'portrait'
				}
			};

			html2pdf().set(opt).from(element).save();

			console.log(family_network);
		};
	</script>






	<script>
		$(document).ready(function() {

			for (var i = 0; i < admin_result.length; i++) {
				if (admin_result[i]["feature_id"] == 2) {
					if (admin_result[i]["feature_enabled"] == 0) {
						$("#artist_yob").hide();
					}
				}
			};

		});
	</script>








	<script>
		for (var i = 0; i < admin_result.length; i++) {
			if (admin_result[i]["feature_id"] == 4) {

				if (admin_result[i]["feature_enabled"] == 1) {
					function promptUserFirstTimeTutorial() {
						if (getCookie("first_time_completed") === "true") {
							console.log("completed, do nothing")
						} else {
							stage = new Stage();
							stage.initStage();
							var tutorshow = document.getElementById("TutorShowUp");
							tutorshow.style.display = "block";
							tutorshow.style.zIndex = "110";
							document.getElementById("FirstTimeTutorialWindow").style.display = "block";
							stage.highLightById("FirstTimeTutorialWindow");
							var yesButton = document.getElementById("first_time_yes");
							var noButton = document.getElementById("first_time_no");
							var laterButton = document.getElementById("first_time_snooze");
							yesButton.onclick = function() {
								closeAllWindows();
								tutorialWelcome();
								setCookie("first_time_completed", "true", cookies_exp_hours);
							}
							noButton.onclick = function() {
								setCookie("first_time_completed", "true", cookies_exp_hours);
								closeAllWindows();
							}
							laterButton.onclick = function() {
								closeAllWindows();
							}
						}
					}

				}
			}
		};
	</script>





	<script>
		$(document).ready(function()
		{
			$(document.body).on("click", ".ui-widget-overlay", function()
			{
				$.each($(".ui-dialog"), function()
				{
					var $dialog;
					$dialog = $(this).children(".ui-dialog-content");
					if($dialog.dialog("option", "modal"))
					{
						$dialog.dialog("close");
						document.getElementById("lineage_opacity").style.height = "0%";
					}
				});
			});;
		});
	</script>




	<style>
		.dialogclosebot{
			background-color: #000;
			color: red;
		}
	</style>






</body>

</html>