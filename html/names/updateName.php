<?php
/*
	$_GET variables:	name_id
	-------------------------------------------
	$_POST variables:	name
*/
	verifyUser("Administrator");
	if (isset($_GET['name_id'])) { $_SESSION['name'] = new Name($_GET['name_id']); }

	$view = new View();
	$view->name = $_SESSION['name'];
	$view->addBlock("names/updateNameForm.inc");
	if (isset($_POST['name']))
	{
		$name = new Name($_POST['id']);
		foreach($_POST['name'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$name->$set($value);
		}

		try
		{
			$name->save();

			unset($_SESSION['name']);
			Header("Location: viewName.php?name_id={$view->name->getId()}");
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>