<?php
require 'util.php';
require_once 'config.php';  // for getting facebook client
my_session_start();

require 'connect.php';

echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';

if (isset($_GET['code'])) {
	$accessToken = $fbHelper->getAccessToken();
    $oAuth2Client = $fbClient->getOAuth2Client();
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    $response = $fbClient->get("/me?fields=link", $accessToken);
    $fbUserData = $response->getGraphNode()->asArray();

	$link_url = $fbUserData['link'];

	// save to db
	echo("<script>
	$.ajax({
		type: 'POST',
		url: 'social_media_linker.php',
		data: {
			platform: 'Facebook',
			link_url: '$link_url'
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