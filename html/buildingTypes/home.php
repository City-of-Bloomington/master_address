<?php
	$template = new Template();

	$buildingTypeList = new BuildingTypeList();
	$buildingTypeList->find();
	$template->blocks[] = new Block("buildingTypes/buildingTypeList.inc",array("buildingTypeList"=>$buildingTypeList));

	$template->render();
?>