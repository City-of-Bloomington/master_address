<?php
/*
	$_GET variables:	id
	----------------------------------------------
	$_POST variables:	id
						direction [	code
									direction
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['direction']))
	{
		$direction = new Direction($_POST['id']);
		foreach($_POST['direction'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$direction->$set($value);
		}

		try
		{
			$direction->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->direction = $direction;
			$view->addBlock("directions/updateDirectionForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->direction = new Direction($_GET['id']);
		$view->addBlock("directions/updateDirectionForm.inc");
		$view->render();
	}
?>