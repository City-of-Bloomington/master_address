<?php
/*
	$_POST variables:	name_id
						streetNameType_id
						status_id
						street_notes
*/
	verifyUser("Administrator");

	$street = new Street($PDO);
	$street->setNotes($_POST['notes']);
	$street->setStatus_id($_POST['status_id']);
	try { $street->save(); }
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: addStreetForm.php?name_id=$_POST[name_id]");
		exit();
	}

	$streetName = new StreetName($PDO);
	$streetName->setStreet_id($street->getId());
	$streetName->setName_id($_POST['name_id']);
	$streetName->setStreetNameType_id($_POST['streetNameType_id']);
	try { $streetName->save(); }
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: ".BASE_URL."/streetNames/addStreetNameForm.php?name_id=$_POST[name_id];street_id={$street->getId()}");
		exit();
	}

	Header("Location: viewStreet.php?id={$street->getId()}");
?>