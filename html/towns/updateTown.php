<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						town [ name ]
*/
	verifyUser("Administrator");

	$view = new View();
	if (isset($_GET['id'])) { $view->town = new Town($_GET['id']); }

	if (isset($_POST['town']))
	{
		$town = new Town($_POST['id']);
		$town->setName($_POST['town']['name']);

		try
		{
			$town->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->town = $town;
			$view->addBlock("towns/updateTownForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->town = new Town($_GET['id']);
		$view->addBlock("towns/updateTownForm.inc");
		$view->render();
	}
?>