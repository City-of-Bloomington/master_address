<?php
/*
	$_GET variables:	name
*/
	$template = new Template();
	$response = new URL("viewName.php");

	$template->blocks[] = new Block("names/findNameForm.inc");
	if (isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$nameList = new NameList();
			$nameList->search($search);
			$template->blocks[] = new Block("names/findNameResults.inc",array("nameList"=>$nameList,"response"=>$response));
		}
	}

	# If they're logged in, they can add a new name
	if (userHasRole("Administrator"))
	{
		$template->blocks[] = new Block("names/addNameForm.inc");
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

				$response->parameters['name_id'] = $name->getId();
				Header("Location: {$response->getURL()}");
				exit();
			}
			catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
		}
	}

	$template->render();
?>