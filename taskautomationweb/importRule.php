<?php

session_start();

require_once('./controller/mongoconfig.php'); // Config file with constant values.
require_once('./controller/rulesManager.php'); // Rules Manager Module.

$title = $_GET['ruleTitle'];

$rulesManager = new RulesManager($config);

if($_GET['action']=='import') $resultHTML = $rulesManager->importRule($title, $_SESSION["user"]);
else if($_GET['action']=='noimport') $resultHTML = $rulesManager->noimportRule($title, $_SESSION["user"]);

header('Location: ./rules.php');

?>