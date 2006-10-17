<?php
/*
	$_GET variables;	address_id
*/
	$template = new Template();

	$address = new Address($_GET['address_id']);
	$template->blocks[] = new Block("addresses/addressInfo.inc",array("address"=>$address));


	$template->blocks[] = new Block("places/placeInfo.inc",array("place"=>$address->getPlace()));
	$template->blocks[] = new Block("places/buildings.inc",array("place"=>$address->getPlace()));
	$template->blocks[] = new Block("places/units.inc",array("place"=>$address->getPlace()));

	$template->render();
?>