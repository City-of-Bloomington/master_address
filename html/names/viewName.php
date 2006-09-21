<?php
/*
	$_GET variables:	name_id
*/
	$view = new View();

	$name = new Name($_GET['name_id']);
	$view->blocks[] = new Block("names/nameInfo.inc",array("name"=>$name));
	$view->blocks[] = new Block("names/streets.inc",array("name"=>$name));
	$view->render();
?>