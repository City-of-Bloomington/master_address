<?php
/*
	$_POST variables:	districtTypeID
						type
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/DistrictType.inc");
	$districtType = new DistrictType($_POST['districtTypeID']);
	$districtType->setType($_POST['type']);

	try
	{
		$districtType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDistrictTypeForm.php?districtTypeID=$_POST[districtTypeID]");
	}
?>