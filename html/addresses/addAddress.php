<?php
/*
	$_GET variables:	street_id
						segment_id
						place_id
*/
	verifyUser(array("Administrator","ADDRESS COORDINATOR"));

	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['segment_id'])) { $_SESSION['segment'] = new Segment($_GET['segment_id']); }
	if (isset($_GET['place_id'])) { $_SESSION['place'] = new Place($_GET['place_id']); }

	$view = new View("popup");

	$view->place = $_SESSION['place'];
	$view->addBlock("places/placeInfo.inc");


	$view->addBlock("addresses/addAddressForm.inc");
	if (isset($_POST['address']))
	{
		$address = new Address();
		foreach($_POST['address'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$address->$set($value);
		}
		$address->setStreet($_SESSION['street']);
		$address->setSegment($_SESSION['segment']);
		$address->setPlace($_SESSION['place']);

		try { $address->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}


	$view->render();
?>