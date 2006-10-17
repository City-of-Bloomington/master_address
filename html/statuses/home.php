<?php
	$template = new Template();
	$template->addressStatusList = new StatusList("address");
	$template->addressStatusList->find();
	$template->addBlock("statuses/addressStatusList.inc");

	$template->buildingStatusList = new StatusList("building");
	$template->buildingStatusList->find();
	$template->addBlock("statuses/buildingStatusList.inc");

	$template->constructionStatusList = new StatusList("construction");
	$template->constructionStatusList->find();
	$template->addBlock("statuses/constructionStatusList.inc");

	$template->intersectionStatusList = new StatusList("intersection");
	$template->intersectionStatusList->find();
	$template->addBlock("statuses/intersectionStatusList.inc");

	$template->inventoryStatusList = new StatusList("inventory");
	$template->inventoryStatusList->find();
	$template->addBlock("statuses/inventoryStatusList.inc");

	$template->mapStatusList = new StatusList("map");
	$template->mapStatusList->find();
	$template->addBlock("statuses/mapStatusList.inc");

	$template->placeStatusList = new StatusList("place");
	$template->placeStatusList->find();
	$template->addBlock("statuses/placeStatusList.inc");

	$template->segmentStatusList = new StatusList("segment");
	$template->segmentStatusList->find();
	$template->addBlock("statuses/segmentStatusList.inc");

	$template->sidewalkStatusList = new StatusList("sidewalk");
	$template->sidewalkStatusList->find();
	$template->addBlock("statuses/sidewalkStatusList.inc");

	$template->streetStatusList = new StatusList("street");
	$template->streetStatusList->find();
	$template->addBlock("statuses/streetStatusList.inc");

	$template->unitStatusList = new StatusList("unit");
	$template->unitStatusList->find();
	$template->addBlock("statuses/unitStatusList.inc");

	$template->render();
?>