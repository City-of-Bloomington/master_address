<?php
/*
	$_GET variables:	id
	------------------------------------------------------
	$_POST variables:	id
						district [	name
									districtType_id
								]
*/
	verifyUser("Administrator");

	if (isset($_POST['district']))
	{
		$district = new District($_POST['id']);
		foreach($_POST['district'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$district->$set($value);
		}
		try
		{
			$district->save();
			Header("Location: viewDistrict.php?id=$_POST[id]");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->district = $district;
			$view->addBlock("districts/udpateDistrictForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->district = new District($_GET['id']);
		$view->addBlock("districts/updateDistrictForm.inc");
		$view->render();
	}
?>