<?php
/*
	$_GET variables:	id
	----------------------------------------
	$_POST variables:	id
						platType [ type
									description
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['platType']))
	{
		$platType = new PlatType($_POST['id']);
		foreach($_POST['platType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$platType->$set($value);
		}

		try
		{
			$platType->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->platType = $platType;
			$view->addBlock("platTypes/updatePlatTypeForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->platType = new PlatType($_GET['id']);
		$view->addBlock("platTypes/updatePlatTypeForm.inc");
		$view->render();
	}
?>