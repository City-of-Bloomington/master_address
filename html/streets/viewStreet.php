<?php
/*
	$_GET variables:	street_id
*/
	$view = new View();
	$street = new Street($_GET['street_id']);
	$response = new URL($_SERVER['REQUEST_URI']);

	$view->blocks[] = new Block("streets/streetInfo.inc",array("street"=>$street,"response"=>$response));
	$view->blocks[] = new Block("streets/streetNames.inc",array("street"=>$street,"response"=>$response));
	$view->blocks[] = new Block("streets/segments.inc",array("street"=>$street));

	$view->render();
?>