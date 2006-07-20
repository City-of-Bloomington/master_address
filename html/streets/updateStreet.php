<?php
/*
	$_GET variables:	id
						return_url
	-----------------------------------------
	$_POST variables:	id
						street [ status_id
								notes
								]
						return_url
*/
	verifyUser("Administrator");

	if (isset($_POST['street']))
	{
		$street = new Street($_POST['id']);
		foreach($_POST['street'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$street->$set($value);
		}

		try
		{
			$street->save();
			Header("Location: $_POST[return_url]{$street->getId()}");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->street = $street;
			$view->return_url = $_POST['return_url'];
			$view->addBlock("streets/updateStreetForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->street = new Street($_GET['id']);
		$view->return_url = $_GET['return_url'];
		$view->addBlock("streets/updateStreetForm.inc");
		$view->render();
	}
?>