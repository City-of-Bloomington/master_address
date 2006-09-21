<?php
/*
	$_SESSION variables:	name
							street - Required
	----------------------------------
	$_GET variables:	name_id
						return_url - Required to create the response URL
						street_id - Required

						name - From findNameForm
	----------------------------------
	$_POST variables:	name - From addNameForm
						streetName	- From addStreetNameForm
*/
	verifyUser("Administrator");
	if (isset($_GET['name_id'])) { $_SESSION['name'] = new Name($_GET['name_id']); }
	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['return_url'])) { $_SESSION['response'] = new URL($_GET['return_url']); }


	$view = new View();
	$view->blocks[] = new Block("streets/streetInfo.inc",array("street"=>$_SESSION['street'],"response"=>$_SESSION['response']));

	# Handle any new Name they're posting
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
			$_SESSION['name'] = $name;
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	# Show the find and add name forms if we still don't have a name
	if (!isset($_SESSION['name']))
	{
		$view->blocks[] = new Block("names/findNameForm.inc");
		$view->blocks[] = new Block("names/addNameForm.inc");

		# If they've searched for a name, show the results
		if (isset($_GET['name']))
		{
			$search = array();
			foreach($_GET['name'] as $field=>$value) { if ($value) $search[$field] = $value; }
			if (count($search))
			{
				$response = new URL("addStreetName.php");
				$nameList = new NameList($search);
				$view->blocks[] = new Block("names/findNameResults.inc",array("nameList"=>$nameList,"response"=>$response));
			}
		}
	}
	else
	{
		# we've got a name
		$view->blocks[] = new Block("names/nameInfo.inc",array("name"=>$_SESSION['name']));


		# Show the Add Street Name form
		$view->blocks[] = new Block("streetNames/addStreetNameForm.inc",array("name"=>$_SESSION['name'],"street"=>$_SESSION['street']));
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
				$street_id = $_SESSION['street']->getId();
				unset($_SESSION['name']);
				unset($_SESSION['street']);

				# Done - send em to the success page
				Header("Location: viewStreet.php?street_id=$street_id");
				exit();
			}
			catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
		}
	}

 	$view->render();
?>