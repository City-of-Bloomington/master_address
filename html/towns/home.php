<?php
	$template = new Template();

	$townList = new TownList();
	$townList->find();
	$template->blocks[] = new Block("towns/townList.inc",array("townList"=>$townList));

	$template->render();
?>