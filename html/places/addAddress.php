<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$_GET variables:	place_id
*/
	verifyUser('Administrator');
	if (isset($_GET['place_id']))
	{
		# Passing in place_id is the start of this process.
		# Clear out any lingering SESSION variables that will cause problems
		$_SESSION['place'] = new Place($_GET['place_id']);
		unset($_SESSION['street']);
		unset($_SESSION['segment']);
	}
	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['segment_id'])) { $_SESSION['segment'] = new Segment($_GET['segment_id']); }


	$view = new View();
	$response = new URL('addAddress.php');


	$view->blocks[] = new Block('places/placeInfo.inc',array('place'=>$_SESSION['place']));


	if (!isset($_SESSION['street']))
	{
		$view->blocks[] = new Block("streets/findStreetForm.inc");

		# If they've submitted the Find Street Form, show any results
		if (isset($_GET['street']['id']) && isset($_GET['name']))
		{
			$search = array();
			if ($_GET['street']['id']) { $search['id'] = $_GET['street']['id']; }
			foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
			if (count($search))
			{
				$streetList = new StreetList($search);
				$view->blocks[] = new Block("streets/findStreetResults.inc",array("response"=>$response,"streetList"=>$streetList));
			}
		}
	}
	else
	{
		$view->blocks[] = new Block('streets/streetInfo.inc',array('street'=>$_SESSION['street'],'response'=>$response));

		if (!isset($_SESSION['segment']))
		{
			$view->blocks[] = new Block('segments/chooseSegment.inc',array('response'=>$response,'segmentList'=>$_SESSION['street']->getSegments()));
		}
		else
		{
			# We've now got a Place, Street, and Segment
			# Show the add address form
			$view->blocks[] = new Block('places/addAddressForm.inc');
			if (isset($_POST['address']))
			{
				$address = new Address();
				foreach($_POST['address'] as $field=>$value)
				{
					$set = "set".ucfirst($field);
					$address->$set($value);
				}

				$address->setPlace($_SESSION['place']);
				$address->setStreet($_SESSION['street']);
				$address->setSegment($_SESSION['segment']);

				try
				{
					$address->save();
					$place_id = $_SESSION['place']->getId();

					unset($_SESSION['place']);
					unset($_SESSION['street']);
					unset($_SESSION['segment']);

					Header("Location: viewPlace.php?place_id=$place_id");
					exit();
				}
				catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
			}

		}
	}

	$view->render();
?>