<?php

// Neccesary files.
require_once('./controller/config.php');
require_once('./controller/viewController.php');
require_once('./controller/channelsManager.php'); // Rules Manager Module.
require_once('./controller/rulesManager.php'); // Rules Manager Module.
require('./controller/mongoconfig.php'); // Config file with mongo values.

$command = $_POST['command'];

switch($command){

	case 'getEvents':

		$channelTitle = $_POST['channelTitle'];

		$viewController = new ViewController();
		$events = $viewController->getEventsChannel($channelTitle);

		$eventsTitles = array();
		foreach ($events as $event) {
			$eventTitle = array('eventTitle' => $event['title'], 'numberOfParams' => $event['num_of_params']);
			array_push($eventsTitles, $eventTitle);
		}

		echo json_encode($eventsTitles);

		break;

	case 'getActions':

		$channelTitle = $_POST['channelTitle'];

		$viewController = new ViewController();
		$events = $viewController->getActionsChannel($channelTitle);

		$eventsTitles = array();
		foreach ($events as $event) {
			$eventTitle = array('eventTitle' => $event['title'],'numberOfParams' => $event['num_of_params']);
			array_push($eventsTitles, $eventTitle);
		}

		echo json_encode($eventsTitles);

		break;

	case 'saveEvent':
		$channelTitle = $_POST['channelTitle'];
		$eventTitle = $_POST['eventTitle'];

		$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
		$channelsManager->saveChoosenEvent($channelTitle, $eventTitle);

		break;

	case 'saveAction':
		$channelTitle = $_POST['channelTitle'];
		$eventTitle = $_POST['eventTitle'];

		$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
		$channelsManager->saveChoosenAction($channelTitle, $eventTitle);

		break;

	case 'getRulesByPlace':

		$place = $_POST['place'];

		$rulesManager = new RulesManager($config);
		if($place=='no_filter'){
			
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
			echo json_encode($rulesManager->getRulesList(RULES_FORMAT_HTML), JSON_UNESCAPED_SLASHES);
		}
		//$rulesManager = new RulesManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
		echo json_encode($rulesManager->getRulesByPlace(RULES_FORMAT_HTML, $place), JSON_UNESCAPED_SLASHES);
		break;
}

?>