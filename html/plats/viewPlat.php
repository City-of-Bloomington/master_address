<?php
/*
	$_GET variables:	plat_id
*/
	$view = new View();
	$view->blocks[] = new Block("plats/platInfo.inc",array("plat"=>new Plat($_GET['plat_id'])));
	$view->render();
?>