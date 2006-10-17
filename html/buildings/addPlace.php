<?php
/*
	$_GET variables:	building_id
*/
	verifyUser("Administrator");
	if (isset($_GET['building_id'])) { $_SESSION['building'] = new Building($_GET['building_id']); }
	if (isset($_GET['place_id']))
	{
		$place = new Place($_GET['place_id']);
		$place->addBuilding($_SESSION['building']);

		try { $place->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template = new Template("popup");
	$template->blocks[] = new Block("places/findPlaceForm.inc");
	if (isset($_GET['place']))
	{
		$search = array();
		foreach($_GET['place'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$template->blocks[] = new Block("places/findPlaceResults.inc",
										array('placeList'=>new PlaceList($search),
												'response'=>new URL("addPlace.php?building_id={$_SESSION['building']->getId()}")));
		}
	}

	$template->render();
?>