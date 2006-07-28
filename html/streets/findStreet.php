<?php
	$view = new View();
	$view->addBlock("streets/findStreetForm.inc");

	# IF they've submitted the form, show any results
	if (isset($_GET['street']['id'] && isset($_GET['name']))
	{
		$search = array();
		if ($_GET['street']['id']) { $search['id'] = $_GET['street']['id']; }
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