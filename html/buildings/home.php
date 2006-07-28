<?php
	$view = new View();
	$view->addBlock("buildings/findBuildingForm.inc");
	if (isset($_GET['building']))
	{
		$search = array();
		foreach($_GET['building'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$view->response = new URL("viewBuilding.php");
			$view->buildingList = new BuildingList($search);
			$view->addBlock("buildings/findBuildingResults.inc");
		}
	}
	$view->render();
?>