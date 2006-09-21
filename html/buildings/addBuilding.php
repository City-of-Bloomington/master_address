<?php
/*
	$_GET variables:	place_id
*/
	verifyUser("Administrator");

	if (isset($_GET['place_id'])) { $_SESSION['place'] = new Place($_GET['place_id']); }

	$view = new View("popup");

	# Show the find form
	$view->blocks[] = new Block("buildings/findBuildingForm.inc");
	if (isset($_GET['building']))
	{
		$search = array();
		foreach($_GET['building'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$view->blocks[] = new Block("buildings/findBuildingResults.inc",
										array("response"=>new URL("addBuilding.php?place_id={$_SESSION['place']->getId()}"),
										"buildingList"=>new BuildingList($search)));
		}
	}

	# Handle any buildings they choose from the find results
	if (isset($_GET['building_id']))
	{
		$building = new Building($_GET['building_id']);
		$_SESSION['place']->addBuilding($building);
		$_SESSION['place']->save();
	}

	# Show the Add form
	$view->blocks[] = new Block("buildings/addBuildingForm.inc");
	if (isset($_POST['building']))
	{
		$building = new Building();
		foreach($_POST['building'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$building->$set($value);
		}

		try
		{
			$building->save();
			$_SESSION['place']->addBuilding($building);
			$_SESSION['place']->save();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>