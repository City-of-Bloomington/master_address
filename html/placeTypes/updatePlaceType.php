<?php
/*
	$_POST variables:	id
						type
						description
*/
	verifyUser("Administrator");

	$placeType = new PlaceType($_POST['id']);
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
		Header("Location: updatePlaceTypeForm.php?id=$_POST[id]");
	}
?>