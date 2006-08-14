<?php
/*
	$_GET variables:	street_id
*/
	$view = new View();
	$view->street = new Street($_GET['street_id']);
	$view->response = new URL($_SERVER['REQUEST_URI']);

	$view->addBlock("streets/streetInfo.inc");
	$view->addBlock("streets/streetNames.inc");
	$view->addBlock("streets/segments.inc");

	$view->render();
?>