<?php
/*
	$_GET variables:	id
	----------------------------------------------
	$_POST variables:	id
						direction [	code
									direction
								]
*/
	verifyUser("Administrator");

	$template = new Template();
	$form = new Block("directions/updateDirectionForm.inc");
	if (isset($_GET['id'])) { $form->direction = new Direction($_GET['id']); }


	if (isset($_POST['direction']))
	{
		$direction = new Direction($_POST['id']);
		foreach($_POST['direction'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$direction->$set($value);
		}

		try
		{
			$direction->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->direction = $direction;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>