<?php

// Files needed.
require_once('config.php'); // Config file with constant values.
require_once('rulesManager.php'); // Rules Manager Module.
require_once('channelsManager.php'); // Rules Manager Module.

class ViewController{

	private $rulesManager;
	private $channelsManager;

	// Get the view.
	public function getView($view){

		require('mongoconfig.php'); // Config file with mongo values.

		switch ($view) {

			case GET_RULES_VIEW:
								
				$rulesManager = new RulesManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
				$resultHTML = $rulesManager->getRulesList(RULES_FORMAT_HTML);
				
				break;
			case GET_RULES_VIEW_USER:
				$rulesManager = new RulesManager($config);
				$resultHTML = $rulesManager->getRulesByUser(RULES_FORMAT_HTML, '');
				break;
			case GET_CHANNELS_VIEW:

				$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
				$resultHTML = $channelsManager->getChannelsList(CHANNELS_FORMAT_HTML);
				break;
			
			case GET_CHANNELS_IMAGES:

				$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
				$resultHTML = $channelsManager->getChannelsList(CHANNELS_FORMAT_IMG);
				break;	
			
			case GET_PLACES_VIEW:
				$rulesManager = new RulesManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
				$resultHTML = $rulesManager->getPlacesList();
				break;

			default:
				# code...					
				break;
		}

		return $resultHTML;
	}

	public function getEventsChannel($channelTitle){
		require('mongoconfig.php'); // Config file with mongo values.

		$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
		$result = $channelsManager->getChannelEvents($channelTitle);

		return $result;
	}

	public function getActionsChannel($channelTitle){
		require('mongoconfig.php'); // Config file with mongo values.

		$channelsManager = new ChannelsManager($config);
				//$rulesManager->createRule('New Rule', 'door', 'tv', 'If I am near the door, turn on tv.', 'Sergio', 'Casa');
		$result = $channelsManager->getChannelActions($channelTitle);

		return $result;
	}

}

?>
