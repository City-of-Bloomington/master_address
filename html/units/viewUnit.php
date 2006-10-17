<?php
/*
	$_GET variables:	unit_id
*/
	$template = new Template();
	$unit = new Unit($_GET['unit_id']);

	$template->blocks[] = new Block("units/unitInfo.inc",array("unit"=>$unit));
	$template->blocks[] = new Block("buildings/buildingInfo.inc",array("building"=>$unit->getBuilding()));
	$template->blocks[] = new Block("places/placeInfo.inc",array("place"=>$unit->getPlace()));
	$template->blocks[] = new Block("places/addresses.inc",array("place"=>$unit->getPlace()));

	$template->render();
?>