<?php
/*
	$_POST variables:	placeTypeID
						type
						description
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/PlaceType.inc");
	$placeType = new PlaceType($_POST['placeTypeID']);
	$placeType->setType($_POST['type']);
	$placeType->setDescription($_POST['description']);

	try
	{
		$placeType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updatePlaceTypeForm.php?placeTypeID=$_POST[placeTypeID]");
	}
?>