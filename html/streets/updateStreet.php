<?php
/*
	$_POST variables:	id
						name
*/
	verifyUser("Administrator");

	$town = new Town($_POST['id']);
	$town->setName($_POST['name']);

	try
	{
		$town->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateTownForm.php?id=$_POST[id]");
	}
?>