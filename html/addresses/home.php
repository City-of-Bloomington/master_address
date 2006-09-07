<?php
	$view = new View();
	$view->response = new URL("viewAddress.php");

	$view->addBlock("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$view->search = new AddressSearch(array('fullAddress'=>$_GET['fullAddress']));
		$view->addBlock("addresses/searchResults.inc");
	}

	$view->addBlock("addresses/findAddressForm.inc");
	if(isset($_GET['address']) && isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['address'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }

		if(count($search))
		{
			$view->addressList = new AddressList($search);
			$view->addBlock("addresses/findAddressResults.inc");
		}
	}
	$view->render();
?>