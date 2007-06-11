<h1><?php echo $this->district->getName(); ?></h1>

<div class="interfaceBox">
	<?php
		$places = $this->district->getPlaces();
		echo "<div class=\"titleBar\">Places (".count($places).")</div>";
		if (count($places))
		{
			echo "
			<table>
			<tr><th>Place ID</th><th>Start Date</th><th>Addresses</th></tr>
			";

			# We want to page these, as there can be thousands of places per district
			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$places = new ArrayObject($places);
			$pages = new Paginator($places,50);
			$iterator = new LimitIterator($places->getIterator(),$pages[$_GET['page']],$pages->getPageSize());
			foreach($iterator as $place)
			{
				echo "
				<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getId()}</a></td>
					<td>{$place->getStartDate()}</td>
					<td><ul class=\"compact\">";
				foreach($place->getAddressList() as $address)
				{
					echo "<li><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">";
					echo $address->getFullAddress();
					echo "</a></li>";
				}
				echo "
					</ul></td>
				</tr>
				";
			}
			echo "</table>";

			include(FRAMEWORK."/pageNavigation.inc");
		}
	?>
</div>