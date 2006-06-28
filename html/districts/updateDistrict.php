<?php
/*
	$_POST variables:	id
						name
						districtType_id
*/
	verifyUser("Administrator");

	$district = new District($_POST['id']);
	$district->setName($_POST['name']);
	$district->setDistrictType_id($_POST['districtType_id']);

	try
	{
		$district->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDistrictForm.php?id=$_POST[id]");
	}
?>