<?php
/*
	$_GET variables:	building_id
*/
	verifyUser("Administrator");
	$view = new View();
	$updateBuildingForm = new Block("buildings/updateBuildingForm.inc");
	if (isset($_GET['building_id'])) { $updateBuildingForm->building = new Building($_GET['building_id']); }

	if (isset($_POST['building']))
	{
		$building = new Building($_POST['building_id']);
		foreach($_POST['building'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$building->$set($value);
		}

		try
		{
			$building->save();
			Header("Location: viewBuilding.php?building_id={$building->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$updateBuildingForm->building = $building;
		}
	}

	$view->blocks[] = $updateBuildingForm;
	$view->render();
?>