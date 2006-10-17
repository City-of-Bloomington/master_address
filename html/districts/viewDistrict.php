<?php
/*
	$_GET variables:	id
*/
	$template = new Template();
	$template->blocks[] = new Block("districts/districtInfo.inc",array("district"=>new District($_GET['id'])));
	$template->render();
?>