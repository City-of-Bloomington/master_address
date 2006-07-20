<?php
/*
	$_GET variables:	name_id
*/
	$view = new View();
	$view->name = new Name($_GET['name_id']);
	$view->addBlock("names/nameInfo.inc");
	$view->addBlock("names/streets.inc");
	$view->render();
?>