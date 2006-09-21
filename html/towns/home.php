<?php
	$view = new View();

	$townList = new TownList();
	$townList->find();
	$view->blocks[] = new Block("towns/townList.inc",array("townList"=>$townList));

	$view->render();
?>