<?php
/*
	$_GET variables:	id
	-----------------------------------------
	$_POST variables:	id
						districtType[ type ]
*/
	verifyUser("Administrator");

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

			$view = new View();
			$view->districtType = $districtType;
			$view->addBlock("districtTypes/updateDistrictTypeForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->districtType = new DistrictType($_GET['id']);
		$view->addBlock("districtTypes/updateDistrictTypeForm.inc");
		$view->render();
	}
?>