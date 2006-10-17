<?php
/*
	$_GET variables:	id
	--------------------------------------------
	$_POST variables:	id
						annexation [ ordinanceNumber
									name
								]
*/
	verifyUser("Administrator");

	$template = new Template();
	$block = new Block("annexations/updateAnnexationForm.inc");
	if (isset($_GET['id'])) { $block->annexation = new Annexation($_GET['id']); }


	if (isset($_POST['annexation']))
	{
		$annexation = new Annexation($_POST['id']);
		foreach($_POST['annexation'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$annexation->$set($value);
		}
		try
		{
			$annexation->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$block->annexation = $annexation;
		}
	}

	$template->blocks[] = $block;
	$template->render();
?>