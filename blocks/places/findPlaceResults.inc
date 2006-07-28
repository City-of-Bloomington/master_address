<?php
/*
	$_GET[page]
	$this->placeList
	$this->response
*/
	switch (count($this->placeList))
	{
		case 0:
			echo "<p>No Places Found</p>";
		break;

		default:
			echo "
			<h2>Places Found</h2>
			<table>
			<tr><th>Place ID</th><th>Start Date</th><th>Addresses</th></tr>
			";

			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$pages = $this->placeList->getPagination(50);
			$iterator = new LimitIterator($this->placeList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

			foreach($iterator as $place)
			{
				$this->response->parameters['place_id'] = $place->getId();

				echo "
				<tr><td><a href=\"{$this->response->getURL()}\">{$place->getId()}</a></td>
					<td>{$place->getStartDate()}</td>
					<td><ul class=\"compact\">";
						foreach($place->getAddressList() as $address) { echo "<li>{$address->getFullAddress()}</li>"; }
				echo "
					</ul></td>
				</tr>
				";
			}
			echo "</table>";
			include(GLOBAL_INCLUDES."/pageNavigation.inc");
	}
?>