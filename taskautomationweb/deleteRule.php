<?php

require_once('./controller/mongoconfig.php'); // Config file with constant values.
require_once('./controller/rulesManager.php'); // Rules Manager Module.

$title = $_GET['ruleTitle'];

$rulesManager = new RulesManager($config);
$resultHTML = $rulesManager->deleteRule($title);

header('Location: ./rules.php');

?>