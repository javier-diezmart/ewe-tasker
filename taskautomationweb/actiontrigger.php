<?php

header('Content-Type: application/json');

require_once('./controller/usersManager.php');
require_once('./controller/mongoconfig.php'); // Config file with mongo values.
require("twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$username = $_POST['user'];
$message = $_POST['message'];

$usersManager = new UsersManager($config);

$user = $usersManager->getUser($username);
// The TwitterOAuth instance
$connection = new TwitterOAuth('2XMGw6v0nn4uIyzvXwVqLZaa5', 'WRrhIHl92AkIVEMsyZXkja1N0LkP4aEGpJDjij9Cb2NWD93V1F', $user['twitteraccesstoken'], $user['twittersecrettoken']);

$statues = $connection->post("statuses/update", ["status" => $message]);

?>