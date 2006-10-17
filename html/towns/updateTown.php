<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						town [ name ]
*/
	verifyUser("Administrator");
	$template = new Template();
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

	$template->blocks[] = $form;
	$template->render();
?>