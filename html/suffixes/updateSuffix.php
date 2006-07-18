<?php
/*
	$_GET variables:	id
	-------------------------------------
	$_POST variables:	id
						suffix
						description
*/
	verifyUser("Administrator");

	if (isset($_POST['suffix']))
	{
		$suffix = new Suffix($_POST['id']);
		foreach($_POST['suffix'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$suffix->$set($value);
		}

		try
		{
			$suffix->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->suffix = $suffix;
			$view->addBlock("suffixes/updateSuffix.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->suffix = new Suffix($_GET['suffix']);
		$view->addBlock("suffixes/updateSuffix.inc");
		$view->render();
	}
?>