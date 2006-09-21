<?php
/*
	$_GET variables:	unit_id
*/
	$view = new View();
	$unit = new Unit($_GET['unit_id']);

	$view->blocks[] = new Block("units/unitInfo.inc",array("unit"=>$unit));
	$view->blocks[] = new Block("buildings/buildingInfo.inc",array("building"=>$unit->getBuilding()));
	$view->blocks[] = new Block("places/placeInfo.inc",array("place"=>$unit->getPlace()));
	$view->blocks[] = new Block("places/addresses.inc",array("place"=>$unit->getPlace()));

	$view->render();
?>