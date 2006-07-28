<?php
/*
	$_GET variables:	place_id
*/
	$view = new View();
	$view->place = new Place($_GET['place_id']);
	$view->addBlock("places/placeInfo.inc");
	$view->addBlock("places/addresses.inc");
	$view->addBlock("places/buildings.inc");
	$view->addBlock("places/units.inc");
	$view->addBlock("places/history.inc");
	$view->render();
?>