<?php

// Neccesary files.
require_once('./controller/config.php');
require_once('./controller/viewController.php');

$viewController = new ViewController();
$events = $viewController->getEventsChannel($channelTitle);

$eventsTitles = array();
foreach ($events as $event) {
	$eventTitle = array('eventTitle' => $event['title']);
	array_push($eventsTitles, $eventTitle);
}

echo json_encode($eventsTitles);

?>