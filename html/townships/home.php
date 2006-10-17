<?php
	$template = new Template();

	$townshipList = new TownshipList();
	$townshipList->find();
	$template->blocks[]  = new Block("townships/townshipList.inc",array("townshipList"=>$townshipList));

	$template->render();
?>