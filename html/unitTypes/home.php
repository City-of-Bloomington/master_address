<?php
	$template = new Template();

	$unitTypeList = new UnitTypeList();
	$unitTypeList->find();
	$template->blocks[] = new Block("unitTypes/unitTypeList.inc",array("unitTypeList"=>$unitTypeList));

	$template->render();
?>