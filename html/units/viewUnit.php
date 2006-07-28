<?php
/*
	$_GET variables:	unit_id
*/
	$view = new View();
	$view->unit = new Unit($_GET['unit_id']);
	$view->addBlock("units/unitInfo.inc");

	$view->building = $view->unit->getBuilding();
	$view->addBlock("buildings/buildingInfo.inc");

	$view->place = $view->unit->getPlace();
	$view->addBlock("places/placeInfo.inc");
	$view->addBlock("places/addresses.inc");

	$view->render();
?>