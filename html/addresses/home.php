<?php
	$view = new View();
	$view->addBlock("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$view->search = new AddressSearch(array('fullAddress'=>$_GET['fullAddress']));
		$view->response = new URL("viewAddress.php");
		$view->addBlock("addresses/searchResults.inc");
	}

	$view->addBlock("addresses/findAddressForm.inc");
	if(isset($_GET['address']) && isset($_GET['name']))
	{

	}
	$view->render();
?>