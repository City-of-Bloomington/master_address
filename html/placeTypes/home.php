<?php
	$view = new View();

	$placeTypeList = new PlaceTypeList();
	$placeTypeList->find();
	$view->blocks[] = new Block("placeTypes/placeTypeList.inc",array('placeTypeList'=>$placeTypeList));

	$view->render();
?>