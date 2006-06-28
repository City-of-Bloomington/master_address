<?php
/*
	$_GET variables:	userID
*/
	verifyUser("Administrator","Supervisor");

	$user = new User($PDO,$_GET['userID']);
	$user->delete();

	Header("Location: home.php");
?>
