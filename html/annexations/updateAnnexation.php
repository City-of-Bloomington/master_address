<?php
/*
	$_GET variables:	id
	--------------------------------------------
	$_POST variables:	id
						annexation [ ordinanceNumber
									name
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['annexation']))
	{
		$annexation = new Annexation($_POST['id']);
		foreach($_POST['annexation'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$annexation->$set($value);
		}
		try
		{
			$annexation->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->annexation = $annexation;
			$view->addBlock("annexations/updateAnnexationForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->annexation = new Annexation($_GET['id']);
		$view->addBlock("annexations/updateAnnexationForm.inc");
		$view->render();
	}
?>