<?php
/*
	$_GET variables:	place_id
*/
	verifyUser("Administrator");
	$view = new View();

	if (isset($_GET['place_id'])) { $view->place = new Place($_GET['place_id']); }

	$view->addBlock("places/updatePlaceForm.inc");
	if (isset($_POST['place']))
	{
		$place = new Place($_POST['place_id']);
		foreach($_POST['place'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$place->$set($value);
		}

		try
		{
			$place->save();
			Header("Location: viewPlace.php?place_id={$place->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$view->place = $place;
		}
	}

	$view->render();
?>