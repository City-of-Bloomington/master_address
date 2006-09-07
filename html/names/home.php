<?php
/*
	$_GET variables:	name
*/
	$view = new View();
	$view->response = new URL("viewName.php");
	$view->addBlock("names/findNameForm.inc");
	if (isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->nameList = new NameList();
			$view->nameList->search($search);
			$view->addBlock("names/findNameResults.inc");
		}
	}

	# If they're logged in, they can add a new name
	if (userHasRole("Administrator"))
	{
		$view->addBlock("names/addNameForm.inc");
		if (isset($_POST['name']))
		{
			$name = new Name();
			foreach($_POST['name'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$name->$set($value);
			}

			try
			{
				$name->save();

				$view->response->parameters['name_id'] = $name->getId();
				Header("Location: {$view->response->getURL()}");
				exit();
			}
			catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
		}
	}

	$view->render();
?>