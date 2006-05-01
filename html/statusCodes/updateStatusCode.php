<?php
/*
	$_POST variables:	statusCode
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/StatusCode.inc");
	$statusCode = new StatusCode($_POST['statusCode']);
	$statusCode->setStatus($_POST['status']);

	try
	{
		$statusCode->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateStatusCodeForm.php?statusCode=$_POST[statusCode]");
	}
?>