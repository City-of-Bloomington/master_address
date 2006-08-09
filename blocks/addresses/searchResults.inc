<?php
/*
	$this->search
	$this->response
*/
	if (count($this->search))
	{
		echo "<table>";
		if (!isset($_GET['page'])) { $_GET['page'] = 0; }
		$pages = $this->search->getPagination(50);
		$iterator = new LimitIterator($this->search->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

		foreach($iterator as $result)
		{
			$this->response->parameters['address_id'] = $result->getAddress_id();
			echo "<tr><td><a href=\"{$this->response->getURL()}\">{$result->getFullAddress()}</a></td></tr>";
		}
		echo "</table>";

		include(GLOBAL_INCLUDES."/pageNavigation.inc");
	}
	else { echo "<p>No addresses found</p>"; }
?>