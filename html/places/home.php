<?php
	$view = new View();
	$view->addBlock("places/findPlaceForm.inc");
	if (isset($_GET['place']))
	{
		$search = array();
		foreach($_GET['place'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$view->placeList = new PlaceList($search);
			$view->response = new URL("viewPlace.php");
			$view->addBlock("places/findPlaceResults.inc");
		}
	}
	$view->render();
?>