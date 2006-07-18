<?php
/*
	$_GET variables:	id
*/
	$view = new View();
	$view->district = new District($_GET['id']);
	$view->addBlock("districts/districtInfo.inc");
	$view->render();
?>