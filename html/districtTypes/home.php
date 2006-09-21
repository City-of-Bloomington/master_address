<?php
	$view = new View();

	$districtTypeList = new DistrictTypeList();
	$districtTypeList->find();
	$view->blocks[] = new Block("districtTypes/districtTypeList.inc",array("districtTypeList"=>$districtTypeList));

	$view->render();
?>