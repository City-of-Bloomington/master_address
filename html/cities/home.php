<?php
	$view = new View();

	$cityList = new CityList();
	$cityList->find();
	$view->blocks[] = new Block("cities/cityList.inc",array('cityList'=>$cityList));

	$view->render();
?>