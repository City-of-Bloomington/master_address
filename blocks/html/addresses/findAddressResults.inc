<?php
/*
	$this->addressList
	$this->response
*/
	switch(count($this->addressList))
	{
		case 0:
			echo "<p>No Names Found.</p>";
		break;

		default:
			echo "<table>";
			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$pages = $this->addressList->getPagination(50);
			$iterator = new LimitIterator($this->addressList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

			foreach($iterator as $result)
			{
				$this->response->parameters['address_id'] = $result->getId();
				echo "<tr><td><a href=\"{$this->response->getURL()}\">{$result->getFullAddress()}</a></td></tr>";
			}
			echo "</table>";

			include(FRAMEWORK."/pageNavigation.inc");
	}
?>
