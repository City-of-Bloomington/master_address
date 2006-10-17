<?php
/*
	$_GET variables:	plat_id
	-----------------------------------------
	$_POST variables:	plat_id
						plat [ name
								township_id
								platType_id
								cabinet
								envelope
								notes
								]
*/
	verifyUser("Administrator");
	$template = new Template();
	$form = new Block('plats/updatePlatForm.inc');
	if (isset($_GET['plat_id'])) { $form->plat = new Plat($_GET['plat_id']); }
	if (isset($_POST['plat']))
	{
		$plat = new Plat($_POST['id']);
		foreach($_POST['plat'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$plat->$set($value);
		}

		try
		{
			$plat->save();
			Header("Location: viewPlat.php?plat_id={$plat->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->plat = $plat;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>