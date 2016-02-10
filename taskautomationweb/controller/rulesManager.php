<?php
session_start();
require_once('dbHelper.php');
require_once('config.php');

/**
* 
*/
class RulesManager
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

    public function createRule($title, $channelOne, $channelTwo, $eventTitle, $actionTitle, $description, $creator, $place, $rule){
        
        $rule = array(
            'title' => $title,
            'channelOne' => $channelOne,
            'channelTwo' => $channelTwo,
            'eventTitle' => $eventTitle,
            'actionTitle' => $actionTitle,
            'description' => $description,
            'creator' => $creator,
            'place' => $place,
            'rule' => $rule,
            'date' => new MongoDate()
        );

        $this->db->create('rules', $rule);

    }

    public function deleteRule($title, $user){
        $this->db->removeRuleByTitle($title, $user);
    }

    public function getEventAndChannel(){
        $eventsAndChannels = $this->db->get('eventsactionschoosen');

        return $eventsAndChannels;
    }

    // Get all the rules available, in different formats.

    public function getRulesList($format){

    	$rules = $this->db->get('rules');
    	$allRules = array();

    	switch ($format) {

    		case RULES_FORMAT_HTML:
    			
				foreach ($rules as $rule) {
                    //echo $rule['channelOne'];
					$ruleHTML = $this->getHTMLRule($rule['title'], $rule['channelOne'], $rule['channelTwo'], $rule['description'], 
						$rule['creator'], $rule['place'], $rule['date']);

					array_push($allRules, $ruleHTML);

				}

    			break;
    		
    		default:
    			# code...
    			break;
    	}

    	return $allRules;

    }

    public function getRulesByUser($format, $user){
        if($user=='') $rules = $this->db->getRulesByUser($_SESSION['user']);
        else $rules = $this->db->getRulesByUser($user);
        $allRules = array();

        switch ($format) {

            case RULES_FORMAT_HTML:
                
                foreach ($rules as $rule) {
                    //echo $rule['channelOne'];
                    $ruleHTML = $this->getHTMLRule($rule['title'], $rule['channelOne'], $rule['channelTwo'], $rule['description'], 
                        $rule['creator'], $rule['place'], $rule['date']);

                    array_push($allRules, $ruleHTML);

                }

                break;
                
            case RULES_FORMAT_RAW:

                foreach ($rules as $rule) {
                    //echo $rule['channelOne'];
                    array_push($allRules, $rule['rule']);
                }

                break;

            default:
                # code...
                break;
        }

        return $allRules;

    }

    public function getRulesByPlace($format, $place){

        $rules = $this->db->getRulesByPlace($place);
        $allRules = array();

        switch ($format) {

            case RULES_FORMAT_HTML:
                
                foreach ($rules as $rule) {
                    //echo $rule['channelOne'];
                    $ruleHTML = $this->getHTMLRule($rule['title'], $rule['channelOne'], $rule['channelTwo'], $rule['description'], 
                        $rule['creator'], $rule['place'], $rule['date']);

                    array_push($allRules, $ruleHTML);

                }

                break;
            
            default:
                # code...
                break;
        }

        return $allRules;

    }

    public function getPlacesList(){
        $rules = $this->db->get('rules');
        $allPlaces = array();
        $allPlacesTitle = array();

        foreach ($rules as $rule) {
            $place = $rule['place'];
            if(in_array($place, $allPlacesTitle)) continue;
            array_push($allPlacesTitle, $place);
            $placeHTML = '<option>' . $place . '</option>';
            array_push($allPlaces, $placeHTML);

        }
        
        return $allPlaces;
    }

    // Import rule.
    public function importRule($title, $user){
        $rule = array(
            'title' => $title,
            'user' => $user
        );

        $this->db->create('rules_users', $rule);
    }

    // NoImport rule.
    public function noimportRule($title, $user){
        $this->db->noImport($title, $user);
    }

    // Get a HTML view for a Rule.

    private function getHTMLRule($title, $channelOne, $channelTwo, $description, $creator, $place, $date){

		$alreadyImported = $this->db->alreadyImported($title, $_SESSION['user']);
        $buttonImport = '<button onclick="location.href=\'./importRule.php?ruleTitle=' . $title . '&action=noimport\'"type="button" class="btn btn-primary btn-info">Imported</button>';
        if($creator==$_SESSION['user'] || $_SESSION['user']=='admin') $buttonRemove = '<button onclick="location.href=\'./deleteRule.php?ruleTitle=' . $title . '\'"type="button" class="btn btn-danger btn-rules-action">Delete</button>';
        else $buttonRemove = '';
        if(!$alreadyImported) $buttonImport = '<button onclick="location.href=\'./importRule.php?ruleTitle=' . $title . '&action=import\'"type="button" class="btn btn-primary btn-activate">Import</button>';
        $ruleHTML = '
					<!-- Rule Item -->
                    <div style="margin-bottom:100px;"class="row rule-item">
                        <div class="col-md-1 col-md-offset-1">
                            <div class="rule-fragment">
                                '.$buttonImport.'
                            </div>
                            
                        </div>
                        <div class="col-md-2">
                            <div class="rule-fragment">
                                <img class="img img-circle img-responsive img-channel" src="img/' . $channelOne . '.png" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="rule-fragment">
                                <img class="img img-circle img-responsive img-arrow" src="img/arrow.png" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="rule-fragment">
                                <img class="img img-circle img-responsive img-channel" src="img/' . $channelTwo . '.png" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="rule-info rule-fragment">
                                <p class="written-rule">' . $description .'</p>
                                <p class="rule-creator">' . $creator .'</p>
                                <p class="rule-place">' . $place .'</p>
                                <p class="rule-place">' . date('Y-m-d H:i:s', $date->sec) .'</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="rule-fragment">
                                <button type="button" class="btn btn-info btn-rules-action">Edit</button>
                                '.$buttonRemove.'
                            </div>
                            
                        </div>
                    </div>  <!-- row -->
                    ';

        return $ruleHTML;
    }

}
?>
