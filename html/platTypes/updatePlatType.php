<?php
/*
	$_GET variables:	id
	----------------------------------------
	$_POST variables:	id
						platType [ type
									description
								]
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block('platTypes/updatePlatTypeForm.inc');
	if (isset($_GET['id'])) { $form->platType = new PlatType($_GET['id']); }

	if (isset($_POST['platType']))
	{
		$platType = new PlatType($_POST['id']);
		foreach($_POST['platType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$platType->$set($value);
		}

		try
		{
			$platType->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->platType = $platType;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>