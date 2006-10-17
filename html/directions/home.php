<?php
	$template = new Template();

	$directionList = new DirectionList();
	$directionList->find();
	$template->blocks[] = new Block("directions/directionList.inc",array("directionList"=>$directionList));

	$template->render();
?>