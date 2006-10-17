<?php
/*
	$_GET variables:	place_id
*/
	$template = new Template();
	$place = new Place($_GET['place_id']);

	$template->blocks[] = new Block("places/placeInfo.inc",array('place'=>$place));
	$template->blocks[] = new Block("places/addresses.inc",array('place'=>$place));
	$template->blocks[] = new Block("places/buildings.inc",array('place'=>$place));
	$template->blocks[] = new Block("places/units.inc",array('place'=>$place));
	$template->blocks[] = new Block("places/history.inc",array('place'=>$place));
	$template->render();
?>