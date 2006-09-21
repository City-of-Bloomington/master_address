<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						town [ name ]
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block("towns/updateTownForm.inc");
	if (isset($_GET['id'])) { $form->town = new Town($_GET['id']); }

	if (isset($_POST['town']))
	{
		$town = new Town($_POST['id']);
		$town->setName($_POST['town']['name']);

		try
		{
			$town->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->town = $town;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>