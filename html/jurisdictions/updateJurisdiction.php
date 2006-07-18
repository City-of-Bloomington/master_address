<?php
/*
	$_GET variables:	id
	-----------------------------------------------
	$_POST variables:	id
						jurisdiction [ name ]
*/
	verifyUser("Administrator");

	if (isset($_POST['jurisdiction']))
	{
		$jurisdiction = new Jurisdiction($_POST['id']);
		foreach($_POST['jurisdiction'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$jurisdiction->$set($value);
		}

		try
		{
			$jurisdiction->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->jurisdiction = $jurisdiction;
			$view->addBlock("jurisdictions/updateJurisdictionForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->jurisdiction = new Jurisdiction($_GET['id']);
		$view->addBlock("jurisdictions/updateJurisdictionForm.inc");
		$view->render();
	}
?>