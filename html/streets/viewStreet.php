<?php
/*
	$_GET variables:	street_id
*/
	$template = new Template();
	$street = new Street($_GET['street_id']);
	$response = new URL($_SERVER['REQUEST_URI']);

	$template->blocks[] = new Block("streets/streetInfo.inc",array("street"=>$street,"response"=>$response));
	$template->blocks[] = new Block("streets/streetNames.inc",array("street"=>$street,"response"=>$response));
	$template->blocks[] = new Block("streets/segments.inc",array("street"=>$street));

	$template->render();
?>