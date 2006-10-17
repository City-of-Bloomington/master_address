<?php
	$template = new Template();

	$jurisdictionList = new JurisdictionList();
	$jurisdictionList->find();
	$template->blocks[] = new Block("jurisdictions/jurisdictionList.inc",array("jurisdictionList"=>$jurisdictionList));

	$template->render();
?>