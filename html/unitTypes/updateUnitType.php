<?php
/*
	$_POST variables:	unitType
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/UnitType.inc");
	$unitType = new UnitType($_POST['unitType']);
	$unitType->setDescription($_POST['description']);

	try
	{
		$unitType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateUnitTypeForm.php?unitType=$_POST[unitType]");
	}
?>