<?php
/*
	$_POST variables:	id
						name
						township_id
						platType_id
						cabinet
						envelope
						notes
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Plat.inc");
	try
	{
		$plat = new Plat($_POST['id']);
		$plat->setName($_POST['name']);
		$plat->setTownship_id($_POST['township_id']);
		$plat->setPlatType_id($_POST['platType_id']);
		$plat->setCabinet($_POST['cabinet']);
		$plat->setEnvelope($_POST['envelope']);
		$plat->setNotes($_POST['notes']);

		$plat->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updatePlatForm.php?id=$_POST[id]");
	}
?>