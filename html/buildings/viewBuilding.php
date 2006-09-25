<?php
/*
	$_GET variables;	building_id
*/
	$view = new View();
	$building = new Building($_GET['building_id']);
	$view->blocks[] = new Block("buildings/buildingInfo.inc",array("building"=>$building));
	$view->blocks[] = new Block("buildings/units.inc",array("building"=>$building));

	$view->blocks[] = new Block("places/placeList.inc",
								array("placeList"=>$building->getPlaceList(),
										"addPlaceURL"=>new URL("addPlace.php?building_id={$building->getId()}"),
										"deletePlaceURL"=>new URL("deletePlace.php?building_id={$building->getId()}")));

	$view->render();
?>