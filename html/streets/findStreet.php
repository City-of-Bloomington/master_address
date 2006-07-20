<?php
	$view = new View();
	$view->addBlock("streets/findStreetForm.inc");

	# IF they've submitted the form, show any results
	if (isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->response = new URL("viewStreet.php");
			$view->streetList = new StreetList($search);
			$view->addBlock("streets/findStreetResults.inc");
		}
	}

	$view->render();
?>