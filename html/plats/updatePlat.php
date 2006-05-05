<?php
/*
	$_POST variables:	platID
						name
						townshipID
						type
						cabinet
						envelope
						notes
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Plat.inc");
	try
	{
		$plat = new Plat($_POST['platID']);
		$plat->setName($_POST['name']);
		$plat->setTownshipID($_POST['townshipID']);
		$plat->setType($_POST['type']);
		$plat->setCabinet($_POST['cabinet']);
		$plat->setEnvelope($_POST['envelope']);
		$plat->setNotes($_POST['notes']);

		$plat->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updatePlatForm.php?platID=$_POST[platID]");
	}
?>