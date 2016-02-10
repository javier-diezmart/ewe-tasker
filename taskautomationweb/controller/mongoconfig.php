<?php

// MongoDB
$dbhost = '127.0.0.1';
$dbname = 'applicationdb';
$port = '27017';
$username = 'client';
$password = 'gsimongodb2015';

$config = array(
    'username' => $username,
    'password' => $password,
    'dbname'   => $dbname,
    'connection_string'=> sprintf('mongodb://%s:%d/%s', $dbhost,$port,$dbname)
);

?>