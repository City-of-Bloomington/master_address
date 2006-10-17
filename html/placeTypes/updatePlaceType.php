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
	$template = new Template();
	$form = new Block('placeTypes/updatePlaceTypeForm.inc');
	if (isset($_GET['id'])) { $form->placeType = new PlaceType($_GET['id']); }

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
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->placeType = $placeType;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>