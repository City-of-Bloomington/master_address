<?php
/*
	$_POST variables:	type
						description
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/PlatType.inc");
	$platType = new PlatType($_POST['type']);
	$platType->setDescription($_POST['description']);

	try
	{
		$platType->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updatePlatTypeForm.php?type=$_POST[type]");
	}
?>