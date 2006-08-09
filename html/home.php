<?php
	$view = new View();
	$view->addBlock("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$view->search = new AddressSearch(array('fullAddress'=>$_GET['fullAddress']));
		$view->response = new URL("addresses/viewAddress.php");
		$view->addBlock("addresses/searchResults.inc");
	}
	$view->render();
?>