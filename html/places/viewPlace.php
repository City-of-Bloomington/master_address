<?php
/*
	$_GET variables:	place_id
*/
	$view = new View();
	$place = new Place($_GET['place_id']);

	$view->blocks[] = new Block("places/placeInfo.inc",array('place'=>$place));
	$view->blocks[] = new Block("places/addresses.inc",array('place'=>$place));
	$view->blocks[] = new Block("places/buildings.inc",array('place'=>$place));
	$view->blocks[] = new Block("places/units.inc",array('place'=>$place));
	$view->blocks[] = new Block("places/history.inc",array('place'=>$place));
	$view->render();
?>