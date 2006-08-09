<?php
/*
	$_GET variables:	plat_id
	-----------------------------------------
	$_POST variables:	plat_id
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
			Header("Location: viewPlat.php?plat_id={$plat->getId()}");
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
		$view->plat = new Plat($_GET['plat_id']);
		$view->addBlock("plats/updatePlatForm.inc");
		$view->render();
	}
?>