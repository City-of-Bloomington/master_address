<?php
/*
	$_POST variables:	townID
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Town.inc");
	$town = new Town($_POST['townID']);
	$town->setName($_POST['name']);

	try
	{
		$town->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateTownForm.php?townID=$_POST[townID]");
	}
?>