<?php
	$template = new Template();

	$cityList = new CityList();
	$cityList->find();
	$template->blocks[] = new Block("cities/cityList.inc",array('cityList'=>$cityList));

	$template->render();
?>