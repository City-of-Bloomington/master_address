<?php
	$template = new Template();

	$districtList = new DistrictList();
	$districtList->find();
	$template->blocks[] = new Block("districts/districtList.inc",array("districtList"=>$districtList));

	$template->render();
?>