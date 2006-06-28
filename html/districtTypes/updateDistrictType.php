<?php
/*
	$_POST variables:	id
						type
*/
	verifyUser("Administrator");

	$districtType = new DistrictType($PDO,$_POST['id']);
	$districtType->setType($_POST['type']);

	try
	{
		$districtType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDistrictTypeForm.php?id=$_POST[id]");
	}
?>