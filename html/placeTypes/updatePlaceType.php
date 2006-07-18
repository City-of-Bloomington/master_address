<?php
/*
	$_GET variables:	id
	--------------------------------------------
	$_POST variables:	id
						placeType [ type
									description
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['placeType']))
	{
		$placeType = new PlaceType($_POST['id']);
		foreach($_POST['placeType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$placeType->$set($value);
		}

		try
		{
			$placeType->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->placeType = $placeType;
			$view->addBlock("placeTypes/updatePlaceTypeForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->placeType = new PlaceType($_GET['id']);
		$view->addBlock("placeTypes/updatePlaceTypeForm.inc");
		$view->render();
	}
?>