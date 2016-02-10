<?php
require_once('./controller/usersManager.php');
require_once('./controller/mongoconfig.php'); // Config file with mongo values.
	require("twitteroauth/autoload.php");
	use Abraham\TwitterOAuth\TwitterOAuth;
	
	session_start();
$usersManager = new UsersManager($config);

	// The TwitterOAuth instance
	$connection = new TwitterOAuth('2XMGw6v0nn4uIyzvXwVqLZaa5', 'WRrhIHl92AkIVEMsyZXkja1N0LkP4aEGpJDjij9Cb2NWD93V1F');
//GETTING ALL THE TOKEN NEEDED
$oauth_verifier = $_GET['oauth_verifier'];
$token_secret = $_COOKIE['token_secret'];
$oauth_token = $_COOKIE['oauth_token'];

//EXCHANGING THE TOKENS FOR OAUTH TOKEN AND TOKEN SECRET
$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $token_secret);
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));

$accessToken=$access_token['oauth_token'];
$secretToken=$access_token['oauth_token_secret'];

$usersManager->updateTwitter($_SESSION['user'], $accessToken, $secretToken);
//DISPLAY THE TOKENS
header('Location: ./user.php');
?>
