<?php
/*
	$_GET variables:	place_id
						building_id

	This form runs as a popup and should refresh the parent window
	after each unit is added.
*/
	verifyUser("Administrator");
	$template = new Template("popup");

	if (isset($_GET['place_id'])) { $_SESSION['place'] = new Place($_GET['place_id']); }
	if (isset($_GET['building_id'])) { $_SESSION['building'] = new Building($_GET['building_id']); }

	$template->blocks[] = new Block("units/addUnitForm.inc");
	if (isset($_POST['unit']))
	{
		$unit = new Unit();
		foreach($_POST['unit'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$unit->$set($value);
		}
		$unit->setPlace($_SESSION['place']);
		$unit->setBuilding($_SESSION['building']);

		try { $unit->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template->render();
?>