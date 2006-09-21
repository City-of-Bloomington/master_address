<?php
	$view = new View();

	$directionList = new DirectionList();
	$directionList->find();
	$view->blocks[] = new Block("directions/directionList.inc",array("directionList"=>$directionList));

	$view->render();
?>