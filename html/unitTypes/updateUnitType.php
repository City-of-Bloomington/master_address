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
	$view = new View();
	$form = new Block("unitTypes/updateUnitTypeForm.inc");
	if (isset($_GET['id'])) { $form->unitType = new UnitType($_GET['id']); }

	if (isset($_POST['unitType']))
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
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->unitType = $unitType;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>