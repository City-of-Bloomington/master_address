<?php
/*
	$_GET variables:	id
	----------------------------------
	$_POST variables:	id
						township [ name
									abbreviation
									quarterCode
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['township']))
	{
		$township = new Township($_POST['id']);
		foreach($_POST['township'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$township->$set($value);
		}

		try
		{
			$township->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->township = $township;
			$view->addBlock("townships/updateTownshipForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->township = new Township($_GET['id']);
		$view->addBlock("townships/updateTownshipForm.inc");
		$view->render();
	}
?>