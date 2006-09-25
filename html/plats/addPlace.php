<?php
/*
	$_GET variables:	plat_id
*/
	verifyUser("Administrator");
	if (isset($_GET['plat_id'])) { $_SESSION['plat_id'] = $_GET['plat_id']; }

	$view = new View("popup");

	$view->blocks[] = new Block("places/findPlaceForm.inc");
	if (isset($_GET['place']))
	{
		$search = array();
		foreach($_GET['place'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$view->blocks[] = new Block("plats/choosePlacesForm.inc",array("placeList"=>new PlaceList($search)));
		}
	}

	# Handle any Places that were chosen to be added to this plat
	if (isset($_POST['places']))
	{
		foreach($_POST['places'] as $place_id=>$value)
		{
			if ($value=="on")
			{
				$place = new Place($place_id);
				$place->setPlat_id($_SESSION['plat_id']);
				try { $place->save(); }
				catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
			}
		}
	}

	$view->render();
?>