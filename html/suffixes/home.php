<?php
	$template = new Template();

	$suffixList = new SuffixList();
	$suffixList->find();
	$template->blocks[] = new Block("suffixes/suffixList.inc",array("suffixList"=>$suffixList));

	$template->render();
?>