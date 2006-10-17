<?php
/*
	$_GET variables:	plat_id
*/
	$template = new Template();
	$plat = new Plat($_GET['plat_id']);
	$template->blocks[] = new Block("plats/platInfo.inc",array("plat"=>$plat));

	$addPlaceURL = new URL("addPlace.php?plat_id={$plat->getId()}");
	$deletePlaceURL = new URL("deletePlace.php?plat_id={$plat->getId()}");
	$template->blocks[] = new Block('places/placeList.inc',
								array(	'placeList'=>$plat->getPlaces(),
										'addPlaceURL'=>$addPlaceURL,
										'deletePlaceURL'=>$deletePlaceURL));
	$template->render();
?>