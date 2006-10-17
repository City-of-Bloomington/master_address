<?php
/*
	$_GET variables:	id
	------------------------------------------------
	$_POST variables:	id
						buildingType[ description ]
*/
	verifyUser("Administrator");
	$template = new Template();

	$form = new Block("buildingTypes/updateBuildingTypeForm.inc");
	if (isset($_GET['id'])) { $form->buildingType = new BuildingType($_GET['id']); }

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
			$form->buildingType = $buildingType;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>