<?php
/*
	$_GET variables:	id
	-----------------------------------------------
	$_POST variables:	id
						jurisdiction [ name ]
*/
	verifyUser("Administrator");
	$template = new Template();
	$form = new Block("jurisdictions/updateJurisdictionForm.inc");
	if (isset($_GET['id'])) { $form->jurisdiction = new Jurisdiction($_GET['id']); }

	if (isset($_POST['jurisdiction']))
	{
		$jurisdiction = new Jurisdiction($_POST['id']);
		foreach($_POST['jurisdiction'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$jurisdiction->$set($value);
		}

		try
		{
			$jurisdiction->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->jurisdiction = $jurisdiction;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>