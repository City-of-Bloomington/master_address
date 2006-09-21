<?php
	$view = new View();

	$buildingTypeList = new BuildingTypeList();
	$buildingTypeList->find();
	$view->blocks[] = new Block("buildingTypes/buildingTypeList.inc",array("buildingTypeList"=>$buildingTypeList));

	$view->render();
?>