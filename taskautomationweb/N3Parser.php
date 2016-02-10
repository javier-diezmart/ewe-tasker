<?php

$response = 'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#>
PREFIX ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#>
PREFIX string: <http://www.w3.org/2000/10/swap/string#>
PREFIX math: <http://www.w3.org/2000/10/swap/math#>
PREFIX ewe-smarttv: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-smarttv/ns/#>
PREFIX ov: <http://vocab.org/open/#>
PREFIX ewe-door: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-door/ns/#>

ewe-presence:PresenceSensor a ewe-presence:PresenceDetectedAtDistance.
ewe-smarttv:SmartTv a ewe-smarttv:SwitchOn.
ewe-door:DoorLock a ewe-door:OpenDoor.
ewe-presence:PresenceSensor ewe:sensorID "1a2b3c".
ewe-presence:PresenceSensor ewe:distance 0 .
ewe-smarttv:SmartTv ov:MicroBlogPost "I just got to the lab".';

//echo "\n" . 'RESPONSE SIN PARSEAR:' . "\n" . $response;

while(strpos($response, 'PREFIX') !== false){
	$response = delete_all_between('PREFIX', '>', $response);
}

$input = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .
@prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .

ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance. #C Event of type PresenceDetectedAtDistance.C#
ewe-presence:PresenceSensor ewe:sensorID "1a2b3c". #C Asign the sensor ID.C#
ewe-presence:PresenceSensor ewe:distance 0. #C Asign the distance to the sensor. C#';

//echo "\n" . 'INPUT  SIN PARSEAR: '. "\n";
//echo $input;
while(strpos($input, '@prefix') !== false){
        $input = delete_all_between('@prefix', '> .', $input);
}

//echo "\n" . 'INPUT PARSEADO 1: '. "\n";
//echo $input;
while(strpos($input, '#C') !== false){
        $input = delete_all_between('#C', 'C#', $input);
}

//echo "\n" . 'INPUT PARSEADO 2: '. "\n";
//echo $input;

$input = str_replace('rdf:type', 'a', $input);
//echo "\n" . 'INPUT PARSEADO 3: '. "\n";
//echo $input;
$input = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input);
$response = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $response);

//echo "\n" . 'INPUT PARSEADO 4: '. "\n";
//echo $input;
$splittedInput = array_filter(explode(".", trim($input)));

$splittedResponse = array_filter(explode(".", trim($response)));

/*echo "\n" . 'RESPONSE PARSEADO: '. "\n";
for($i = 0; $i < count($splittedResponse); $i++){
    echo $splittedResponse[$i];
}*/

/*echo "\n" . 'INPUT ANTES PARSEO: '. "\n";
for($i = 0; $i < count($splittedInput); $i++){
    echo $splittedInput[$i] . "\n";
}
echo count($splittedInput);
for($i=0; $i<count($splittedInput); $i++){
  $splittedInput[$i] = trim($splittedInput[$i]);
}

$splittedInput = array_values($splittedInput);

echo count($splittedInput);

echo "\n" . 'INPUT PARSEADO: '. "\n";
for($i = 0; $i < count($splittedInput); $i++){
    echo $splittedInput[$i] . "\n";
}*/
//echo count($splittedResponse);

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

    /*echo "\n" . 'BUSCANDO: ' .$splittedInput[$i];
    if(strpos($response, $splittedInput[$i]) !== false){
      $response = str_replace($splittedInput[$i], "", $response);
      echo "\n" .'New response:' . "\n" .$response;
    }
}

*/echo "\n" . 'RESPONSE PARSEADO: '. "\n";

$responses = array();
for($j = 0; $j < count($splittedResponse); $j++){
    if($splittedResponse[$j] == "" || strpos($splittedResponse[$j], 'ov:')) continue;
    $response =  preg_split("/[\s,]+/", trim($splittedResponse[$j]));
    $h = 1;
    while(strpos($splittedResponse[$j+$h], 'ov:')){
      array_push($response, $splittedResponse[$j+$h]);
      $h++;
    }
    array_push($responses, $response);
}

for($j = 0; $j < count($responses); $j++){
    $response = $responses[$j];
    for($i = 0; $i < count($response); $i++){
      $response[$i] = trim($response[$i]);
      if($response[$i]=="") continue;
      echo $response[$i] . "\n";
    }
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
