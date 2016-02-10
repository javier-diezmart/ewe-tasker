<?php

	require("twitteroauth/autoload.php");
	use Abraham\TwitterOAuth\TwitterOAuth;
	
	session_start();


	// The TwitterOAuth instance
	$connection = new TwitterOAuth('2XMGw6v0nn4uIyzvXwVqLZaa5', 'WRrhIHl92AkIVEMsyZXkja1N0LkP4aEGpJDjij9Cb2NWD93V1F');
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "http://taskautomationserver.ddns.net/taskautomationweb/twitterback.php"));
//callback is set to where the rest of the script is

//TAKING THE OAUTH TOKEN AND THE TOKEN SECRET AND PUTTING THEM IN COOKIES (NEEDED IN THE NEXT SCRIPT)
$oauth_token=$request_token['oauth_token'];
$token_secret=$request_token['oauth_token_secret'];
setcookie("token_secret", " ", time()-3600);
setcookie("token_secret", $token_secret, time()+60*10);
setcookie("oauth_token", " ", time()-3600);
setcookie("oauth_token", $oauth_token, time()+60*10);

//GETTING THE URL FOR ASKING TWITTER TO AUTHORIZE THE APP WITH THE OAUTH TOKEN
$url = $connection->url("oauth/authorize", array("oauth_token" => $oauth_token));

//REDIRECTING TO THE URL
header('Location: ' . $url);
?>
