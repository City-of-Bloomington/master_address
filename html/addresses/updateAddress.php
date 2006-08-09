<?php
/*
	$_GET variables:	address_id
						return_url
*/
	verifyUser("Administrator");

	$view = new View();
	if (isset($_GET['address_id'])) { $view->address = new Address($_GET['address_id']); }
	if (isset($_GET['return_url'])) { $_SESSION['return_url'] = new URL($_GET['return_url']); }

	$view->addBlock("addresses/updateAddressForm.inc");
	if (isset($_POST['address']))
	{
		$address = new Address($_POST['address_id']);
		foreach($_POST['address'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$address->$set($value);
		}

		try
		{
			$address->save();
			Header("Location: viewAddress.php?address_id={$address->getId()}");
			exit();
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$view->address = $address;
		}
	}

	$view->render();
?>