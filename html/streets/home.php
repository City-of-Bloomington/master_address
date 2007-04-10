<?php
	$template = new Template();
	$template->blocks[] = new Block("streets/findStreetForm.inc");

	# IF they've submitted the form, show any results
	if (isset($_GET['street']['id']) && isset($_GET['name']))
	{
		$search = array();
		if ($_GET['street']['id']) { $search['street_id'] = $_GET['street']['id']; }
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			# Searching for street name information is really searching through the streetNames
			$response = new URL("viewStreet.php");
			$streetNameList = new StreetNameList();
			$streetNameList->search($search);
			$template->blocks[] = new Block("streets/findStreetResults.inc",array("streetNameList"=>$streetNameList,"response"=>$response));
		}
	}

	$template->render();
?>