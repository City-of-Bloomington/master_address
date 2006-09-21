<?php
	$view = new View();

	$suffixList = new SuffixList();
	$suffixList->find();
	$view->blocks[] = new Block("suffixes/suffixList.inc",array("suffixList"=>$suffixList));

	$view->render();
?>