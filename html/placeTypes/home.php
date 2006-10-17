<?php
	$template = new Template();

	$placeTypeList = new PlaceTypeList();
	$placeTypeList->find();
	$template->blocks[] = new Block("placeTypes/placeTypeList.inc",array('placeTypeList'=>$placeTypeList));

	$template->render();
?>