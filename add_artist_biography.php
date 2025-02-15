<?php
include 'util.php';
require_once 'config.php';  // for getting google client, facebook client
my_session_start();

// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
	header('Location: login.php');
	exit;
}

require 'connect.php';
include 'connection_open.php';

$conn = getDbConnection();
$query = "SELECT * FROM admin_features;";
$statement = $conn->prepare($query);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$admin_result = $statement->fetchAll();
$admin_result = json_encode($admin_result);


// fetch the logged in user's profile record
$query_artist_profile = "SELECT * FROM artist_profile WHERE artist_profile_id = ?";
$statement = $conn->prepare($query_artist_profile);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute([$_SESSION["artist_profile_id"]]);
$count_artist_profile = $statement->rowCount();
$result_artist_profile = $statement->FETCHALL();

if ($count_artist_profile != 0) {
	$_SESSION["photo_file_path"] = $result_artist_profile[0]['artist_photo_path'];
	$_SESSION["biography_file_path"] = $result_artist_profile[0]['artist_biography'];
	$_SESSION["biography_text"] = $result_artist_profile[0]['artist_biography_text'];

	if (isset($_SESSION['photo_file_path']) && !is_null($_SESSION['photo_file_path']) && $_SESSION['photo_file_path'] != "") {
		$_SESSION['artist_image_present'] = '1';
	} else {
		$_SESSION['artist_image_present'] = '0';
	}

	if (isset($_SESSION['biography_file_path']) && !is_null($_SESSION['biography_file_path']) && $_SESSION['biography_file_path'] != "") {
		$_SESSION['artist_bio_present'] = '1';
	} else {
		$_SESSION['artist_bio_present'] = '0';
	}
}

$_SESSION["timeline_stage"] = "bio";
include 'menu.php';

// if we are in view mode, set a flag that will prevent the user from editing the values
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
	<title>Add Artist Biography | Choreographic Lineage</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>
	<style type="text/css">
		.biography_container {
			padding-left: 4%;
		}

		.button_container {
			margin: auto;
		}

		.submit_text {
			width: 20%;
		}

		.action_button {
			width: 190px;
		}

		.mrt35p {
			margin-top: 35px;
		}

		.clrr {
			background-color: red;
		}

		.clrg {
			background-color: green;
		}

		#prev_button {
			margin: 0;
			padding: 0;
			width: unset;
			padding-left: 10px;
		}

		#save_and_next {
			text-align: right;
			margin: 0;
			padding: 0px;
			padding-top: 0px;
			width: unset;
			padding-right: 10px;
		}

		#save_and_continue {
			width: unset;
			padding: 0;
			padding-right: 28.6%;
		}

		#Upload_pdf_section {
			margin: 0px;
		}

		#previous,
		#next,
		#next1 {
			margin: 0px;
		}

		#save_biography {
			display: flex;
		}

		#biography_text_message {
			padding-left: 15px;
		}

		#photo_div {
			display: flex;
		}

		#social_media_photo_section {
			margin-right: 0px;
			width: 100%;
			padding-right: 6.5px;
		}

		#social_media_linker_section {
			margin-left: 0px;
			width: 100%;
			padding-left: 6.5px;
		}

		#social_media_linker_box {
			height: 173px;
		}

		@media only screen and (max-width: 1000px) {
			#photo_div {
				flex-direction: column;
			}

			#social_media_photo_section,
			#social_media_linker_section {
				padding-left: 0px;
				padding-right: 0px;
			}

			#social_media_linker_box {
				height: 106px;
			}

			#prev_save_row {
				display: flex;
				padding-bottom: 10px;
			}

			#prev_button {
				width: 100%;
				padding-right: 5px;
			}

			#save_and_next {
				width: 100%;
				text-align: right;
				padding-top: 0px;
				padding-left: 5px;
			}

			#save_and_continue {
				padding: 0;
				text-align: center;
				float: inherit;
				padding-inline: 10px;
			}

			#previous,
			#next,
			#next1 {
				width: 100%;
			}

			#save_biography {
				flex-direction: column;
			}

			#biography_text_message {
				padding-left: 0px;
			}
		}
	</style>
	<style type="text/css">
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
		}

		.modal #close-button {
			position: absolute;

			/* don't need to go crazy with z-index here, just sits over .modal-guts */
			z-index: 1;

			top: 10px;

			/* needs to look OK with or without scrollbar */
			right: 10px;

			border: 0;
			background: #006400;
			color: white;
			padding: 5px 10px;
			font-size: 1.3rem;
		}

		.profile-details-class {
			display: none;
			border: 5px solid mediumseagreen;
			border-radius: 8px;
			margin-bottom: 5px;
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
			background-color: lightgreen;
			margin-left: -10px !important;
			margin-right: 0px !important;
		}

		.tal {
			text-align: left;
		}

		.mrl27p {
			margin-left: 27px;
		}
	</style>
	<link href="css/progressbar.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/cropper/jquery.Jcrop.min.css" type="text/css" />

	<script src="cropper/js/jquery.Jcrop.min.js"></script>
	<script src="submit_database_request.js"></script>
	<script src="js/cropperScript.js"></script>
</head>

<body>
	<?php include 'progressbar.php'; ?>
	<?php include 'add_artist_references.php'; ?>

	<input type="hidden" name="is_user_artist" id="is_user_artist" value="<?php echo (($_SESSION['contribution_type'] == "own") ? 'artist' : 'other')  ?>">
	<!-- <div class="row">
			<div class="sectionbox">
					<h2  style="display:inline;">
						<strong><?php echo (isset($_SESSION['contribution_type']) ? $_SESSION['contribution_type'] == "own" ? 'Your Biography' : 'Artist Biography' : 'Artist Biography') ?></strong>
						<span style="color:red;font-weight: bold;"> *</span>
					</h2>
					<div class="add-reference-button" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div>
						<h5  style="display:inline; float: right; color: #006400;"></h5>
					</div>
			</div>
		</div> -->
	<div class="row" id="bio_div">
		<div class="sectionbox">
			<div style="margin: 0px; display: flex; justify-content: space-between;">
				<h2>
					<strong><?php echo (isset($_SESSION['contribution_type']) ? $_SESSION['contribution_type'] == "own" ? 'Your Biography' : 'Artist Biography' : 'Artist Biography') ?></strong>
					<span style="color:red;font-weight: bold;"> *</span>
				</h2>
				<div class="add-reference-button" style="display: none;" onclick="addArtistReferences()"><img src="reference_quote.png" style="height: 40px; width: 40px; cursor: pointer;"></div>
			</div>
			<fieldset>
				<legend><strong>Choose a method</strong></legend>
				<div class="column small-6">
					<label style="font-size:18px">
						<input type="radio" id="upload_document" name="contribute_online_form" class="contribute_online_form" value="form" />
						Upload PDF
					</label>
				</div>
				<div class="column small-6">
					<label style="font-size:18px">
						<input type="radio" id="type_biography" name="contribute_online_form" class="contribute_online_form" value="phone" />
						Type or paste
					</label>
				</div>
			</fieldset>
			<!-- INSERT THE INPUT FIELDS HERE -->
			<div class="sectionbox" id="Upload_pdf_section" style="padding: 0px;">
				<form id="upload_biography" action="" method="post" enctype="multipart/form-data">
					<div class="row" style="padding-left:15px;">
						<h6><em><b>Upload a PDF file (Max size : 4MB)</b></em></h6>
						<div class="row">
							<!-- INSERT THE "choose file" here FIELDS HERE -->

							<?php
							if (isset($_SESSION["artist_bio_present"]) && $_SESSION['artist_bio_present'] == '1') {
							?>
								<div class="small-4 columns">
									<label for="bio_file_add" class="button medium action_button clrg" style="background-color:#65ba79;margin: 0px;">Update File</label>
									<input type="file" id="bio_file_add" name="bio_file_add" class="show-for-sr" required />
								</div>
							<?php
							} else {
							?>
								<div class="small-4 columns">
									<label for="bio_file_add" class="button medium action_button clrg" style="background-color:#65ba79;margin: 0px;">Upload File</label>
									<input type="file" id="bio_file_add" name="bio_file_add" class="show-for-sr" required />
								</div>
							<?php
							}
							?>
						</div>
						<div class="row">
							<div class="small-12 columns">
								<div id="bio_file_name">
									<?php
									if (isset($_SESSION["biography_file_path"])) {
										echo $_SESSION["biography_file_path"];
									}
									?>
								</div>
							</div>
							<div class="row mrt35p small-12 columns">
								<div id="bio_message">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="sectionbox" id="type_section" style="display: none">
				<label>
					<h6><em>Type/Paste Biography (Text Only)</em></h6>
					<form form id="upload_bigraphy_text" name="bigraphy_text_form" method="post" action="" enctype="multipart/form-data">
						<div class="row">
							<textarea placeholder="None" id="biography_text" class="tal" name="biography_text" rows="5"><?php
																														if (isset($_SESSION["biography_text"])) {
																															echo $_SESSION["biography_text"];
																														}
																														?>
									</textarea>
						</div>
						<div id="save_biography" class="row" style="display: flex;">
							<input type="submit" value="Save" class="button medium action_button clrg" style="background-color:#65ba79;margin: 0px;" />
							<span id="biography_text_message"></span>
						</div>
					</form>
				</label>
			</div>
		</div>
	</div>

	<!-- <div class="sectionbox" id="bio_message_div">
		<fieldset>
			<div class="sectionbox" style="color:red; margin-top: -15px;">Please provide a Biography. (Upload a <b>pdf</b> document or type in the biography text box.)</div>
		</fieldset>
	</div> -->
	</div>
	<div class="row" id="photo_div">
		<div id="social_media_photo_section">
			<div class="sectionbox">
				<div style="display: flex;">
					<h2 style="padding-right: 16px;">
						<strong><?php echo (($_SESSION['contribution_type'] == "own") ? 'Your Photo' : 'Artist Photo') ?></strong>
					</h2>
					<!-- INSERT THE PREVIEW FIELDS HERE -->
					<div id="image_preview" style="max-width: 50;max-height: 50;display: flex;">
						<img id="previewing" src="<?php
													if (isset($_SESSION["photo_file_path"])) {
														echo $_SESSION["photo_file_path"];
													}
													?>" />
					</div>
				</div>
				<h6><em>Upload only jpg/png/jpeg file. (Max size : 10MB)</em></h6>
				<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
					<div style="display: flex;">
						<!-- INSERT THE INPUT FIELDS HERE -->
						<!-- INSERT THE "choose file" here FIELDS HERE -->
						<?php
						if (isset($_SESSION["artist_image_present"]) && $_SESSION['artist_image_present'] == '1') {
						?>
							<div style="padding-right: 16px; padding-top: 13px;">
								<label for="image_file_add" class="button medium action_button clrg" style="background-color:#65ba79;">Update Image</label>
								<input type="file" id="image_file_add" name="image_file_add" class="show-for-sr" style="margin-left:-15px;" required />
							</div>
						<?php
						} else {
						?>
							<div style="padding-right: 16px; padding-top: 13px;">
								<label for="image_file_add" class="button medium action_button clrg" style="background-color:#65ba79;">Upload Image</label>
								<input type="file" id="image_file_add" name="image_file_add" class="show-for-sr" style="margin-left:-15px;" required />
							</div>
						<?php
						}
						?>
						<?php echo "<a class='btn btn-primary' href='" . $fbHelper->getLoginUrl(FACEBOOK_PHOTO_SAVER_REDIRECT_URI) . "' role='button'>"; ?>
						<div style="float:left;color:white;background-color:#3b5998;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
							<span><i class="fab fa-facebook-f fa-lg"></i></span>
						</div>
						</a>
						<div style="float:left;height:50px;width:15px;"></div>
						<?php $googleClient->setRedirectUri(GOOGLE_PHOTO_SAVER_REDIRECT_URI); ?>
						<?php echo "<a class='btn btn-primary' href='" . $googleClient->createAuthUrl() . "' role='button'>"; ?>
						<div style="float:left;color:white;background-color:#dd4b39;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
							<span><i class="fab fa-google fa-lg"></i></span>
						</div>
						</a>
						<div style="float:left;height:50px;width:15px;"></div>
						<a class="btn btn-primary" href="#!" role="button" hidden>
							<div style="float:left;color:white;background-color:#bc2a8d;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
								<span><i class="fab fa-instagram fa-lg"></i></span>
							</div>
						</a>
					</div>
				</form>
			</div>
			<!-- <div class="row small-8 columns">
			<div id="image_name">
			</div>
			</div> -->
		</div>
		<div id="social_media_linker_section">
			<div id="social_media_linker_box" class="sectionbox">
				<h2><strong>Link Social Media Profiles</strong></h2>
				<div style="display: flex; justify-content: space-between; width: 115px;">
					<div id="facebook_linker">
						<!-- Facebook -->
						<?php
						// $_SESSION['is_facebook_linked'] is flagged in social_media_linker.php/social_media_unlinker.php
						if (isset($_SESSION['is_facebook_linked']) and $_SESSION['is_facebook_linked']) {
							// onclick event listener in document.ready
							echo '<a id="facebook_unlink">
							<div style="float:left;color:white;background-color:#3b5998;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;border:3px solid #0BDA51;">
								<span><i class="fab fa-facebook-f fa-lg"></i></span>
							</div>
						  </a>';
						} else {
							echo '<a href="' . $fbHelper->getLoginUrl(FACEBOOK_LINKING_MEDIATOR_REDIRECT_URI) . '">';
							echo '
							<div style="float:left;color:white;background-color:#3b5998;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
								<span><i class="fab fa-facebook-f fa-lg"></i></span>
							</div>
						  </a>';
						}
						?>
					</div>
					<!-- <div style="float:left;border: 1px solid grey;height:180px;width:150px;margin:0 0 0 10px;"> -->
					<!-- <div style="padding:30px 50px 30px 50px;"> -->
					<!-- Google -->
					<?php
					// $_SESSION['is_google_linked'] is flagged in social_media_linker.php/social_media_unlinker.php
					// if (isset($_SESSION['is_google_linked']) and $_SESSION['is_google_linked']) {
					// 	// onclick event listener in document.ready
					// 	echo '<a id="google_unlink">
					// 			<div style="float:left;color:white;background-color:#dd4b39;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;border:3px solid #0BDA51;">
					// 				<span><i class="fab fa-google fa-lg"></i></span>
					// 			</div>
					// 		  </a>';
					// } else {
					// 	echo '<a href="' . $fbHelper->getLoginUrl(FACEBOOK_LINKING_MEDIATOR_REDIRECT_URI) . '">';
					// 	echo '
					// 			<div style="float:left;color:white;background-color:#dd4b39;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
					// 				<span><i class="fab fa-google fa-lg"></i></span>
					// 			</div>
					// 		  </a>';
					// }
					?>
					<!-- </div> -->
					<!-- </div> -->
					<div id="instagram_linker">
						<!-- Instagram -->
						<?php
						// $_SESSION['is_instagram_linked'] is flagged in social_media_linker.php/social_media_unlinker.php
						if (isset($_SESSION['is_instagram_linked']) and $_SESSION['is_instagram_linked']) {
							// onclick event listener in document.ready
							echo '<a id="instagram_unlink">
							<div style="float:left;color:white;background-color:#bc2a8d;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;border:3px solid #0BDA51;">
								<span><i class="fab fa-instagram fa-lg"></i></span>
							</div>
						  </a>';
						} else {
							$igClient->set_redirect_uri(INSTAGRAM_LINKING_MEDIATOR_REDIRECT_URI);
							// echo '<a href="' . $igClient->get_authorize_url() . '">';
							echo '<a href="coming_soon.php">';
							echo '
							<div style="float:left;color:white;background-color:#bc2a8d;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;">
								<span><i class="fab fa-instagram fa-lg"></i></span>
							</div>
						  </a>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div class=" large-4 medium-6 small-6 columns">
			<div class="modal-overlay" id="modal-overlay"></div>
			<div class="modal" id="modal">
				<div style="padding:50px 60px 0px 20px" class="modal-guts">

					<div id="popup_crop">
						<div class="form_crop">
				
							<div style="margin-top: -50px;">
								<h3 style="display:inline">Crop photo</h3>
								<input style="float:right;margin:10px;" type="button" value="Crop Image" class="secondary success button noneofthem" onclick="crop_photo()" />
								<button class="close-button" id="close-button" onclick="closeModal();">X</button>
							</div>
							<img id="cropbox" />
							<form>
								<input type="hidden" id="x" name="x" />
								<input type="hidden" id="y" name="y" />
								<input type="hidden" id="w" name="w" />
								<input type="hidden" id="h" name="h" />
								<input type="hidden" id="photo_url" name="photo_url" />
								<br />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="row">
		<!-- INSERT THE submit here here FIELDS HERE -->
		<div class="small-8 columns">
			<div id="message"></div>
		</div>
	</div>
	</div>
	</div>
	</form>
	</div>
	<div class="row">
		<div id="prev_save_row">
			<div id="prev_button" class="large-2 small-8 columns ">
				<button style="font-style: normal;" class="primary button" id="previous" type="button" onclick="window.open('add_artist_personal_information.php','_self')">
					<span>&lt; Previous</span>
				</button>
			</div>
			<div id="save_and_next" class="large-3 small-8 columns">
				<button style="font-style: normal;" class="primary button" id="next" type="button" onclick="saveAndNext();">
					<span><?php echo (($_SESSION['timeline_flow'] == "view") ? "" : "Save & ") ?>Next &gt;</span>
				</button>
			</div>
		</div>
		<div id="save_and_continue" class="large-4 small-10 columns">
			<button style="font-style: normal;" class="secondary button" id="next1" type="button" onclick="saveAndContribute();">
				<span>Save & Continue Later</span>
			</button>
		</div>
	</div>
</body>

<?php
include 'footer.php';
?>

</html>
<script type="text/javascript">
	$(document).ready(function(e) {
		// check social media link status on login
		<?php
		$artist_profile_id = $_SESSION["artist_profile_id"];

		// check if facebook media already exists
		$query = "SELECT * FROM artist_social
				  WHERE artist_profile_id='$artist_profile_id' AND social_platform='Facebook'";
		$result = mysqli_query($dbc, $query)
			or die('Error querying database.: '  . mysqli_error($dbc));
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			$_SESSION['is_facebook_linked'] = True;
			$the_inner_html = '<a id="facebook_unlink">\
									<div style="float:left;color:white;background-color:#3b5998;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;border:3px solid #0BDA51;">\
										<span><i class="fab fa-facebook-f fa-lg"></i></span>\
									</div>\
							</a>';
			echo '$("#facebook_linker").html(\'' .
				$the_inner_html
				. '\');';
		}

		// check if instagram media already exists
		$query = "SELECT * FROM artist_social
				  WHERE artist_profile_id='$artist_profile_id' AND social_platform='Instagram'";
		$result = mysqli_query($dbc, $query)
			or die('Error querying database.: '  . mysqli_error($dbc));
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			$_SESSION['is_instagram_linked'] = True;
			$the_inner_html = '<a id="instagram_unlink">\
									<div style="float:left;color:white;background-color:#bc2a8d;height:50px;width:50px;padding:15px;border-radius:50%;display:flex;justify-content:center;border:3px solid #0BDA51;">\
										<span><i class="fab fa-instagram fa-lg"></i></span>\
									</div>\
		 					</a>';
			echo '$("#instagram_linker").html(\'' .
				$the_inner_html
				. '\');';
		}


		?>

		// unlink facebook listener
		$("#facebook_unlink").click(function() {
			$.ajax({
				type: 'POST',
				url: 'social_media_unlinker.php',
				data: {
					platform: 'Facebook',
				},
				success: function(data) {
					location.href = 'add_artist_biography.php';
				},
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		});

		// unlink instagram listener
		$("#instagram_unlink").click(function() {
			$.ajax({
				type: 'POST',
				url: 'social_media_unlinker.php',
				data: {
					platform: 'Instagram',
				},
				success: function(data) {
					location.href = 'add_artist_biography.php';
				},
				error: function(xhr, status, error) {
					console.log(xhr);
				}
			});
		});

		if ($("#is_user_artist").val() == 'other') {
			// if($("#profile_complete_status").val() == '0'){
			//     showRefNote();
			// }
			$(".add-reference-button").show();
		} else {
			$(".add-reference-button").hide();
		}
		$('#biography_text').val($('#biography_text').val().trim())

		// $("#bio_message_div").hide();

		// check bio text
		if ($("#biography_text").val().trim() != "") {
			$("#Upload_pdf_section").hide();
			$("#type_section").show();
			$("#type_biography").prop("checked", true);
		}
		// check bio doc
		else {
			$("#Upload_pdf_section").show();
			$("#type_section").hide();
			$("#upload_document").prop("checked", true);
		}

		// function to upload a selected file to the server
		function uploadImageDb() {
			var obj = document.getElementById("uploadimage");
			$("#message").empty();
			$('#loading').show();
			$.ajax({
				url: "photo_upload.php", // Url to which the request is send
				type: "POST", // Type of request to be send, called as method
				data: new FormData(obj), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData: false, // To send DOMDocument or non processed data file it is set to false
				success: function(data) // A function to be called if request succeeds
				{
					$('#loading').hide();
					$("#message").html("<span id='success'>Image Uploaded Successfully...!!</span><br/>");
					// reloadPage();
					console.log(data);
					window.show_popup_crop(data);
				}
			});
		}

		// Function to preview image after validation
		$(function() {
			$("#image_file_add").change(function() {
				$("#message").empty();
				var file = this.files[0];
				var fileName = file.name;
				$("#image_name").text(fileName);
				var imagefile = file.type;
				var match = ["image/jpeg", "image/png", "image/jpg"];
				if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
					$("#image_name").text("");
					$("#message").text("The file you selected was a " + imagefile + " file.  Valid file types are jpeg, jpg, or png.");
					$("#image_file_add").val("");
					return false;
				} else {
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
					// $("#uploadimage").submit();
					uploadImageDb();
				}
			});
		});

		function imageIsLoaded(e) {
			$("#image_file_add").css("color", "green");
			$('#image_preview').css("display", "block");
			$("#image_preview").show();
			$('#previewing').attr('src', e.target.result);
			$('#previewing').attr('width', '250px');
			$('#previewing').attr('height', '230px');
		};

		// function to upload selected file to the server
		$("#upload_biography").on('submit', (function(e) {
			e.preventDefault();
			$("#bio_message").empty();
			$('#loading').show();
			$.ajax({
				url: "biography_upload.php", // Url to which the request is send
				type: "POST", // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData: false, // To send DOMDocument or non processed data file it is set to false
				success: function(data) // A function to be called if request succeeds
				{
					$('#loading').hide();
					$("#bio_message").html(data);
					reloadPage();
				}
			});
		}));

		// To upload and submit a document for bio
		$(function() {
			$("#bio_file_add").change(function() {
				$("#bio_message").empty();
				var file = this.files[0];
				var fileName = file.name;
				$("#bio_file_name").text(fileName);
				var biofile = file.type;
				var match = ["application/pdf"];
				if ($.inArray(biofile, match)) {
					$("#bio_file_name").text("Please upload a file with a valid format");
					$("#bio_file_name").css("color", "red");
					$("#bio_file_add").val("");
					return false;
				} else {
					var reader = new FileReader();
					reader.readAsDataURL(this.files[0]);
					$("#upload_biography").submit();
				}
			});
		});

		// On submit bio
		$("#upload_bigraphy_text").on('submit', (function(e) {
			e.preventDefault();
			$("#biography_text_message").empty();
			//$('#loading').show();
			$.ajax({
				url: "biography_upload.php", // Url to which the request is send
				type: "POST", // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData: false, // To send DOMDocument or non processed data file it is set to false
				success: function(data) // A function to be called if request succeeds
				{
					$("#biography_text_message").show();
					$("#biography_text_message").html(data);
					$("#biography_text").html($("#biography_text").val());
					// reloadPage();
				}
			});
			$('#biography_text').click(function() {
				$("#biography_text_message").hide();
			});
		}));
		if (disabled_input) {
			$('input').attr('disabled', 'true');
			$('textArea').attr('disabled', 'true');
			$(".action_button").hide();
		}
	});

	// Select to upload document for bio
	$("#upload_document").click(function() {
		// $("#bio_message_div").hide();

		console.log($("#bio_file_name").html().trim());
		console.log($("#bio_file_name").val().trim());
		console.log($("#biography_text").html().trim());
		console.log($("#biography_text").val().trim());
		// if(confirm("If there is Biography Text, it would be removed. Do you want to continue?")){
		if ($("#biography_text").val().trim() != "") {
			if (confirm("You have already entered a biography in the text box. Do you wish to replace this text by uploading a biography from a file?")) {
				console.log("remove bio text");
				removeBioText();
				$("#bio_message").html("");
				$("#bio_file_add").val("");
				$("#bio_file_name").text("");
			} else {
				console.log("keep bio text");
				return false;
			}
		}
		$("#Upload_pdf_section").show();
		$("#type_section").hide();
	});

	// Select to write text for bio
	$("#type_biography").click(function() {
		// $("#bio_message_div").hide();

		console.log($("#bio_file_name").html().trim());
		console.log($("#bio_file_name").val().trim());
		console.log($("#biography_text").html().trim());
		console.log($("#biography_text").val().trim());
		// if(confirm("If there is Biography Document, it would be removed. Do you want to continue?")){
		if ($("#bio_file_name").html().trim() != "") {
			if (confirm("You have already attached a biography file. Do you wish to replace this file by entering text in the text box?")) {
				console.log("remove bio document");
				removeBioDoc();
				$("#biography_text").empty();
			} else {
				console.log("keep bio document");
				return false;
			}
		}
		$("#type_section").show();
		$("#Upload_pdf_section").hide();
	});

	function saveAndNext() {
		$("#bio_message").empty();
		if (!validateBioInfo()) {
			// $("#bio_message_div").show();
			window.alert("Please provide biography");
		} else {
			// $("#bio_message_div").hide();
			submitJson(null, 'artistcontroller.php', {
				"action": "addOrEditArtistProfile",
				"artist_profile_id": "<?php echo $_SESSION["artist_profile_id"] ?>",
				"status": "75"
			}, window.open.bind(null, 'add_lineage.php', '_self'));
		}
	}

	function saveAndContribute() {
		$("#bio_message").empty();
		if (!validateBioInfo()) {
			// $("#bio_message_div").show();
			window.alert("Please provide biography");
		} else {
			// $("#bio_message_div").hide();
			submitJson(null, 'artistcontroller.php', {
				"action": "addOrEditArtistProfile",
				"artist_profile_id": "<?php echo $_SESSION["artist_profile_id"] ?>",
				"status": "75"
			}, window.open.bind(null, 'profiles.php', '_self'));
		}
	}


	// Remove image
	function removeImage() {
		console.log("remove image");
		$("#message").empty();
		$('#loading').show();
		$.ajax({
			url: "photo_upload.php", // Url to which the request is send
			type: "POST", // Type of request to be send, called as method
			data: {
				"action": "delete"
			}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false, // The content type used when sending data to the server.
			cache: false, // To unable request pages to be cached
			processData: false, // To send DOMDocument or non processed data file it is set to false
			success: function(data) // A function to be called if request succeeds
			{
				$('#loading').hide();
				$("#message").html(data);
				$("#image_file_add").val("");
				$("#image_name").text("");
				$("#image_preview").hide();
			}
		});
	}

	// Remove document from bio
	function removeBioDoc() {
		console.log("remove bio");
		$("#bio_message").empty();
		$('#loading').show();
		$.ajax({
			url: "biography_upload.php",
			type: "POST",
			data: {
				"action": "delete",
				"field": 'artist_biography'
			},
			success: function(data) {
				$('#loading').hide();
				$("#bio_message").html(data);
				$("#bio_file_add").val("");
				$("#bio_file_name").text("");
			}
		});
	}

	// Remove text from bio
	function removeBioText() {
		console.log("remove bio");
		$("#biography_text").empty();
		$("#biography_text").val('');
		$('#loading').show();
		$.ajax({
			url: "biography_upload.php",
			type: "POST",
			data: {
				"action": "delete",
				"field": 'artist_biography_text'
			},
			success: function(data) {
				$('#loading').hide();
				$("#biography_text").html(data);
			}
		});
	}

	function reloadPage() {
		location.reload(true);
	}

	// Check if bio text is filled or bio pdf doc is uploaded
	function validateBioInfo() {

		for (var i = 0; i < admin_result.length; i++) {
			if (admin_result[i]["feature_id"] == 11) {
				if (admin_result[i]["feature_enabled"] == 0) {

					return true;

				}
			}
		};

		if (($("#bio_file_name").html().trim() == "") && ($("#biography_text").val().trim() == "")) {
			console.log('bio not valid')
			return false;
		}
		console.log('bio valid')
		console.log($("#bio_file_name").html().trim())
		console.log($("#biography_text").val().trim())
		return true;
	}
</script>

<script>
	for (var i = 0; i < admin_result.length; i++) {
		if (admin_result[i]["feature_id"] == 11) {
			if (admin_result[i]["feature_enabled"] == 0) {

				$("#bio_div").hide();

			}
		}
	};

	for (var i = 0; i < admin_result.length; i++) {
		if (admin_result[i]["feature_id"] == 13) {
			if (admin_result[i]["feature_enabled"] == 0) {

				$("#photo_div").hide();

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
						"data":{'user': '<?php echo($_SESSION['user_id']);?>', 'oper': 'Edited biography information', 'det': '<?php echo $_SESSION["artist_profile_id"] ?>'}
					}),
					success:function(){
					}
				})
			}
		}

		
</script>