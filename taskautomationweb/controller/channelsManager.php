<?php

require_once('dbHelper.php');
require_once('config.php');

/**
* 
*/
class ChannelsManager
{
	
	function __construct($config)
	{
		$this->connect($config);
	}

	//connecting mongodb
    private function connect($config)
    {
    	$this->db = new DBHelper($config);
    }

    public function getChannelByTitle($title){
        $channel = $this->db->getChannelByTitle($title);
        return $channel;
    }

    public function createChannel($title, $description, $nicename, $actionsTitles, $actionsRules, $actionsPrefix, $actionsNumOfParams, $eventsTitles, $eventsRules, $eventsPrefix, $eventsNumOfParams){
        
        $channel = array(
            'title' => $title,
            'description' => $description,
            'nicename' => $nicename,
            'date' => new MongoDate()
        );
        
        $this->db->removeChannelByTitle($title);
        $this->createActions($title, $actionsTitles, $actionsRules, $actionsPrefix, $actionsNumOfParams);
        $this->createEvents($title, $eventsTitles, $eventsRules, $eventsPrefix, $eventsNumOfParams);
        $this->db->create('channels', $channel);

    }

    public function removeChannelByTitle($title){
        $this->db->removeChannelByTitle($title);
        $this->db->removeRuleByChannelTitle($title);
    }

    public function saveChoosenEvent($channelTitle, $eventTitle){

        $this->db->removeEventChoosen();

        $event = array(
            'event' => $eventTitle,
            'action' => '',
            'channel' => $channelTitle
        );

        $this->db->create('eventsactionschoosen', $event);

    }

    public function saveChoosenAction($channelTitle, $eventTitle){

        $this->db->removeActionChoosen();

        $event = array(
            'action' => $eventTitle,
            'event' => '',
            'channel' => $channelTitle
        );

        $this->db->create('eventsactionschoosen', $event);

    }
    
    private function createActions($channelTitle, $actionsTitles, $actionsRules, $actionsPrefix, $actionsNumOfParams){

        $i = 0;

        foreach ($actionsTitles as $actionTitle) {

            
            if($actionTitle == "") continue;
            
            $action = array(
                'title' => $actionTitle,
                'channelTitle' => $channelTitle,
                'prefix' => $actionsPrefix[$i],
                'num_of_params' => $actionsNumOfParams[$i],
                'rule' => $actionsRules[$i]
            );

            $i++;

            $this->db->create('actions', $action);
        }

    }

    private function createEvents($channelTitle, $eventsTitles, $eventsRules, $eventsPrefix, $eventsNumOfParams){

        $i = 0;

        foreach ($eventsTitles as $eventTitle) {

            if($eventTitle == "") continue;

            $event = array(
                'title' => $eventTitle,
                'channelTitle' => $channelTitle,
                'prefix' => $eventsPrefix[$i],
                'num_of_params' => $eventsNumOfParams[$i],
                'rule' => $eventsRules[$i]
            );

            $i++;
            
            $this->db->create('events', $event);
        }

    }

    // Get all the channels available, in different formats.
    public function getChannelsList($format){

        $channels = $this->db->get('channels');
        $allChannels = array();

        switch ($format) {

            case CHANNELS_FORMAT_HTML:
                
                foreach ($channels as $channel) {
                    $channelHTML = $this->getHTMLChannel($channel['title'], $channel['nicename'], $channel['description']);

                    array_push($allChannels, $channelHTML);

                }

                break;
            
            case CHANNELS_FORMAT_IMG:
                foreach ($channels as $channel) {

                    $channelHTML = $this->getHTMLImageChannel($channel['title']);

                    array_push($allChannels, $channelHTML);
                }
                break;

            case CHANNELS_FORMAT_JSON:

                foreach ($channels as $channel) {
                    $channelJson = array('title' => $channel['title'], 'description' => $channel['description']);
                    $events = $this->getChannelEvents($channel['title']);
                    $channelJson['events'] = array();
                    $channelJson['actions'] = array();
                    foreach ($events as $event) {
                        $eventJson = array('title' => $event['title'], 'prefix' => $event['prefix'], 'num_of_params' => $event['num_of_params'], 'rule' => $event['rule']);
                        array_push($channelJson['events'], $eventJson);
                    }
                    $actions = $this->getChannelActions($channel['title']);
                    foreach ($actions as $action) {
                        $actionJson = array('title' => $action['title'], 'prefix' => $action['prefix'], 'num_of_params' => $action['num_of_params'], 'rule' => $action['rule']);
                        array_push($channelJson['actions'], $actionJson);
                    }
                    array_push($allChannels, $channelJson);
                }

                break;

            default:
                # code...
                break;
        }

        return $allChannels;
    }

    // Get channel events.
    public function getChannelEvents($channelTitle){
        $events = $this->db->getEventsByChannel($channelTitle);
        return $events;
    }

    // Get channel actions.
    public function getChannelActions($channelTitle){
        $events = $this->db->getActionsByChannel($channelTitle);
        return $events;
    }

    public function getPrefixByEventAndChannel($channelTitle, $event){
        $event = $this->db->getRuleByEventAndChannel($channelTitle, $event);
        foreach($event as $e){
            return $e['prefix'];
        }
        
    }

    public function getRuleByEventAndChannel($channelTitle, $event){
        $event = $this->db->getRuleByEventAndChannel($channelTitle, $event);
        foreach($event as $e){
            return $e['rule'];
        }
        
    }

    public function getPrefixByActionAndChannel($channelTitle, $action){
        $action = $this->db->getRuleByActionAndChannel($channelTitle, $action);
        foreach($action as $a){
            return $a['prefix'];
        }
    }

    public function getRuleByActionAndChannel($channelTitle, $action){
        $action = $this->db->getRuleByActionAndChannel($channelTitle, $action);
        foreach($action as $a){
            return $a['rule'];
        }
    }
    // Get a HTML view for a Channel.

    private function getHTMLChannel($channelTitle, $nicename, $description){

		$channelHTML = '
					<!-- Channel Item -->
                    <div class="row channel-item">

                        <div class="col-md-2 col-md-offset-1">
                            <div class="channel-fragment">
                                <!--<input type="submit" Value="Ok" style="vertical-align: middle"></input>-->
                                <img class="img img-circle img-responsive img-channel" src="img/' . $channelTitle . '.png" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="channel-title">' . $nicename . '</h3>
                            <p class="channel-description">' . $description . '</p>
                        </div>
                        <div class="col-md-2">
                            <div class="channel-fragment">
                                <button onclick="location.href=\'./editChannel.php?channelTitle=' . $channelTitle . '\'" type="button" class="btn btn-info btn-channel-action">Edit</button>
                                <button onclick="location.href=\'./deleteChannel.php?channelTitle=' . $channelTitle . '\'"type="button" class="btn btn-danger btn-channel-action">Delete</button>
                            </div>
                        </div>
                    </div>  <!-- row -->
                    ';

        return $channelHTML;
    }

    // Get a HTML image view for a Channel.

    private function getHTMLImageChannel($channelTitle){

        $channelHTML = '
                    <!-- Channel Item -->
                    <div class="col-md-2">

                        <div class="channel-fragment">
                            <img id="' . $channelTitle . '" draggable="true" class="img img-circle img-responsive img-channel" src="img/'. $channelTitle .'.png" />
                        </div>  

                    </div>
                    ';

        return $channelHTML;
    }

}
?>