<?php
/*
	$_POST variables:	suffix
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Suffix.inc");
	$suffix = new Suffix($_POST['suffix']);
	$suffix->setDescription($_POST['description']);

	try
	{
		$suffix->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateSuffixForm.php?suffix=$_POST[suffix]");
	}
?>