<?php
/*
	$_GET variables:	unit_id
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block("units/updateUnitForm.inc");
	if (isset($_GET['unit_id'])) { $form->unit = new Unit($_GET['unit_id']); }

	if (isset($_POST['unit']))
	{
		$unit = new Unit($_POST['unit_id']);
		foreach($_POST['unit'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$unit->$set($value);
		}

		try
		{
			$unit->save();
			Header("Location: viewUnit.php?unit_id={$unit->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->unit = $unit;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>