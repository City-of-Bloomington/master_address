<?php
/*
	$_GET variables:	street_id
						return_url
*/
	verifyUser("Administrator");


	$view = new View();
	$form = new Block("streets/updateStreetForm.inc");

	if (isset($_GET['street_id'])) { $form->street = new Street($_GET['street_id']); }
	if (isset($_GET['return_url'])) { $form->response = new URL($_GET['return_url']); }


	if (isset($_POST['street']))
	{
		$street = new Street($_POST['street_id']);
		$form->response = new URL($_POST['response']);
		foreach($_POST['street'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$street->$set($value);
		}

		try
		{
			$street->save();
			Header("Location: {$form->response->getURL()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->street = $street;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>