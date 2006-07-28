<?php
/*
	$_GET variables:	building_id
*/
	$view = new View();
	if (isset($_GET['building_id'])) { $view->building = new Building($_GET['building_id']); }

	$view->addBlock("buildings/updateBuildingForm.inc");
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
			$view->building = $building;
		}
	}

	$view->render();
?>