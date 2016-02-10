<?php

require_once('channelsManager.php');
require_once('mongoconfig.php'); // Config file with mongo values.

$channelsManager = new ChannelsManager($config);

$channelTitle = $_POST['channel_title'];
$channelDescription = $_POST['channel_description'];
$channelNicename = $_POST['channel_nicename'];

$actionsTitles = $_POST['action_title'];
$actionsRules = $_POST['action_rule'];
$actionsPrefix = $_POST['action_prefix'];

$eventsTitles = $_POST['event_title'];
$eventsRules = $_POST['event_rule'];
$eventsPrefix = $_POST['event_prefix'];

$target_dir = "../img/";
$target_file = $target_dir . $channelTitle . '.png';

$actionsNumOfParams = array();
$eventsNumOfParams = array();

foreach($actionsRules as $actionRule){
	$paramText = '#PARAM_1#';
	$i = 1;
	while(strpos($actionRule, $paramText) !== false){
		$i++;
		$paramText = substr($paramText, 0, -2);
		$paramText = $paramText . $i . '#';
	}
	$i--;
	array_push($actionsNumOfParams, $i);
}

foreach($eventsRules as $eventRule){
	$paramText = '#PARAM_1#';
	$i = 1;
	while(strpos($eventRule, $paramText) !== false){
		$i++;
		$paramText = substr($paramText, 0, -2);
		$paramText = $paramText . $i . '#';
	}
	$i--;
	array_push($eventsNumOfParams, $i);
}

move_uploaded_file($_FILES["channel_img"]["tmp_name"], $target_file);

$channelsManager->createChannel($channelTitle, $channelDescription, $channelNicename, $actionsTitles, $actionsRules, $actionsPrefix, $actionsNumOfParams, $eventsTitles, $eventsRules, $eventsPrefix, $eventsNumOfParams);

header("Location: ../channels.php");

die();

?>