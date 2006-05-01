<?php
/*
	$_POST variables:	jurisdictionID
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Jurisdiction.inc");
	$jurisdiction = new Jurisdiction($_POST['jurisdictionID']);
	$jurisdiction->setName($_POST['name']);

	try
	{
		$jurisdiction->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateJurisdictionForm.php?jurisdictionID=$_POST[jurisdictionID]");
	}
?>