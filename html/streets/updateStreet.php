<?php
/*
	$_GET variables:	street_id
						return_url
*/
	verifyUser("Administrator");

	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['return_url'])) { $_SESSION['response'] = new URL($_GET['return_url']); }

	$view = new View();
	$view->street = $_SESSION['street'];
	$view->response = $_SESSION['response'];

	$view->addBlock("streets/updateStreetForm.inc");
	if (isset($_POST['street']))
	{
		$street = new Street($_POST['street_id']);
		foreach($_POST['street'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$street->$set($value);
		}

		try
		{
			$street->save();

			# Clean out the session junk
			unset($_SESSION['street']);
			unset($_SESSION['response']);

			Header("Location: {$view->response->getURL()}");
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>