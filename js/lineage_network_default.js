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
var center = { posx: 0, posy: 0 };
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
	"Full Network": { nodes: [], edges: [], associatedNodeIDs: new Set(), associatedEdgeIDs: new Set() },
	"Studied Under": { nodes: [], edges: [], associatedNodeIDs: new Set(), associatedEdgeIDs: new Set() },
	"Collaborated With": { nodes: [], edges: [], associatedNodeIDs: new Set(), associatedEdgeIDs: new Set() },
	"Danced in the Work of": { nodes: [], edges: [], associatedNodeIDs: new Set(), associatedEdgeIDs: new Set() },
	"Influenced By": { nodes: [], edges: [], associatedNodeIDs: new Set(), associatedEdgeIDs: new Set() }
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
	$(".rel_box").each(function () {
		this.checked = true;
	});
	$(".rel_box").change(function () {
		if (this.checked) {
			rel.push(tab_labels[this.id]);
		} else {
			const index = rel.indexOf(tab_labels[this.id]);
			if (index > -1) {
				rel.splice(index, 1);
			}
		}
		lineage_network.applyEdgeFilters({ artist_relation: rel });

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
/**
 * @author Sai Cao
 * @param {*} node 
 */
function loadArtistEvent(node) {
	// submitJson(null, 'eventcontroller.php',
	// {
	// 	"action":"getEventPlanner",
	// 	"useremailid":node["artist_email_address"],
	// 	"current":true
	// },
	// loadTable);
	// let email=node['artist_email_address'];
	// console.log(email);
	console.log(node["artist_email_address"]);
	$.ajax({
		type: "POST",
		url: "eventcontroller.php",
		data: JSON.stringify({
			"action": "getEventsByEmail",
			"useremailid": node["artist_email_address"]
		}), // serializes the form's elements.
		success: function (res) {
			console.log(res);
			console.log(res["result"]);
			loadTableEvents(res, node);
			$('#eventTable').show();
			$('#spin_loading_event').hide();
		},
		error: function (reponse) {
			console.log(reponse);
		}
	});
}


function initNetwork() {

	const default_options = {
		autoResize: true,
		height: '100%',
		width: '100%',
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
			font: { align: "middle", size: 0 }
		},        // defined in the edges module.
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
		},        // defined in the nodes module.

		interaction: {

			hover: true,
			tooltipDelay: 100
		},  // defined in the interaction module.
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
		},      // defined in the physics module.
	};
	lineage_network = new LineageNetwork("my_network", default_options);
	originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
	$('#search_text').html(originalText);

	drawDefaultNetwork(function () { return undefined; });
}


function initSearchMenu() {

	// normal global variables to help functions run
	var search_names = ["-1", "-1", "-1", "-1"];
	// "504" is melanie aceto
	var default_ids = ["534", "209", "102", "504"]
	var current_id = "-1"
	var search_filters = { "genre": "", "artistType": "", "country": "", "state": "", "city": "", "university": "", "degree": "", "major": "", "ethnicity": "", "gender": "", "livingStatus": "" }
	var autocompleteLoadedData = { 'artist_name': [], 'university': [], 'city_names': [], 'state_names': [], 'country_names': [], 'major_names': [], 'artist_genres': [], 'genres': [], 'degree_names': [], 'ethnicity_names': [] }
	var autocompleteCategoryToAction = { 'artist_name': ["getArtistNames", "artistcontroller.php"], 'university': ["getUniversityNames", "artistcontroller.php"], 'city_names': ["getCityNames", "artistcontroller.php"], 'state_names': ["getStateNames", "artistcontroller.php"], 'country_names': ["getCountryNames", "artistcontroller.php"], 'major_names': ["getMajor", "artistcontroller.php"], 'genres': ["getGenres", "genrecontroller.php"], 'artist_genres': ["getArtistGenre", "artistcontroller.php"], 'degree_names': ["getDegree", "artistcontroller.php"], 'ethnicity_names': ["getEthnicity", "artistcontroller.php"] }
	var idToName = {}
	var savedNetwork = []
	var genreIdToName = {}
	var genreNameToID = {}


	//format variables to make network look a certain way
	var edge_colors_dict = { default_color: "#C0C0C0", "Studied Under": "#1A3263", "Collaborated With": "#1A3263", "Danced in the Work of": "#1A3263", "Influenced By": "#1A3263" };
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
	search_button.addEventListener('click', (function (e) { submitSearch() }))
	//zeping
	search_button_popup = document.getElementById('search_popup');
	search_button_popup.addEventListener('click', (function (e) { FilterMenuEndEvent().then(endFilter); }))

	clear = document.getElementById('clear');
	clear.addEventListener('click', (function (e) { clearSearchbox() }));

	filterClo = document.getElementById('filterWindClose');
	filterClo.addEventListener('click', (function (e) { filterClose() }));

	search_all = document.getElementById('searchAll');
	search_all.addEventListener('click', (function (e) { searchEntireNet() }))

	clear_popup = document.getElementById("clear_popup");
	clear_popup.addEventListener("click", (function (e) { clearFilterMenu() }));
	addArtistSearch = document.getElementById('addArtistSearch');
	addArtistSearch.addEventListener('click', (function (e) { addSearchBox('Artist') }));
	addGenreSearch = document.getElementById('addGenreSearch');
	addGenreSearch.addEventListener('click', (function (e) { addSearchBox('Genre') }));

	addGenreSearch_popup = document.getElementById('addGenre_popupSearch');
	addGenreSearch_popup.addEventListener('click', (function (e) { addSearchBox('Genre_popup') }));

	addArtistTypeSearch = document.getElementById('addArtistTypeSearch');
	addArtistTypeSearch.addEventListener('click', (function (e) { addSearchBox('ArtistType') }));


	addArtistTypeSearch_popup = document.getElementById('addArtistType_popupSearch');
	addArtistTypeSearch_popup.addEventListener('click', (function (e) { addSearchBox('ArtistType_popup') }));

	addCountrySearch = document.getElementById('addCountrySearch');
	addCountrySearch.addEventListener('click', (function (e) { addSearchBox('Country') }));

	addCountrySearch_popup = document.getElementById('addCountry_popupSearch');
	addCountrySearch_popup.addEventListener('click', (function (e) { addSearchBox('Country_popup') }));

	addStateSearch = document.getElementById('addStateSearch');
	addStateSearch.addEventListener('click', (function (e) { addSearchBox('State') }));

	addStateSearch_popup = document.getElementById('addState_popupSearch');
	addStateSearch_popup.addEventListener('click', (function (e) { addSearchBox('State_popup') }));

	addCitySearch = document.getElementById('addCitySearch');
	addCitySearch.addEventListener('click', (function (e) { addSearchBox('City') }));

	addCitySearch_popup = document.getElementById('addCity_popupSearch');
	addCitySearch_popup.addEventListener('click', (function (e) { addSearchBox('City_popup') }));

	addRelationship_popup = document.getElementById('addRelatioshipSearch_popup');
	addRelationship_popup.addEventListener('click', (function (e) { openRelationship('addRelationship_popup', 'addRelatioshipSearch_popup', 'study_rel', "coll_rel", "dance_rel", "infl_rel") }))
	closeRelationship_popup = document.getElementById('closeRelationship_popup');
	closeRelationship_popup.addEventListener('click', (function (e) { closeRelationship('addRelationship_popup', 'addRelatioshipSearch_popup', 'study_rel', "coll_rel", "dance_rel", "infl_rel") }))
	addEthnicitySearch = document.getElementById('addEthnicitySearch');
	addEthnicitySearch.addEventListener('click', (function (e) { addSearchBox('Ethnicity') }))

	addGenderSearch = document.getElementById('addGenderSearch');
	addGenderSearch.addEventListener('click', (function (e) { addSearchBox('Gender') }))

	// clear all search boxes and close all open search boxess except for the default one

	/**
	 * This function generate id and classname for search boxes and prepare for creating search box dynamically. 
	 * 
	 * @param  {string} frontID Common ID of search boxes. e.g. frontID for "Genre" search boxes is "Genre".
	 */
	function addSearchBox(frontID) {
		countMap.set(frontID, countMap.get(frontID) + 1);
		if (countMap.get(frontID) >= 1) {
			if (frontID.includes('_')) {
				if (frontID.includes('ArtistType')) {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Artist Type' + ' +';
				} else if (frontID.includes('Country')) {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Country' + ' +';
				}
				else {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + frontID.substring(0, frontID.indexOf('_')) + ' +';
				}
			} else {
				if (frontID.includes('ArtistType')) {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Artist Type' + ' +';
				} else if (frontID.includes('Country')) {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add Another ' + 'Country' + ' +';
				}
				else {
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
				autocompleteLoadedData['ethnicity_names'].sort(function (a, b) {
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
				autocompleteLoadedData['artist_genres'].sort(function (a, b) {
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

			currentClose.addEventListener('click', (function (e) { closeSearchBox(currentDiv, currentSelect, frontID) }));
			document.getElementById(currentDivID).appendChild(currentSelect);
			document.getElementById(currentDivID).appendChild(currentClose);
			document.getElementById('add' + frontID).style.display = 'block';
		}
		else {
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
			}
			else {
				firstLetter = frontID[0].toLowerCase();
				if (firstLetter == "a" || firstLetter == "e" || firstLetter == "i" || firstLetter == "o" || firstLetter == "u") {
					currentBox.placeholder = 'Enter an ' + frontID.toLowerCase();
				} else {
					currentBox.placeholder = 'Enter a ' + frontID.toLowerCase();
				}
			}
			currentBox.className = currentClassName;
			currentClose.addEventListener('click', (function (e) { closeSearchBox(currentDiv, currentBox, frontID) }));

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
		console.log("openrelationship");
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
				}
				else if (frontID.includes('Country')) {
					document.getElementById('add' + frontID + 'Search').textContent = 'Filter by ' + 'Country' + ' +';
				}
				else {
					document.getElementById('add' + frontID + 'Search').textContent = 'Filter By ' + frontID.substring(0, frontID.indexOf('_')) + ' +';
				}
			} else {
				if (frontID == 'ArtistType') {
					document.getElementById('add' + frontID + 'Search').textContent = 'Add ' + 'Artist Type' + ' to Search +';
				}
				else if (frontID.includes('Country')) {
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
					if (item.style.display != 'none') { allClose = false }
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
			data: JSON.stringify({ "action": "getCompleteArtistProfile" }),
			success: function (response) {
				response = JSON.stringify(response);
				json_object = $.parseJSON(response);
				nodesFetched = json_object.artist_profile;
				//get the artist who is current logged in
				for (var node of nodesFetched) if (node.artist_email_address === email) current_id = node.artist_profile_id
				if (nodesFetched) getNetworkData(nodesFetched)
				myNetwork = makeNetWork(default_nodes, default_edges, options)
				// set functions for what happens when network is acted upon
				onNetworkChange(myNetwork);
				//centers the node to the top
				myNetwork.body.data.nodes.update({ id: current_id, x: 0, y: 0 });
				myNetwork.redraw();
			},
			error: function (xhr, status, error) {
				defaultHomeScreenLoad()
				console.log("Error");
				console.log(xhr.responseText);
			}
		});
	}

	// the output of the desired artist attributes look like this: [{attribute: "user data"}, {attribute: "user data"},...{attribute: "user data"}]
	function organizeSQLOutcome(sqlOutput) {
		if (Object.keys(sqlOutput[0])[0] == "genre_id") for (var genre of sqlOutput) {
			genreIdToName[genre["genre_id"]] = genre["genre_name"]
			genreNameToID[genre["genre_name"]] = genre["genre_id"]
		}
		var data = sqlOutput
		if (Object.keys(data[0])[0] == "artist_genre") data = organizeArtistTypeData(sqlOutput)
		var organizedOutput = [];
		var counter = 1;
		var listedByID = false;
		// if the attribute happens to already include an id, we do not want to overwrite it
		for (var key of Object.keys(data[0])) if (key.includes("id") && !key.includes("residence")) listedByID = true;
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
		for (var artist_genre of brokenUpGenres) sqlFormat.push({ "artist_genre": artist_genre })
		return sqlFormat
	}

	// all of the data needs to be loaded and ready to access in the different searches box
	function loadAutocompleteData(category) {
		fetch(autocompleteCategoryToAction[category][1], {
			method: "post",
			body: JSON.stringify({ action: autocompleteCategoryToAction[category][0] })
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
						autocompleteLoadedData[category].push({ label: fullName, node_id: data.artist_profile_id, image: imageURL, is_artist: is_artist });
						//this allows for easy access of names later on
						idToName[data.artist_profile_id] = fullName
					}
					else if (category == "genres") autocompleteLoadedData[category].push({ label: data.genre_name, id: data.genre_id });
					else autocompleteLoadedData[category].push({ label: data.label, id: data.id });
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
			select: function (event, ui) {
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
		$(searchbox).data("ui-autocomplete")._renderItem = function (ul, item) {
			var $li = $('<li>');
			var $img = $('<img style="width:32px;height:32px;">');
			var imgURL = item.image
			if (item.image === "") imgURL = "./img/noProfile1.png"
			if (myNetwork && myNetwork.body.data.nodes._data[item.node_id]) if (myNetwork.body.data.nodes._data[item.node_id].is_artist && !myNetwork.body.data.nodes._data[item.node_id].image.includes("upload")) imgURL = "./img/profileNoPic.png"
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
			response: function () {
				if (!ifLetterContains(src, this.value) && typeof this.value !== "undefined") {
					document.getElementById(searchbox.slice(1, searchbox.length)).style.borderColor = "red";
					if (autocompleteTextboxCounter.get(searchbox) == undefined) {
						//console.log("autocomplete box is undefined," + searchbox);
						$('#invalidInputOverlay').fadeIn(300);
						setTimeout(function () {
							$('#invalidInputOverlay').fadeOut(800);
						}, 1100);
					} else {
						//console.log("There is an entry of " + searchbox)
						//console.log("Current length " + autocompleteTextboxCounter.get(searchbox))
						if (this.value.length > autocompleteTextboxCounter.get(searchbox)) {
							$('#invalidInputOverlay').fadeIn(300);
							setTimeout(function () {
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
			select: function (event, ui) {
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
		//$('#search_text').html("");
		$('input:checkbox').removeAttr('checked');
		for (var name of search_names) name = "-1"
		for (var id of default_ids) id = "-1"
		search_names = ["-1", "-1", "-1", "-1"];
		searchAndDraw({ action: "centerSearchById", "artist_profile_id": ["534", "209", "102", "504"] }, lineage_network, function () { return undefined; });
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
		setTimeout(function () {
			closeFilter();
			//console.log("second function executed");
		}, 1);
	}


	function FilterMenuEndEvent() {
		return new Promise(function (resolve, reject) {
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

			input_button_close.onclick = function () {
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
		lineage_network.applyEdgeFilters({ artist_relation: relaaa });
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
			position: { x: 0, y: 0 },
			scale: 0.6,
			offset: { x: 0, y: 0 },
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
		}
		else {
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
		if (data.artist_profile_id.length != 0) { allEmpty = false }
		if (data.artist_gender.length != 0) { allEmpty = false }
		if (data.artist_genre.length != 0) { allEmpty = false }
		if (data.genre.length != 0) { allEmpty = false }
		if (data.artist_residence_country.length != 0) { allEmpty = false }
		if (data.artist_residence_state.length != 0) { allEmpty = false }
		if (data.artist_residence_city.length != 0) { allEmpty = false }
		if (data.artist_ethnicity.length != 0) { allEmpty = false }
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

	/**
	 * Function for "search" button, it will fetch input from search boxes, submit data to backend, and generate, display result information,
	 */
	function submitSearch() {

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
			searchAndDraw(loadData, lineage_network, function (result, mainNodeCount) {
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
				searchAndDraw({ action: "centerSearchById", "artist_profile_id": ["534", "209", "102", "504"] }, lineage_network, function () { return undefined; });
				originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
				$('#search_text').html(originalText);
			} else {
				searchAndDraw({ action: "centerSearchById", "artist_profile_id": ["-1"] }, lineage_network, function () { return undefined; });
				$('#search_text').html('&nbsp&nbsp' + "No Results Found. Please change your search criteria.");
				document.getElementById('NoResultWindow').style.display = 'block';
			}
		}
		//alert(test);	

		$("#mySidenav").hide();
	}
	
	/**
	 * Fcuntion for "Search Entire Network" button.
	 */
	function searchEntireNet() {
		$("#mySidenav").hide();
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
		//JSON.stringify(loadData);
		searchAndDraw(loadData, lineage_network, function (result, mainNodeCount) {
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
			((data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0)
				? frontstr = frontstr + ', of'
				: str)
		} else {
			//alert((data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0) );
			(data.artist_gender.length != 0 || data.artist_genre.length != 0 || data.genre.length != 0 || data.artist_ethnicity.length != 0
				? frontstr = frontstr + 'artists of'
				: frontstr)
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
			(data.artist_gender.length != 0
				? frontstr = frontstr + ', type: '
				: frontstr = frontstr + ' type: ')
			for (var type of data.artist_genre) {
				frontstr = frontstr + '<span style="font-weight:bold">' + type + '</span>' + ", "
			}
			frontstr = frontstr.substr(0, frontstr.length - 2)
		} else if (data.artist_genre.length != 0 && data.artist_genre.length > 3) {
			(data.artist_gender.length != 0
				? frontstr = frontstr + ', artist type: '
				: frontstr = frontstr + ' artist type:')
			var i = 0;
			while (i <= 2) {
				frontstr = frontstr + '<span style="font-weight:bold">' + data.artist_genre[i] + '</span>' + ", "
				i = i + 1
			}
			((data.genre.length != 0 || data.artist_ethnicity.length != 0)
				? frontstr = frontstr.substr(0, frontstr.length - 2)
				: frontstr = frontstr + ' and other types')
		}

		if (data.genre.length != 0 && data.genre.length <= 3) {
			((data.artist_gender.length != 0 || data.artist_genre.length != 0)
				? frontstr = frontstr + ', genre: '
				: frontstr = frontstr + ' genre: ')
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
			((data.artist_gender.length != 0 || data.artist_genre.length != 0)
				? frontstr = frontstr + ', genre: '
				: frontstr = frontstr + ' genre: ')
			var i = 0;
			while (i <= 2) {
				if (data.genre.includes(autocompleteLoadedData.genres[i].id)) {
					frontstr = frontstr + '<span style="font-weight:bold">' + autocompleteLoadedData.genres[i].label + '</span>' + ", "
				}
				//frontstr = frontstr + '<span style="font-weight:bold">' + data.genre[i] +'</span>' + ", "
				i = i + 1
			}
			((data.artist_ethnicity.length != 0)
				? frontstr = frontstr.substr(0, frontstr.length - 2)
				: frontstr = frontstr + ' and other genres')
		}

		if (data.artist_ethnicity.length != 0 && data.artist_ethnicity.length <= 3) {
			((data.artist_gender.length != 0 || data.genre.length != 0 || data.artist_genre.length != 0)
				? frontstr = frontstr + ', ethnicity: '
				: frontstr = frontstr + ' ethnicity: ')
			for (var race of data.artist_ethnicity) {
				frontstr = frontstr + '<span style="font-weight:bold">' + race + '</span>' + ", "
			}
			frontstr = frontstr.substr(0, frontstr.length - 2)
		} else if (data.artist_ethnicity.length != 0 && data.artist_ethnicity.length > 3) {
			((data.artist_gender.length != 0 || data.genre.length != 0 || data.artist_genre.length != 0)
				? frontstr = frontstr + ', ethnicity: '
				: frontstr = frontstr + ' ethnicity: ')
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
			((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0)
				? poststr = poststr + ', from'
				: poststr)
		} else if (data.artist_profile_id.length == 0 && data.artist_gender.length == 0 && data.artist_genre.length == 0 && data.genre.length == 0 && data.artist_ethnicity.length == 0) {
			((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0)
				? poststr = poststr + 'artists from'
				: poststr)
		} else if (data.artist_profile_id.length == 0 && (data.artist_gender.length == 0 || data.artist_genre.length == 0 || data.genre.length == 0 || data.artist_ethnicity.length == 0)) {
			((data.artist_residence_country.length != 0 || data.artist_residence_state != 0 || data.artist_residence_city != 0)
				? poststr = poststr + ', from'
				: poststr)
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
			((data.artist_residence_state != 0 || data.artist_residence_city != 0)
				? poststr = poststr.substr(0, poststr.length - 2)
				: poststr = poststr + ' and other countries')
		}
		if (data.artist_residence_state.length != 0 && data.artist_residence_state.length <= 3) {
			(data.artist_residence_country.length != 0
				? poststr = poststr + ', state: '
				: poststr = poststr + ' state: ')
			for (var state of data.artist_residence_state) {
				poststr = poststr + '<span style="font-weight:bold">' + state + '</span>' + ", "
			}
			poststr = poststr.substr(0, poststr.length - 2)
		} else if (data.artist_residence_state.length != 0 && data.artist_residence_state.length > 3) {
			(data.artist_residence_country.length != 0
				? poststr = poststr + ', state: '
				: poststr = poststr + ' state: ')
			var i = 0
			while (i <= 2) {
				poststr = poststr + '<span style="font-weight:bold">' + data.artist_residence_state[i] + '</span>' + ", "
				i = i + 1
			}
			(data.artist_residence_city != 0
				? poststr = poststr.substr(0, poststr.length - 2)
				: poststr = poststr + ' and other states')
		}
		if (data.artist_residence_city.length != 0 && data.artist_residence_city.length <= 3) {
			((data.artist_residence_country.length != 0 || data.artist_residence_state.length != 0)
				? poststr = poststr + ', city: '
				: poststr = poststr + ' city: ')
			for (var city of data.artist_residence_city) {
				poststr = poststr + '<span style="font-weight:bold">' + city + '</span>' + ", "
			}
			poststr = poststr.substr(0, poststr.length - 2)
		} else if (data.artist_residence_city.length != 0 && data.artist_residence_city.length > 3) {
			((data.artist_residence_country.length != 0 || data.artist_residence_state.length != 0)
				? poststr = poststr + ', city: '
				: poststr = poststr + ' city: ')
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
				data: JSON.stringify({ "action": "getArtistProfileByName", "artist_profile_id": id_selected }),
				success: function (response) {
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
					for (var id of default_ids) if (id !== "-1") if (myNetwork.body.data.nodes._data[id] && myNetwork.body.data.nodes._data[id].artist_relation.length <= 0) for (var page of Object.keys(all_nodes)) for (var node of default_nodes) {
						if (!all_nodes[page].associatedNodeIDs.has(node.id)) all_nodes[page].nodes.push(node)
						all_nodes[page].associatedNodeIDs.add(node.id)
					}
					//console.log(default_nodes)
					// how we get the network to reflect these changes in real time
					myNetwork.body.data.nodes.update(default_nodes);
					myNetwork.body.data.edges.update(default_edges);
					myNetwork.redraw();
					myNetwork.setOptions({ physics: true });
					network.setOptions({ physics: { timestep: 0.1 } });
					network.setOptions({ physics: { stabilization: { iterations: 0 } } });
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
		for (var node of default_nodes) if (node.id == id) default_nodes.splice(default_nodes.indexOf(node), 1)

		for (var page of Object.keys(all_nodes)) {
			for (var node of all_nodes[page].nodes) if (node.id == id) all_nodes[page].nodes.splice(all_nodes[page].nodes.indexOf(node), 1)
			for (var i = 0; i < all_nodes[page].associatedNodeIDs.length; i++) if (all_nodes[page].associatedNodeIDs[i] == id) all_nodes[page].associatedNodeIDs.splice(i, 1)
		}
		for (var i = 0; i < nodeIDs_visible.length; i++) if (nodeIDs_visible[i] == id) nodeIDs_visible.splice(i, 1)
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
				offset: { x: x, y: y },
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
		for (var elem of default_edges) if (twoWayID == elem.id) elem.twoWay = true
		for (var elem of all_nodes["Full Network"].edges) if (twoWayID == elem.id) elem.twoWay = true
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
			}
			else noLineage[nodesFetched[i].artist_profile_id] = true // if no relationships, no lineage for this node should be true
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
					if (page !== "Full Network") for (var id of all_nodes[page].associatedNodeIDs) {
						if (nodeDetails_new.id == id) all_nodes[page].nodes.push(nodeDetails_new)
					}
				}
			}
			// if we only have one node, we want to show it in all filters
			nodeIDs_visible.push(nodeDetails_new.id)
			if (nodesFetched.length === 1) for (var page of Object.keys(all_nodes)) {
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

		if (node.artist_dob) nodeDetails_new['dob'] = node.artist_dob;

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
			}
			else {
				if (node.is_user_artist === "artist") nodeDetails_new['image'] = "./img/profileNoPic.png";
				nodeDetails_new['shape'] = selected_shape_image;
			}
		}
		else {
			if (node.is_user_artist === "artist") {
				if (node.artist_photo_path !== "") nodeDetails_new['image'] = node.artist_photo_path;
				else nodeDetails_new['image'] = "./img/profileNoPic.png";
				nodeDetails_new['shape'] = default_shape
				nodeDetails_new['is_artist'] = true
				nodeDetails_new['color'] = isArtistNodeColor
			}
			else {
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
				}
				else if (eduNodes[j].education_type === "other") nodeDetails_new['university_other'] = eduNodes[j].institution_name;
			}
		}
		else {
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
		}
		else nodeDetails_new["artist_relation"] = "";
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
		network.on("stabilizationIterationsDone", function () {
			network.setOptions({ physics: false });

		});

		// hide the loading div after network is fully loaded
		network.on("afterDrawing", function () {
			$("#load").css("display", "none");
			//weFocused = true
			//$("body").css("overflow", "scroll");
		});

		// change the type of cursor to grabbing hand while dragging the network
		//Charul Testing
		network.on('dragging', function (obj) {
			$("#my_network").css("cursor", "-webkit-grabbing");
			//selected = -1
		});

		// change the type of cursor to hand on releasing the drag
		network.on('release', function (obj) {
			$("#my_network").css("cursor", "-webkit-grab");
			selected = -1
		});
		//CHarul Testing

		// change the type of cursor to pointing hand when hovered over a node
		network.on('hoverNode', function (obj) {
			$("#my_network").css("cursor", "pointer");
			$("#my_network").attr('title', 'No. of connections= ' + network.getConnectedEdges(obj.node).length);
			//$(".rightClickSuggestion").show()
		});

		network.on('hoverEdge', function (obj) {
			var relationship = myNetwork.body.edges[obj.edge].body.data.edges._data[obj.edge].label
			var to = idToName[myNetwork.body.edges[obj.edge].from.id]
			var from = idToName[myNetwork.body.edges[obj.edge].to.id]
			var twoWay = myNetwork.body.edges[obj.edge].body.data.edges._data[obj.edge].twoWay
			$("#my_network").css("cursor", "pointer");
			if (twoWay) {
				if (relationship.toLowerCase() === "influenced by") $("#my_network").attr('title', from + " was " + relationship.toLowerCase() + " " + to + " and " + to + " was " + relationship.toLowerCase() + " " + from);
				else $("#my_network").attr('title', from + " " + relationship.toLowerCase() + " " + to + " and " + to + " " + relationship.toLowerCase() + " " + from);
			}
			else {
				if (relationship.toLowerCase() === "influenced by") $("#my_network").attr('title', from + " was " + relationship.toLowerCase() + " " + to);
				else $("#my_network").attr('title', from + " " + relationship.toLowerCase() + " " + to);
			}
		});

		// change the type of cursor to hand on coming out of node hover
		network.on('blurNode', function (obj) {
			$("#my_network").css("cursor", "-webkit-grab");
			$(".rightClickSuggestion").hide()
		});

		network.on('blurEdge', function (obj) {
			$("#my_network").css("cursor", "-webkit-grab");
		});
		network.on('selectEdge', function (obj) {
		});

		network.on('selectNode', function (obj) {
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
		position: { x: 0, y: 0 },
		scale: 0.6,
		offset: { x: 0, y: 0 },
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
		searchAndDraw({ action: "centerSearchById", "artist_profile_id": [PROFILE_ID] }, lineage_network, function (result, mainNodeCount) {
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
		searchAndDraw({ action: "centerSearchById", "artist_profile_id": ["534", "209", "102", "504"] }, lineage_network, showResult);
		originalText = 'Choreographic Lineage of <span style="font-weight:bold">Anne Burnidge, Melanie Aceto, Monica Bill, Seyong Kim</span> are showing below:';
		$('#search_text').html(originalText);
	}
}

function isEmpty(data) {
	var allEmpty = true;
	if (typeof data.artist_profile_id !== 'undefined' && data.artist_profile_id.length != 0) { allEmpty = false }
	if (typeof data.artist_gender !== 'undefined' && data.artist_gender.length != 0) { allEmpty = false }
	if (typeof data.artist_genre !== 'undefined' && data.artist_genre.length != 0) { allEmpty = false }
	if (typeof data.genre !== 'undefined' && data.genre.length != 0) { allEmpty = false }
	if (typeof data.artist_residence_country !== 'undefined' && data.artist_residence_country.length != 0) { allEmpty = false }
	if (typeof data.artist_residence_state !== 'undefined' && data.artist_residence_state.length != 0) { allEmpty = false }
	if (typeof data.artist_residence_city !== 'undefined' && data.artist_residence_city.length != 0) { allEmpty = false }
	if (typeof data.artist_ethnicity !== 'undefined' && data.artist_ethnicity.length != 0) { allEmpty = false }
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
		success: function (response) {

			console.log(response);
			loading_img.style.display = 'none';
			// document.getElementById(network.conatiner_id).style.display = "inline-block";
			document.getElementById("loadingBar").style.display = "inline-block";
			network.vis_net.on("stabilizationProgress", function (params) {
				// console.log(params.total);

				// console.log(document.getElementById("loadingBar").style.display)
				var maxWidth = 496;
				var minWidth = 20;
				var widthFactor = params.iterations / params.total;
				var width = Math.max(minWidth, maxWidth * widthFactor);

				document.getElementById("progress_bar").style.width = width + "px";
				document.getElementById("progress_text").innerText =
					Math.round(widthFactor * 100) + "%";
			});

			network.vis_net.on('stabilizationIterationsDone', function () {
				let net = this;
				setTimeout(function () {
					document.getElementById("loadingBar").style.display = "none";
					document.getElementById(network.conatiner_id).style.display = "inline-block";
					net.stopSimulation();
					//console.log(PROFILE_ID);
					if (typeof PROFILE_ID != 'undefined' && !empty && loginSearch) {
						var { x: nodeX, y: nodeY } = lineage_network.vis_net.getPositions(PROFILE_ID)[PROFILE_ID];
						var nodePos = {
							position: { x: nodeX, y: nodeY },
							scale: 0.8,
							offset: { x: 0, y: 0 },
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
		error: function (xhr, status, err) {
			console.log(xhr.responseText);
		},
		dataType: "json",
		contentType: "application/json"
	}
	);
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

	eventButton.on("click", function () {
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

	let sending_json = { action: "getLoginRelatedArtistWithId", artist_profile_id: node["artist_profile_id"] };

	$.ajax({
		type: "POST",
		url: "artistrelationcontroller.php",
		data: JSON.stringify(sending_json),
		success: function (data) {
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
		error: function (xhr, status, err) {
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
		relations_arr.push({ artist_relation: "Studied Under", works: null });
	}

	if ($('#danced').prop('checked')) {
		relations_arr.push({ artist_relation: "Danced in the Work of", works: $('#danced_titles').val() });
	}
	if ($('#collaborated').prop('checked')) {
		relations_arr.push({ artist_relation: "Collaborated With", works: null });
	}
	if ($('#influenced').prop('checked')) {
		relations_arr.push({ artist_relation: "Influenced By", works: null });
	}


	let sending_json = JSON.stringify({ action: "addEditArtistRelationById", relations: relations_arr, artist_profile_id: nid });
	console.log(sending_json);
	$.ajax({
		type: "POST",
		url: "artistrelationcontroller.php",
		data: sending_json,
		success: function (data) {
			switch (data['Exception']) {
				case 100:
					alert("Please Login to add this artist");
					window.location.href = './login.php'
					break;
				case undefined:
					closeAddRelationPopUp();
					drawDefaultNetwork(function () { return undefined; });

					$("#AddRelationWindow").hide();
				default:
					console.log(data['Exception']);
			}
		},
		error: function (xhr, status, err) {
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
		console.log("hide it");
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

		this.vis_net = new vis.Network(this.container, { nodes: this.vis_nodes, edges: this.vis_edges }, options);
		this.colorMap = new Map();
		this.initColor();

		var self = this
		this.vis_net.on('selectNode', function (obj) {
			self.leftClickEvent(obj);

		});
		this.vis_net.on("oncontext", function (Object) {
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
		var { x: nodeX, y: nodeY } = this.vis_net.getPositions(obj.nodes[0])[obj.nodes[0]];
		var nodePos = {
			position: { x: nodeX, y: nodeY },
			scale: 0.6,
			offset: { x: 0, y: 0 },
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
			success: function (response) {
				console.log(response);
				loading_img.style.display = 'none';
				document.getElementById(self.conatiner_id).style.display = "";
				self.addDataFromArray(response["result"]);

			},
			error: function (xhr, status, err) {
				console.log(xhr.responseText);
			},
			dataType: "json",
			contentType: "application/json"
		}
		);
	}

	/**
	 * event show right click menu
	 * @param Object pointer Event
	 * @author Sai Cao
	 */
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
			document.getElementById('searchRelation').onclick = function (obj) {
				console.log("click add relations");
				$(".custom-menu").hide();
				self.showRelationships(selected);
				self.searchAndAddArtistEvent({ action: "centerSearchById", "artist_profile_id": [selected] });

			};

			console.log(selected);
			let hide_item = $("#network_node_menu li").eq(1)


			hide_item.html("Hide related artists of " + node["artist_first_name"]);

			hide_item.off();
			hide_item.on("click", function () {
				$(".custom-menu").hide();
				self.hideRelationships(selected);
			});
			let event1 = $("#network_node_menu li").eq(2);
			event1.html("Show Events");
			event1.off();
			event1.on("click", function () {
				$('#EventPopUp').show();
				$('#eventTable').hide();
				$('#spin_loading_event').show();

				$(".custom-menu").hide();
				loadArtistEvent(node);
			});
			let addnewrelation = $("#network_node_menu li").eq(3);
			addnewrelation.html("Add " + node["artist_first_name"] + " to my network");
			addnewrelation.off();
			$("#AddRelationWindow_content").hide();
			document.getElementById('AddRelation').onclick = function (obj) {
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


		console.log("show");



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
		this.vis_net.setData({ nodes: this.vis_nodes, edges: this.vis_edges });
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
		this.vis_net.setData({ nodes: this.vis_nodes, edges: this.vis_edges });
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
			this.edges.forEach(function (value, key) {
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
			this.vis_nodes.update({ id: id, hidden: true });
		}
	}
	/**
	 * Show the node by id
	 * @param id id of node
	 */
	showNode(id) {
		// console.log(id);
		if (this.vis_nodes.get(id)) {
			this.vis_nodes.update({ id: id, hidden: false });
		}
	}

	/**
	 * show the edge by id
	 * @param id
	 */
	showEdge(id) {

		if (this.vis_edges.get(id)) {
			this.vis_edges.update({ id: id, hidden: false });
		}
	}

	/**
	 * hide edge by id
	 * @param id
	 */
	hiddenEdge(id) {

		if (this.vis_edges.get(id)) {
			this.vis_edges.update({ id: id, hidden: true });
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

	/**
	 * Filter the edge by following condition
	 * combined with OR
	 * { "artist_relation":["Studied Under", "Collaborated With", "Danced in the Work of", "Influenced By"]
	 * }
	 * @param {Object} nodeConditions
	 * @returns {number} number of nodes in network after filtered
	 */
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

	/**
	 * Not Used
	 * @param nodeConditions
	 * @param edgeConditions
	 */
	applyAllFilters(nodeConditions, edgeConditions) {
		var result = this.dataFilter(this.nodes, nodeConditions);
		var all_node = result["inliers"];
		for (var i = 0; i < all_node.length; i++) {
			this.addNode(all_node[i]);
		}
		var result = this.dataFilter(this.nodes, edgeConditions);
		this.draw();
	}

	/**
	 * get related artist for node by node
	 * @param node node data
	 * @param edge
	 * @returns {undefined|*}
	 */
	getMate(node, edge) {
		if (node["artist_profile_id"] == edge["artist_profile_id_1"]) {
			return edge["artist_profile_id_2"];
		}
		if (node["artist_profile_id"] == edge["artist_profile_id_2"]) {
			return edge["artist_profile_id_1"];
		}
		return undefined;
	}


	/**
	 * Help function for applyNodeFilters which just filter data
	 * @param data
	 * @param conditions
	 * @returns {{inliers: [], outliers: []}} inliers number of nodes after filter, outliers number of node filtered
	 */
	dataFilter(data, conditions) {
		var result = { inliers: [], outliers: [] }

		let self = this;
		data.forEach(function (value, key) {

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
		this.vis_net.setData({ node: new vis.DataSet(), edges: new vis.DataSet() });
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


