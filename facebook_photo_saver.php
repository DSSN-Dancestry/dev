<?php
require 'util.php';
require_once 'config.php';  // for getting google client
my_session_start();

require 'connect.php';

echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';

if (isset($_GET['code'])) {
	$accessToken = $fbHelper->getAccessToken();
    $oAuth2Client = $fbClient->getOAuth2Client();
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    $response = $fbClient->get("/me?fields=picture.height(1024)", $accessToken);
    $fbUserData = $response->getGraphNode()->asArray();

	$photo_url = $fbUserData['picture']['url'];
	
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

// echo("<script>location.href='add_artist_biography.php'</script>");