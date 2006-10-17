<?php
/*
	$_GET variables:	id
	----------------------------------
	$_POST variables:	id
						township [ name
									abbreviation
									quarterCode
								]
*/
	verifyUser("Administrator");
	$template = new Template();
	$form = new Block("townships/updateTownshipForm.inc");
	if (isset($_GET['id'])) { $form->township = new Township($_GET['id']); }

	if (isset($_POST['township']))
	{
		$township = new Township($_POST['id']);
		foreach($_POST['township'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$township->$set($value);
		}

		try
		{
			$township->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->township = $township;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>