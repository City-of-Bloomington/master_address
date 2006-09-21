<?php
	$view = new View();

	$unitTypeList = new UnitTypeList();
	$unitTypeList->find();
	$view->blocks[] = new Block("unitTypes/unitTypeList.inc",array("unitTypeList"=>$unitTypeList));

	$view->render();
?>