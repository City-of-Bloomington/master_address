<?php
/*
	$_POST variables:	id
						name
*/
	verifyUser("Administrator");

	$jurisdiction = new Jurisdiction($PDO,$_POST['id']);
	$jurisdiction->setName($_POST['name']);

	try
	{
		$jurisdiction->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateJurisdictionForm.php?id=$_POST[id]");
	}
?>