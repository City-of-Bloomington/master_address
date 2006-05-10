<?php
/*
	$_POST variables:	id
						status
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Status.inc");
	$status = new Status($_POST['id']);
	$status->setStatus($_POST['status']);

	try
	{
		$status->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateStatusForm.php?id=$_POST[id]");
	}
?>