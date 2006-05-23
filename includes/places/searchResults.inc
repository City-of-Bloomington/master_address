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
			echo "<h2>Places Found</h2>";
			$placeList = new PlaceList();
			$placeList->find($search);

			if (count($placeList))
			{
				echo "<table>";
				if (!isset($_GET['page'])) { $_GET['page'] = 0; }
				$pages = $placeList->getPagination(50);

				$iterator = new LimitIterator($placeList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());
				foreach($iterator as $place)
				{
					echo "
					<tr><td>{$place->getId()}</td>
					</tr>
					";
				}
				echo "</table>";

				include(APPLICATION_HOME."/includes/pageNavigation.inc");
			}
		}
	}
?>