<?php

require_once('./controller/mongoconfig.php'); // Config file with constant values.
require_once('./controller/channelsManager.php'); // Rules Manager Module.

$title = $_GET['channelTitle'];

$channelsManager = new ChannelsManager($config);
$resultHTML = $channelsManager->removeChannelByTitle($title);

header('Location: ./channels.php');

?>