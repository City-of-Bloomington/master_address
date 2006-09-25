<?php
	$view = new View();

	$platTypeList = new PlatTypeList();
	$platTypeList->find();
	$view->blocks[] = new Block("platTypes/platTypeList.inc",array('platTypeList'=>$platTypeList));

	$view->render();
?>