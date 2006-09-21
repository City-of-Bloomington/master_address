<?php
	$view = new View();
	$view->addressStatusList = new StatusList("address");
	$view->addressStatusList->find();
	$view->addBlock("statuses/addressStatusList.inc");

	$view->buildingStatusList = new StatusList("building");
	$view->buildingStatusList->find();
	$view->addBlock("statuses/buildingStatusList.inc");

	$view->constructionStatusList = new StatusList("construction");
	$view->constructionStatusList->find();
	$view->addBlock("statuses/constructionStatusList.inc");

	$view->intersectionStatusList = new StatusList("intersection");
	$view->intersectionStatusList->find();
	$view->addBlock("statuses/intersectionStatusList.inc");

	$view->inventoryStatusList = new StatusList("inventory");
	$view->inventoryStatusList->find();
	$view->addBlock("statuses/inventoryStatusList.inc");

	$view->mapStatusList = new StatusList("map");
	$view->mapStatusList->find();
	$view->addBlock("statuses/mapStatusList.inc");

	$view->placeStatusList = new StatusList("place");
	$view->placeStatusList->find();
	$view->addBlock("statuses/placeStatusList.inc");

	$view->segmentStatusList = new StatusList("segment");
	$view->segmentStatusList->find();
	$view->addBlock("statuses/segmentStatusList.inc");

	$view->sidewalkStatusList = new StatusList("sidewalk");
	$view->sidewalkStatusList->find();
	$view->addBlock("statuses/sidewalkStatusList.inc");

	$view->streetStatusList = new StatusList("street");
	$view->streetStatusList->find();
	$view->addBlock("statuses/streetStatusList.inc");

	$view->unitStatusList = new StatusList("unit");
	$view->unitStatusList->find();
	$view->addBlock("statuses/unitStatusList.inc");

	$view->render();
?>