<?php
/*
	$_POST variables:	districtID
						name
						districtTypeID
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/District.inc");
	$district = new District($_POST['districtID']);
	$district->setName($_POST['name']);
	$district->setDistrictTypeID($_POST['districtTypeID']);

	try
	{
		$district->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDistrictForm.php?districtID=$_POST[districtID]");
	}
?>