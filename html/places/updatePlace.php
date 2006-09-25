<?php
/*
	$_GET variables:	place_id
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block('places/updatePlaceForm.inc');
	if (isset($_GET['place_id'])) { $form->place = new Place($_GET['place_id']); }

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
			$form->place = $place;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>