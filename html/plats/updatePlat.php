<?php
/*
	$_GET variables:	id
	-----------------------------------------
	$_POST variables:	id
						plat [ name
								township_id
								platType_id
								cabinet
								envelope
								notes
								]
*/
	verifyUser("Administrator");
	if (isset($_POST['plat']))
	{
		$plat = new Plat($_POST['id']);
		foreach($_POST['plat'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$plat->$set($value);
		}

		try
		{
			$plat->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->plat = $plat;
			$view->addBlock("plats/updatePlatForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->plat = new Plat($_GET['id']);
		$view->addBlock("plats/updatePlatForm.inc");
		$view->render();
	}
?>