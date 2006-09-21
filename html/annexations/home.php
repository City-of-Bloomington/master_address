<?php
	$view = new View();

	$annexationList = new AnnexationList();
	$annexationList->find();
	$view->blocks[] = new Block("annexations/annexationList.inc",array("annexationList"=>$annexationList));

	$view->render();
?>