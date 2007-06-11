<?php
/*
	$this->placeList
*/
	switch (count($this->placeList))
	{
		case 0:
			echo "<p>No Places Found</p>";
		break;

		default:
			echo "
			<h2>Places Found</h2>
			<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">
			<fieldset><legend>Choose which places to add</legend>
				<table>
				<tr><th></th><th>Place ID</th><th>Start Date</th><th>Addresses</th></tr>
			";

				if (!isset($_GET['page'])) { $_GET['page'] = 0; }
				$pages = $this->placeList->getPagination(50);
				$iterator = new LimitIterator($this->placeList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

				foreach($iterator as $place)
				{
					echo "
					<tr><td><input name=\"places[{$place->getId()}]\" id=\"places-{$place->getId()}\" type=\"checkbox\" /></td>
						<td><label for=\"places-{$place->getId()}\">{$place->getId()}</label></td>
						<td>{$place->getStartDate()}</td>
						<td><ul class=\"compact\">";
							foreach($place->getAddressList() as $address) { echo "<li>{$address->getFullAddress()}</li>"; }
					echo "
						</ul></td>
					</tr>
					";
				}
			echo "
				</table>

				<button type=\"submit\" class=\"submit\">Submit</button>
			</fieldset>
			";
			include(FRAMEWORK."/pageNavigation.inc");
	}
?>