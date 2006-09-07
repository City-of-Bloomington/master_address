<?php
/*
	$_GET variables;	building_id
*/
	$view = new View();
	$view->building = new Building($_GET['building_id']);
	$view->addBlock("buildings/buildingInfo.inc");
	$view->addBlock("buildings/units.inc");

	$view->placeList = $view->building->getPlaceList();
	$view->addBlock("places/placeList.inc");

	$view->render();
?>