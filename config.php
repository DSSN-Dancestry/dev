<?php
if (empty($_SESSION)) {
    session_start();
}

$host = '/choreographiclineage/';


// Redirect URI's
define('GOOGLE_LOGIN_MEDIATOR_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'google_login_mediator.php');
define('FACEBOOK_LOGIN_MEDIATOR_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'facebook_login_mediator.php');
define('INSTAGRAM_LOGIN_MEDIATOR_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'instagram_login_mediator.php');
define('GOOGLE_PHOTO_SAVER_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'google_photo_saver.php');
define('FACEBOOK_PHOTO_SAVER_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'facebook_photo_saver.php');
define('FACEBOOK_LINKING_MEDIATOR_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'facebook_linking_mediator.php');
define('INSTAGRAM_LINKING_MEDIATOR_REDIRECT_URI', 'https://' . $_SERVER['HTTP_HOST'] . $host . 'instagram_linking_mediator.php');


// old
// Google App details
// define('GOOGLE_CLIENT_ID', '654941711081-apv13emirov7i374iqav9sid7u3jv8qj.apps.googleusercontent.com');
// define('GOOGLE_CLIENT_SECRET', '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');


// new
define('GOOGLE_CLIENT_ID', '781023168143-030mm5gbk1gq14ujlvjh7a78548le5hu.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');



// old
// define('FACEBOOK_APP_ID', '447188680411234');
// define('FACEBOOK_APP_SECRET', 'c32c07015745d0b75c2e918398b740a6');
// define('DEFAULT_GRAPH_VERSION', 'v2.5');

// Facebook App details - new and working
// define('FACEBOOK_CLIENT_ID', '2330660060421716');
// define('FACEBOOK_CLIENT_SECRET', 'dd5a76dd8aa5bfe33141a7ce387dfa3a');
// define('FACEBOOK_API_VERSION', 'v2.10');

//Facebook latest from Melanie Account
define('FACEBOOK_CLIENT_ID', '730947018509684');
define('FACEBOOK_CLIENT_SECRET', '75d073b35d119761b777241292fd82d6');
define('FACEBOOK_API_VERSION', 'v16.0');

// Instagram App details
// define('INSTAGRAM_CLIENT_ID', '661773478429158');
// define('INSTAGRAM_CLIENT_SECRET', '2d23436320d615fb0e82881a1f5965ff');

// New Instagram app details with Melanie Account
define('INSTAGRAM_CLIENT_ID', '2368411743332273');
define('INSTAGRAM_CLIENT_SECRET', 'a3f246280be693ef0bfdb72719b67507');

require_once 'vendor/autoload.php';  // for creating google client, facebook client

// create google client
$googleClient = new Google_Client();
$googleClient->setClientId(GOOGLE_CLIENT_ID);
$googleClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$googleClient->addScope("email");
$googleClient->addScope("profile");

// create facebook client - new and working
$fbClient = new Facebook\Facebook([
    'app_id' => FACEBOOK_CLIENT_ID,
    'app_secret' => FACEBOOK_CLIENT_SECRET,
    'default_graph_version' => FACEBOOK_API_VERSION,
]);
$fbHelper = $fbClient->getRedirectLoginHelper();
if (isset($_GET['state'])) {
    $_SESSION['FBRLH_state'] = $_GET['state'];
}

// create instagram client
use Instagram\Instagram;

$igClient = new Instagram(INSTAGRAM_CLIENT_ID, INSTAGRAM_CLIENT_SECRET);
$igClient->set_scope('user_profile');

// old
// $fb = new Facebook\Facebook([
//     'app_id' => FACEBOOK_APP_ID,
//     'app_secret' => FACEBOOK_APP_SECRET,
//     'default_graph_version' => DEFAULT_GRAPH_VERSION,
// ]);
// $helper = $fb->getRedirectLoginHelper();
