<?php
/*
	$_GET variables;	address_id
*/
	$view = new View();

	$view->address = new Address($_GET['address_id']);
	$view->addBlock("addresses/addressInfo.inc");

	$view->place = $view->address->getPlace();
	$view->addBlock("places/placeInfo.inc");
	$view->addBlock("places/buildings.inc");
	$view->addBlock("places/units.inc");

	$view->render();
?>