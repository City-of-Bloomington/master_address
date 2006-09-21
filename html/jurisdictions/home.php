<?php
	$view = new View();

	$jurisdictionList = new JurisdictionList();
	$jurisdictionList->find();
	$view->blocks[] = new Block("jurisdictions/jurisdictionList.inc",array("jurisdictionList"=>$jurisdictionList));

	$view->render();
?>