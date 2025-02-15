<?php
require 'util.php';
require_once 'config.php';  // for getting instagram client
my_session_start();

require 'connect.php';

echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';

if (isset($_GET['code'])) {
	$igClient->set_redirect_uri(INSTAGRAM_LINKING_MEDIATOR_REDIRECT_URI);
	$token = $igClient->get_access_token($_GET['code']);
    $igClient->set_access_token($token->access_token);
    $ig_user_id = strval($token->user_id);
	$token = ($igClient->get_long_lived_token())->access_token;
	$ig_user = $igClient->get_user($ig_user_id);

	$link_url = 'https://www.instagram.com/'.$ig_user->username;

	// save to db
	echo("<script>
	$.ajax({
		type: 'POST',
		url: 'social_media_linker.php',
		data: {
			platform: 'Instagram',
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