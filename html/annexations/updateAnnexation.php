<?php
/*
	$_POST variables:	id
						ordinanceNumber
						name
*/
	verifyUser("Administrator");

	$annexation = new Annexation($_POST['id']);
	$annexation->setOrdinanceNumber($_POST['ordinanceNumber']);
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