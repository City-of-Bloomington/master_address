<?php
/*
	$_GET variables:	id						mailable
						name					livable
						township_id				section
						jurisdiction_id			quarterSection
						trashPickupDay_id		placeType_id
						largeItemPickupDay_id
						recyclingPickupWeek_id
*/
	$search = array();
	if (count($_GET))
	{
		foreach($_GET as $field=>$value) { if ($value) { $search[$field] = $value; } }

		if (count($search))
		{
			$placeList = new PlaceList($search);

			echo "<h2>Places Found (".count($placeList).")</h2>";
			if (count($placeList))
			{
				echo "
				<table>
				<tr><th>Place ID</th><th>Start Date</th><th>Addresses</th></tr>
				";
				if (!isset($_GET['page'])) { $_GET['page'] = 0; }
				$pages = $placeList->getPagination(50);

				$iterator = new LimitIterator($placeList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());
				foreach($iterator as $place)
				{
					echo "
					<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?id={$place->getId()}\">{$place->getId()}</a></td>
						<td>{$place->getStartDate()}</td>
						<td><ul>";
					foreach($place->getAddressList() as $address)
					{
						echo "<li>{$address->getFullAddress()}</li>";
					}
					echo "
						</ul></td>
					</tr>
					";
				}
				echo "</table>";

				include(GLOBAL_INCLUDES."/pageNavigation.inc");
			}
		}
	}
?>