<?php
/*
	$_GET variables:	id
	-----------------------------------
	$_POST variables:	id
						unitType [ type
									name
								]
*/
	verifyUser("Administrator");
	if (isset($_POST['unitTyope']))
	{
		$unitType = new UnitType($_POST['id']);
		foreach($_POST['unitType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$unitType->$set($value);
		}

		try
		{
			$unitType->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->unitType = $unitType;
			$view->addBlock("unitTypes/updateUnitTypeForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->unitType = new UnitType($_GET['id']);
		$view->addBlock("unitTypes/updateUnitTypeForm.inc");
		$view->render();
	}
?>