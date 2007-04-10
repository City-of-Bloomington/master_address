<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						town [ name ]
*/
	verifyUser("Administrator");
	if (isset($_GET['id'])) { $town = new Town($_GET['id']); }

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
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template = new Template();
	$template->blocks[] = new Block("towns/updateTownForm.inc",array('town'=>$town));
	$template->render();
?>