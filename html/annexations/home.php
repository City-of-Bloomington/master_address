<?php
	$template = new Template();

	$annexationList = new AnnexationList();
	$annexationList->find();
	$template->blocks[] = new Block("annexations/annexationList.inc",array("annexationList"=>$annexationList));

	$template->render();
?>