<?php
	$view = new View();

	$districtList = new DistrictList();
	$districtList->find();
	$view->blocks[] = new Block("districts/districtList.inc",array("districtList"=>$districtList));

	$view->render();
?>