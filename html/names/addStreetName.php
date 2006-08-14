<?php
/*
	$_SESSION variables:	name
							street
	----------------------------------
	$_GET variables:	name_id
						street_id	- From findStreetResults

						name	- From findStreetForm
	----------------------------------
	$_POST variables:	street	- From addStreetForm
						streetName	- From addStreetNameForm
*/
	verifyUser("Administrator");
	if (isset($_GET['name_id'])) { $_SESSION['name'] = new Name($_GET['name_id']); }
	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }


	$view = new View();
	$view->name = $_SESSION['name'];
	$view->addBlock("names/nameInfo.inc");

	# Handle any new street that they're posting
	if (isset($_POST['street']))
	{
		$street = new Street();
		foreach($_POST['street'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$street->$set($value);
		}

		try
		{
			$street->save();
			$_SESSION['street'] = $street;
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}


	# Show the street stuff if we still don't have a street
	if (!isset($_SESSION['street']))
	{
		$view->addBlock("streets/findStreetForm.inc");
		# If they've submitted the Find Street Form, show any results
		if (isset($_GET['street']['id']) && isset($_GET['name']))
		{
			$search = array();
			if ($_GET['street']['id']) { $search['id'] = $_GET['street']['id']; }
			foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
			if (count($search))
			{
				$view->response = new URL("addStreetName.php");
				$view->streetList = new StreetList($search);
				$view->addBlock("streets/findStreetResults.inc");
			}
		}

		$view->addBlock("streets/addStreetForm.inc");
	}
	else
	{
		# We've got a street
		$view->street = $_SESSION['street'];
		$view->response = new URL(BASE_URL."/names/addStreetName.php");
		$view->addBlock("streets/streetInfo.inc");

		# Handle any new streetName that they've posted
		$view->addBlock("streetNames/addStreetNameForm.inc");
		if (isset($_POST['streetName']))
		{
			$streetName = new StreetName();
			foreach($_POST['streetName'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$streetName->$set($value);
			}

			try
			{
				$streetName->save();

				# Clear out the session stuff, just to be nice
				$name_id = $_SESSION['name']->getId();
				unset($_SESSION['name']);
				unset($_SESSION['street']);

				Header("Location: viewName.php?name_id=$name_id");
				exit();
			}
			catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
		}
	}

	$view->render();
?>