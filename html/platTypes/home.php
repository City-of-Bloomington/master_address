<?php
	$template = new Template();

	$platTypeList = new PlatTypeList();
	$platTypeList->find();
	$template->blocks[] = new Block("platTypes/platTypeList.inc",array('platTypeList'=>$platTypeList));

	$template->render();
?>