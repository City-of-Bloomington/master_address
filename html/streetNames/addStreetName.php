<?php
/*
	$_POST variables:	name_id
						street_id
						streetNameType_id

	$_SESSION variables:	return_url
							error_url
*/
	verifyUser("Administrator");

	$streetName = new StreetName();
	$streetName->setStreet_id($_POST['street_id']);
	$streetName->setName_id($_POST['name_id']);
	$streetName->setStreetNameType_id($_POST['streetNameType_id']);
	try { $streetName->save(); }
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: $_SESSION[error_url]");
		exit();
	}


 	$return_url = isset($_SESSION['return_url']) ? "$_SESSION[return_url]" : BASE_URL."/streetNames/viewStreetName.php?id={$streetName->getId()}";
	Header("Location: $return_url");
?>