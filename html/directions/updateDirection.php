<?php
/*
	$_POST variables:	id
						code
						direction
*/
	verifyUser("Administrator");

	$direction = new Direction($PDO,$_POST['id']);
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