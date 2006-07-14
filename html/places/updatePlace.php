<?php
/*
	$_POST variables:	id
						place [	jurisdiction_id			mailable		censusBlockFIPSCode
								status_id				livable			statePlaneX
								name					section			statePlaneY
								township_id				quarterSection	latitude
								trashPickupDay_id		class			longitude
								recyclingPickupWeek_id	placeType_id
								startDate
								endDate
							]
*/
	verifyUser("Administrator");

	# Make sure we're editing an existing place
	if (!is_numeric($_POST['id']))
	{
		$_SESSION['errorMessages'][] = new Exception("missingRequiredFields");
		Header("Location: home.php");
	}

	$place = new Place($_POST['id']);
	foreach($_POST['place'] as $field=>$value)
	{
		$set = "set".ucfirst($field);
		$place->$set($value);
	}
	try
	{
		$place->save();
		Header("Location: viewPlace.php?id={$place->getId()}");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updatePlaceForm.php?id={$place->getId()}");
	}
?>