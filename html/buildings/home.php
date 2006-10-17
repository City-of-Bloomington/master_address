<?php
	$template = new Template();
	$template->blocks[] = new Block("buildings/findBuildingForm.inc");
	if (isset($_GET['building']))
	{
		$search = array();
		foreach($_GET['building'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$template->blocks[] = new Block("buildings/findBuildingResults.inc",
										array("response"=>new URL("viewBuilding.php"),
												"buildingList"=>new BuildingList($search)));
		}
	}
	$template->render();
?>