<?php
/*
	$_GET variables:	place_id
*/
	verifyUser("Administrator");

	if (isset($_GET['place_id'])) { $_SESSION['place'] = new Place($_GET['place_id']); }

	$view = new View();
	$view->addBlock("buildings/addBuildingForm.inc");
	if (isset($_POST['building']))
	{
		$building = new Building();
		foreach($_POST['building'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$building->$set($value);
		}
		try
		{
			$building->setPlace($_SESSION['place']);
			$building->save();

		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e->getMessage(); }
	}
?>
