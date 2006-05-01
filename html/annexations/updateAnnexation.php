<?php
/*
	$_POST variables:	ordinanceNumber
						name
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Annexation.inc");
	$annexation = new Annexation($_POST['ordinanceNumber']);
	$annexation->setName($_POST['name']);

	try
	{
		$annexation->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateAnnexationForm.php?ordinanceNumber=$_POST[ordinanceNumber]");
	}
?>