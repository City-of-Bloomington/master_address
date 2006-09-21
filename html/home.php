<?php
	$view = new View();
	$view->blocks[] = new Block("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$view->blocks[] = new Block("addresses/searchResults.inc",
									array( "search"=>new AddressSearch(array('fullAddress'=>$_GET['fullAddress'])),
											"response"=>new URL("addresses/viewAddress.php") ) );
	}
	$view->render();
?>