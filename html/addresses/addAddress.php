<?php
/*
	$_POST variables:	number				addressType		startMonth
						suffix				city_id			startDay
											zip  zipplus4	startYear
						segment_id			active			endMonth
															endDay
											notes			endYear



						# Place information
												mailable
						name					livable
						township_id				section
						jurisdiction_id			quarterSection
						trashPickupDay_id		placeType_id
						largeItemPickupDay_id
						recyclingPickupWeek_id
*/
	verifyUser("Administrator","ADDRESS COORDINATOR");
	#--------------------------------------------------------------------------
	# Create the new address
	#--------------------------------------------------------------------------
	$address = new Address();
	$address->setNumber($_POST['number']);
	$address->setSuffix($_POST['suffix']);
	$address->setSegment_id($_POST['segment_id']);
	$address->setAddressType($_POST['addressType']);
	$address->setCity_id($_POST['city_id']);
	$address->setZip($_POST['zip']);
	$address->setZipplus4($_POST['zipplus4']);
	$address->setActive($_POST['active']);
	$address->setStartDate("$_POST[startYear]-$_POST[startMonth]-$_POST[startDay]");
	if ($_POST['endYear'] && $_POST['endMonth'] && $_POST['endDay']) { $address->setEndDate("$_POST[endYear]-$_POST[endMonth]-$_POST[endDay]"); }
	$address->setNotes($_POST['notes']);

	#--------------------------------------------------------------------------
	# Add the new place
	#--------------------------------------------------------------------------
	$place = new Place();
	$place->setName($_POST['name']);
	$place->setTownship_id($_POST['township_id']);
	$place->setJurisdiction_id($_POST['jurisdiction_id']);
	$place->setTrashPickupDay_id($_POST['trashPickupDay_id']);
	$place->setRecyclingPickupWeek_id($_POST['recyclingPickupWeek_id']);
	if (isset($_POST['mailable'])) { $place->setMailable($_POST['mailable']); }
	if (isset($_POST['livable'])) { $place->setLivable($_POST['livable']); }
	$place->setSection($_POST['section']);
	$place->setQuarterSection($_POST['quarterSection']);
	$place->setPlaceType_id($_POST['placeType_id']);
	print_r($place);

	#$place->save();
	#$address->setPlace_id($place->getId());


	print_r($address);
	#$address->save();
	#Header("Location: viewAddress.php?id={$address->getId()}");
?>