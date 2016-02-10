<?php

header('Content-Type: application/json');

require_once('mongoconfig.php');
require_once('config.php');
require_once('rulesManager.php');


$rulesManager = new RulesManager($config);

$inputEvent = $_POST['inputEvent'];
$user = $_POST['user'];

$rulesArray = $rulesManager->getRulesByUser(RULES_FORMAT_RAW, $user); 
$rules = "";
foreach ($rulesArray as $rule) {
	$rules = $rules . "\r" . $rule;
}

//$inputEvent = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> . @prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> . @prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> . @prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> . ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor ewe:sensorID "A1B2C3". ewe-presence:PresenceSensor ewe:distance 3.2139448147463336. ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor ewe:sensorID "D4E5F6". ewe-presence:PresenceSensor ewe:distance 9.755085593371165. ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor ewe:sensorID "G7H8I9". ewe-presence:PresenceSensor ewe:distance 5.25348614913399.';
/*$inputEvent = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .
@prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .

ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance.
ewe-presence:PresenceSensor ewe:sensorID "1a2b3c".
ewe-presence:PresenceSensor ewe:distance 0.5.
ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance.
ewe-presence:PresenceSensor ewe:sensorID "d4e5f6".
ewe-presence:PresenceSensor ewe:distance 0.4.';*/

$response = evaluateEvent($inputEvent, $rules);

$responseJSON = parseResponse($inputEvent, $response);

echo json_encode($responseJSON);

function evaluateEvent($input, $rules){
		
	$data = array(
		'data' => array($rules, $input),
		'query' => '{ ?a ?b ?c. } => { ?a ?b ?c. }.'
	);

	$url = EYE_SERVER;

	$ch = curl_init($url);

	$postString = http_build_query($data, '', '&');

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

function parseResponse($input, $response){
	
	// REMOVE PREFIXES.
	while(strpos($response, 'PREFIX') !== false){
		$response = delete_all_between('PREFIX', '>', $response);
	}

	while(strpos($input, '@prefix') !== false){
        $input = delete_all_between('@prefix', '> .', $input);
	}

	// REMOVE COMMENTS.
	while(strpos($input, '#C') !== false){
        $input = delete_all_between('#C', 'C#', $input);
	}

	// CHANGE RDF:TYPE BY A
	$input = str_replace('rdf:type', 'a', $input);

	// REMOVE BLANK SPACES AND BREAKPOINTS
	$input = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input);
	$response = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $response);

	// SPLIT IN SENTENCES
	$splittedInput = array_filter(explode(".", trim($input)));

	$splittedResponse = array_filter(explode(".", trim($response)));

	// REMOVE INPUT FROM RESPONSE.
	for($i = 0; $i < count($splittedInput); $i++){
	  $splittedInput[$i] = trim($splittedInput[$i]);
	  for($j = 0; $j < count($splittedResponse); $j++){
	    $splittedResponse[$j] = trim($splittedResponse[$j]);
	    if($splittedInput[$i] == $splittedResponse[$j]){
	      unset($splittedResponse[$j]);
	    }
	  }
	}

	$splittedResponse = array_values($splittedResponse);
	$splittedResponse = array_filter($splittedResponse);

	// SPLIT ACTIONS AND PARAMETERS.
	$parameters = array();
	$actions = array();
	for($j = 0; $j<count($splittedResponse); $j++){
		//echo $splittedResponse[$j] . "</br>";
		if(strpos($splittedResponse[$j], 'ov:')){
			//$splittedResponse[$j] = delete_all_between('ov:', ' ', $splittedResponse[$j]);
			array_push($parameters, $splittedResponse[$j]);
			continue;
		} 
		array_push($actions, $splittedResponse[$j]);
	}

	// SPLIT ACTIONS.
	$actionsJson = array('success' => 1);
	$actionsJson['actions'] = array();

	for($j = 0; $j<count($actions); $j++){
		//echo $splittedResponse[$j] . "</br>";
		$response =  preg_split("/[\s,]+/", trim($actions[$j]));
		$action['channel'] = str_replace(':', '', strstr($response[0], ':'));
		$action['action'] = str_replace(':', '', strstr($response[2], ':'));
		$action['parameter'] = "";
		for($h = 0; $h<count($parameters); $h++){
			$paramText = "";
			$spaceCount = 0;
			for($i = 0; $i<strlen($parameters[$h]); $i++){
				$parameters[$h] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $parameters[$h]);//error_log($parameters[$h]);
				if(ctype_space($parameters[$h][$i])){
					$spaceCount++;
				}
				if($spaceCount>=2){
					//error_log($parameters[$h][$i]);
					if($parameters[$h][$i]=="\"") continue;
					$paramText = $paramText . $parameters[$h][$i];
				} 
			}
			$parameter = preg_split("/[\s,]+/", trim($parameters[$h]));
			$paramChannel = str_replace(':', '', strstr($parameter[0], ':'));
			if($action['channel']==$paramChannel){
				//echo 'AADIOS ' .$paramChannel . "</br>";
				//echo 'HOLA ' . $parameter[2];
				$action['parameter'] = trim($paramText);
				break;
			}
		}

		array_push($actionsJson['actions'], $action);
	}

	return $actionsJson;
}

function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, '', $string);
}

?>
