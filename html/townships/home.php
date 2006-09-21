<?php
	$view = new View();

	$townshipList = new TownshipList();
	$townshipList->find();
	$view->blocks[]  = new Block("townships/townshipList.inc",array("townshipList"=>$townshipList));

	$view->render();
?>