<?php
/*
	$_POST variables:	street[	id
								status_id
								notes
							]

						return_url
*/
	verifyUser("Administrator");

	$street = new Street($_POST['street']['id']);
	$street->setStatus_id($_POST['street']['status_id']);
	$street->setNotes($_POST['street']['notes']);

	try
	{
		$street->save();
		Header("Location: $_POST[return_url]{$street->getId()}");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateStreetForm.php?id={$street->getId()}");
	}
?>