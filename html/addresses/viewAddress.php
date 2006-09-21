<?php
/*
	$_GET variables;	address_id
*/
	$view = new View();

	$address = new Address($_GET['address_id']);
	$view->blocks[] = new Block("addresses/addressInfo.inc",array("address"=>$address));


	$view->blocks[] = new Block("places/placeInfo.inc",array("place"=>$address->getPlace()));
	$view->blocks[] = new Block("places/buildings.inc",array("place"=>$address->getPlace()));
	$view->blocks[] = new Block("places/units.inc",array("place"=>$address->getPlace()));

	$view->render();
?>