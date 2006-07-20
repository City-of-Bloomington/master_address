<?php
/*
	$_GET variables:	id
	---------------------------------------------------------
	$_POST variables:	id
						name [ town_id
								name
								direction_id
								suffix_id
								postDirection_id
								startDate
								endDate
								notes
							]
*/
	verifyUser("Administrator");

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
			Header("Location: viewName.php?id=$_POST[id]");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->name = $name;
			$view->addBlock("names/updateNameForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->name = new Name($_GET['id']);
		$view->addBlock("names/updateNameForm.inc");
		$view->render();
	}
?>