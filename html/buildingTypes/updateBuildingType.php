<?php
/*
	$_POST variables:	id
						buildingType[ description ]
*/
	verifyUser("Administrator");

	if (isset($_POST['buildingType']))
	{
		$buildingType = new BuildingType($_POST['id']);
		foreach($_POST['buildingType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$buildingType->$set($value);
		}

		try
		{
			$buildingType->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$view = new View();
			$view->buildingType = $buildingType;
			$view->addBlock("buildingTypes/updateBuildingTypeForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->buildingType = new BuildingType($_GET['id']);
		$view->addBlock("buildingTypes/updateBuildingTypeForm.inc");
		$view->render();
	}
?>