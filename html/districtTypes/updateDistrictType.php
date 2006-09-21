<?php
/*
	$_GET variables:	id
	-----------------------------------------
	$_POST variables:	id
						districtType[ type ]
*/
	verifyUser("Administrator");
	$view = new View();
	$form = new Block("districtTypes/updateDistrictTypeForm.inc");
	if (isset($_GET['id'])) { $form->districtType = new DistrictType($_GET['id']); }

	if (isset($_POST['districtType']))
	{
		$districtType = new DistrictType($_POST['id']);
		foreach($_POST['districtType'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$districtType->$set($value);
		}

		try
		{
			$districtType->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->districtType = $districtType;
		}
	}

	$view->blocks[] = $form;
	$view->render();
?>