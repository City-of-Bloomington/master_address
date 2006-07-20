<?php
/*
	$_GET variables:	return_url
						name_id			# Name and Street might not be sent,
						street_id		# In which case, we'll need to show
										# find and add forms.
	---------------------------------------------------------------------------
	$_POST variables:	name_id
						street_id
						streetNameType_id

	$_SESSION variables:	return_url
							error_url
*/
	verifyUser("Administrator");

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
			Header("Location: $_SESSION[return_url]");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->streetName = $streetName;
			$view->addBlock("streetNames/addStreetNameForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->
	}

	$streetName = new StreetName();
	$streetName->setStreet_id($_POST['street_id']);
	$streetName->setName_id($_POST['name_id']);
	$streetName->setStreetNameType_id($_POST['streetNameType_id']);


 	$return_url = isset($_SESSION['return_url']) ? "$_SESSION[return_url]" : BASE_URL."/streetNames/viewStreetName.php?id={$streetName->getId()}";
	Header("Location: $return_url");
?>