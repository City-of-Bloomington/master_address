<?php
/*
	$_GET variables:	streetName_id
						return_url
*/
	verifyUser("Administrator");

	$template = new Template();
	$form = new Block('streetNames/updateStreetNameForm.inc');
	if (isset($_GET['streetName_id']) && isset($_GET['return_url']))
	{
		$streetName = new StreetName($_GET['streetName_id']);
		$response = new URL($_GET['return_url']);
	}

	if (isset($_POST['streetName']))
	{
		$streetName = new StreetName($_POST['streetName_id']);
		$response = new URL($_POST['response']);

		foreach($_POST['streetName'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$streetName->$set($value);
		}
		try
		{
			$streetName->save();
			Header("Location: {$response->getURL()}");
			exit();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template->blocks[] = new Block("names/nameInfo.inc",array('name'=>$streetName->getName()));
	$template->blocks[] = new Block("streets/streetInfo.inc",array('street'=>$streetName->getStreet(),'response'=>$response));

	$form->streetName = $streetName;
	$form->response = $response;
	$template->blocks[] = $form;

	$template->render();
?>