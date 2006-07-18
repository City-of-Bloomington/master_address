<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						town [ name ]
*/
	verifyUser("Administrator");

	if (isset($_POST['town']))
	{
		$town = new Town($_POST['id']);
		$town->setName($_POST['name']);

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
			$view->addBlock("towns/udpateTownForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->town = new Town($_GET['id']);
		$view->addBlock("towns/udpateTownForm.inc");
		$view->render();
	}
?>