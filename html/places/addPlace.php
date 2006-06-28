<?php
/*
	$_POST variables:							mailable
						name					livable
						township_id				section
						jurisdiction_id			quarterSection
						trashPickupDay_id		placeType_id
						largeItemPickupDay_id
						recyclingPickupWeek_id
*/
	#verifyUser("Administrator");

	$place = new Place($PDO);
	$place->setName($_POST['name']);
	$place->setTownship_id($_POST['township_id']);
	$place->setJurisdiction_id($_POST['jurisdiction_id']);
	$place->setTrashPickupDay_id($_POST['trashPickupDay_id']);
	$place->setRecyclingPickupWeek_id($_POST['recyclingPickupWeek_id']);
	$place->setMailable($_POST['mailable']);
	$place->setLivable($_POST['livable']);
	$place->setSection($_POST['section']);
	$place->setQuarterSection($_POST['quarterSection']);
	$place->setPlaceType_id($_POST['placeType_id']);

	try
	{
		$place->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: addPlaceForm.php");
	}
?>