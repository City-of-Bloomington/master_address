<?php
	$view = new View();
	$response = new URL("viewAddress.php");

	$view->blocks[] = new Block("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$view->blocks[] = new Block("addresses/searchResults.inc",
									array("search"=>new AddressSearch(array('fullAddress'=>$_GET['fullAddress'])),
											"response"=>$response));
	}

	$view->blocks[] = new Block("addresses/findAddressForm.inc");
	if(isset($_GET['address']) && isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['address'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }

		if(count($search))
		{
			$view->blocks[] = new Block("addresses/findAddressResults.inc",array("addressList"=>new AddressList($search),"response"=>$response));
		}
	}
	$view->render();
?>