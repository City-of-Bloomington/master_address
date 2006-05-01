<?php
/*
	$_POST variables:	directionCode
						direction
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Direction.inc");
	$direction = new Direction($_POST['directionCode']);
	$direction->setDirection($_POST['direction']);

	try
	{
		$direction->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDirectionForm.php?directionCode=$_POST[directionCode]");
	}
?>