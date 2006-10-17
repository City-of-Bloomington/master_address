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
	$response = new URL(BASE_URL."/names/addStreetName.php");


	$template = new Template();
	$template->blocks[] = new Block("names/nameInfo.inc",array("name"=>$_SESSION['name']));

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
		$template->blocks[] = new Block("streets/findStreetForm.inc");

		# If they've submitted the Find Street Form, show any results
		if (isset($_GET['street']['id']) && isset($_GET['name']))
		{
			$search = array();
			if ($_GET['street']['id']) { $search['id'] = $_GET['street']['id']; }
			foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
			if (count($search))
			{
				$streetList = new StreetList($search);
				$template->blocks[] = new Block("streets/findStreetResults.inc",array("response"=>$response,"streetList"=>$streetList));
			}
		}
		$template->blocks[] = new Block("streets/addStreetForm.inc",array("return_url"=>$response));
	}
	else
	{
		# We've got a street
		$template->blocks[] = new Block("streets/streetInfo.inc",array("street"=>$_SESSION['street'],"response"=>$response));

		# Handle any new streetName that they've posted
		$template->blocks[] = new Block("streetNames/addStreetNameForm.inc",array("name"=>$_SESSION['name'],"street"=>$_SESSION['street']));
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

	$template->render();
?>