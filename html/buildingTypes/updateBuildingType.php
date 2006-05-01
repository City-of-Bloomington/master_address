<?php
/*
	$_POST variables:	typeID
						description
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/BuildingType.inc");
	$buildingType = new BuildingType($_POST['typeID']);
	$buildingType->setDescription($_POST['description']);

	try
	{
		$buildingType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateBuildingTypeForm.php?typeID=$_POST[typeID]");
	}
?>