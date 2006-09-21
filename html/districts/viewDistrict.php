<?php
/*
	$_GET variables:	id
*/
	$view = new View();
	$view->blocks[] = new Block("districts/districtInfo.inc",array("district"=>new District($_GET['id'])));
	$view->render();
?>