<?php
/*
	$_GET variables:	name_id
	-------------------------------------------
	$_POST variables:	name
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block("names/updateNameForm.inc");
	if (isset($_GET['name_id'])) { $form->name = new Name($_GET['name_id']); }


	if (isset($_POST['name']) && $_POST['id'])
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
			Header("Location: viewName.php?name_id={$name->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->name = $name;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>