<?php
/*
	$_GET variables:	id
	-------------------------------
	$_POST variables:	id
						city [ name ]
*/
	verifyUser("Administrator");
	$template = new Template();
	$form = new Block("cities/updateCityForm.inc");
	if (isset($_GET['id'])) { $form->city = new City($_GET['id']); }

	if (isset($_POST['city']))
	{
		$city = new City($_POST['id']);
		foreach($_POST['city'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$city->$set($value);
		}

		try
		{
			$city->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->city = $city;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>