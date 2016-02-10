<?php

session_start();

require_once('rulesManager.php');
require_once('channelsManager.php');
require_once('mongoconfig.php'); // Config file with mongo values.

$rulesManager = new RulesManager($config);
$channelsManager = new ChannelsManager($config);

$ruleTitle = $_POST['rule_title'];
$ruleDescription = $_POST['rule_description'];
if(isset($_POST['rule_creator'])) $ruleCreator = $_POST['rule_creator'];
else $ruleCreator = $_SESSION['user'];
$rulePlace = $_POST['rule_place'];

$eventParams = $_POST['event_params'];
$actionParams = $_POST['action_params'];

$eventsAndActions = $rulesManager->getEventAndChannel();
$channelOne = '';
$channelTwo = '';
$eventTitle = '';
$actionTitle = '';

foreach ($eventsAndActions as $obj) {
	if($obj['event'] != ''){
		$eventTitle = $obj['event'];
		$channelOne = $obj['channel'];
	}else if($obj['action'] != ''){
		$actionTitle = $obj['action'];
		$channelTwo = $obj['channel'];
	}
}

$prefixEvent = $channelsManager->getPrefixByEventAndChannel($channelOne, $eventTitle);
$prefixAction = $channelsManager->getPrefixByActionAndChannel($channelTwo, $actionTitle);

$eventRule = $channelsManager->getRuleByEventAndChannel($channelOne, $eventTitle);
$actionRule = $channelsManager->getRuleByActionAndChannel($channelTwo, $actionTitle);

if(is_array($eventParams)){
	for($i = 1; $i<=sizeof($eventParams); $i++){
		$eventRule = str_replace("#PARAM_" . $i . "#", $eventParams[$i-1], $eventRule);
	}
}else{
	$eventRule = str_replace("#PARAM_1#", $eventParams, $eventRule);
}

if(is_array($actionParams)){

	for($i = 1; $i<=sizeof($actionRule); $i++){
		$actionRule = str_replace("#PARAM_" . $i . "#", $actionParams[$i-1], $actionRule);
	}
}else{
	$actionRule = str_replace("#PARAM_1#", $actionParams, $actionRule);
}

$rule= $prefixEvent .  $prefixAction . '{ ' . $eventRule . ' }=>{ ' . $actionRule . '}.';
$rule = str_replace("\n", "", $rule);
$rule = str_replace("\r", "", $rule);
$rule = str_replace("\t", "", $rule);
error_log($rule);
$rule = str_replace("\\\"", "", $rule);
error_log($rule);
$rule = stripslashes($rule);
error_log($rule);
$rulesManager->createRule($ruleTitle, $channelOne, $channelTwo, $eventTitle, $actionTitle, $ruleDescription, $ruleCreator, $rulePlace, $rule);

?>