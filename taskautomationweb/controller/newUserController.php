<?php

session_start();
require_once('usersManager.php');
require_once('mongoconfig.php'); // Config file with mongo values.

$usersManager = new UsersManager($config);

$username = $_POST['username'];
$password = $_POST['password'];

if ($_POST['action']=='Login') {
    $success = $usersManager->logUser($username, $password);
    $error = 2;
} else if ($_POST['action']=='Sign Up') {
    $success = $usersManager->createUser($username, $password);
    $error = 1;
}

if($success){
	$_SESSION['user'] = $username;
	header("Location: ../user.php");
}
else header("Location: ../user.php?error=" . $error);
die();

?>
