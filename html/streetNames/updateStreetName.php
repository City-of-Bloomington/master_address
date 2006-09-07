<?php
/*
	$_GET variables:	streetName_id
						return_url
*/
	verifyUser("Administrator");
	if (isset($_GET['streetName_id'])) { $_SESSION['streetName'] = new StreetName($_GET['streetName_id']); }
	if (isset($_GET['return_url'])) { $_SESSION['response'] = new URL($_GET['return_url']); }

	$view = new View();

	$view->name = $_SESSION['streetName']->getName();
	$view->addBlock("names/nameInfo.inc");

	$view->street = $_SESSION['streetName']->getStreet();
	$view->response = new URL($_SERVER['REQUEST_URI']);
	$view->addBlock("streets/streetInfo.inc");


	$view->streetName = $_SESSION['streetName'];
	$view->addBlock("streetNames/updateStreetNameForm.inc");
	if (isset($_POST['streetName']))
	{
		$streetName = new StreetName($_POST['streetName_id']);
		foreach($_POST['streetName'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$streetName->$set($value);
		}
		try
		{
			$streetName->save();

			$response = $_SESSION['response'];
			unset($_SESSION['streetName']);
			unset($_SESSION['response']);

			Header("Location: {$response->getURL()}");
			exit();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>