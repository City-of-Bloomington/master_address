<?php
	$template = new Template();

	$districtTypeList = new DistrictTypeList();
	$districtTypeList->find();
	$template->blocks[] = new Block("districtTypes/districtTypeList.inc",array("districtTypeList"=>$districtTypeList));

	$template->render();
?>