<?php
/*
	$_GET variables:	id
	-------------------------------------
	$_POST variables:	id
						suffix [ suffix
								description ]
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block("suffixes/updateSuffixForm.inc");
	if (isset($_GET['id'])) { $form->suffix = new Suffix($_GET['id']); }

	if (isset($_POST['suffix']))
	{
		$suffix = new Suffix($_POST['id']);
		foreach($_POST['suffix'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$suffix->$set($value);
		}

		try
		{
			$suffix->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->suffix = $suffix;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>