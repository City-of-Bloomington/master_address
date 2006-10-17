<?php
	$template = new Template();
	$template->blocks[] = new Block("places/findPlaceForm.inc");
	if (isset($_GET['place']))
	{
		$search = array();
		foreach($_GET['place'] as $field=>$value) { if ($value) $search[$field] = $value; }
		if (count($search))
		{
			$template->blocks[] = new Block("places/findPlaceResults.inc",
										array('placeList'=>new PlaceList($search),
												'response'=>new URL("viewPlace.php")));
		}
	}
	$template->render();
?>