<?php
/*
	$_GET variables:	plat_id
*/
	$view = new View();
	$view->plat = new Plat($_GET['plat_id']);
	$view->addBlock("plats/platInfo.inc");
	$view->render();
?>