<?php
/*
	$_GET variables:	plat_id
*/
	$view = new View();
	$plat = new Plat($_GET['plat_id']);
	$view->blocks[] = new Block("plats/platInfo.inc",array("plat"=>$plat));

	$addPlaceURL = new URL("addPlace.php?plat_id={$plat->getId()}");
	$deletePlaceURL = new URL("deletePlace.php?plat_id={$plat->getId()}");
	$view->blocks[] = new Block('places/placeList.inc',
								array(	'placeList'=>$plat->getPlaces(),
										'addPlaceURL'=>$addPlaceURL,
										'deletePlaceURL'=>$deletePlaceURL));
	$view->render();
?>