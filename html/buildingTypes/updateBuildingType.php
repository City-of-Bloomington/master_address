<?php
/*
	$_POST variables:	id
						description
*/
	verifyUser("Administrator");

	$buildingType = new BuildingType($_POST['id']);
	$buildingType->setDescription($_POST['description']);

	try
	{
		$buildingType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateBuildingTypeForm.php?id=$_POST[id]");
	}
?>