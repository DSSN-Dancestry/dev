<?php
require 'util.php';
require_once 'config.php';  // for getting google client
my_session_start();

require 'connect.php';

echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';

if (isset($_GET['code'])) {
	$googleClient->setRedirectUri(GOOGLE_PHOTO_SAVER_REDIRECT_URI);
	$token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
	$googleClient->setAccessToken($token['access_token']);

	// get profile info
	$google_oauth = new Google_Service_Oauth2($googleClient);
	$google_account_info = $google_oauth->userinfo->get();

	$photo_url = str_replace('s96-c', 's0',$google_account_info['picture']);
	
	// save to db
	echo("<script>
	$.ajax({
		type: 'POST',
		url: 'social_media_photo_upload.php',
		data: {
			photo_url: '$photo_url'
		},
		success: function(data){
			location.href='add_artist_biography.php';
		},
		error: function(xhr, status, error){
			console.log(xhr);
		}
	});
	</script>");
}