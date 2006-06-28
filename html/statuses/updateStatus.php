<?php
/*
	$_POST variables:	id
						status
*/
	verifyUser("Administrator");

	$status = new Status($PDO,$_POST['id']);
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