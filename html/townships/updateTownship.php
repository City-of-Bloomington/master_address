<?php
/*
	$_POST variables:	id
						name
						abbreviation
						quarterCode
*/
	verifyUser("Administrator");

	$township = new Township($_POST['id']);
	$township->setName($_POST['name']);
	$township->setAbbreviation($_POST['abbreviation']);
	$township->setQuarterCode($_POST['quarterCode']);

	try
	{
		$township->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateTownshipForm.php?id=$_POST[id]");
	}
?>