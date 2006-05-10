<?php
/*
	$_POST variables:	id
						code
						direction
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Direction.inc");
	$direction = new Direction($_POST['id']);
	$direction->setCode($_POST['code']);
	$direction->setDirection($_POST['direction']);

	try
	{
		$direction->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateDirectionForm.php?id=$_POST[id]");
	}
?>