<?php
	$template = new Template();
	$template->blocks[] = new Block("addresses/searchForm.inc");
	if (isset($_GET['fullAddress']))
	{
		$template->blocks[] = new Block("addresses/searchResults.inc",
									array( "search"=>new AddressSearch(array('fullAddress'=>$_GET['fullAddress'])),
											"response"=>new URL("addresses/viewAddress.php") ) );
	}
	$template->render();
?>