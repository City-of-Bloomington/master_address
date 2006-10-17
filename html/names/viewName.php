<?php
/*
	$_GET variables:	name_id
*/
	$template = new Template();

	$name = new Name($_GET['name_id']);
	$template->blocks[] = new Block("names/nameInfo.inc",array("name"=>$name));
	$template->blocks[] = new Block("names/streets.inc",array("name"=>$name));
	$template->render();
?>