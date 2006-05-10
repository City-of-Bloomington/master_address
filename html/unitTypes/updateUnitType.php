<?php
/*
	$_POST variables:	id
						type
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/UnitType.inc");
	$unitType = new UnitType($_POST['id']);
	$unitType->setType($_POST['type']);
	$unitType->setDescription($_POST['description']);

	try
	{
		$unitType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateUnitTypeForm.php?id=$_POST[id]");
	}
?>