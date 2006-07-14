<?php
/*
	Required variables:		place  # Must be a place object
*/
?>
	<div class="interfaceBox">
		<div class="titleBar">
			<?php
				if (userHasRole("Administrator"))
				{
					echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/places/updatePlaceForm.php?id={$place->getId()}';\">Edit</button>";
				}
				echo "Place: <a href=\"".BASE_URL."/places/viewPlace.php?id={$place->getId()}\">{$place->getId()}: {$place->getName()}</a>";
			?>
		</div>
	<table>
	<tr><th>Status</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th></th></tr>
	<tr><td><?php echo $place->getStatus(); ?></td>
		<td><?php echo $place->getStartDate(); ?></td>
		<td><?php echo $place->getEndDate(); ?></td>
		<td></td></tr>

	<tr><th>Jurisdiction</th>
		<th>Township</th>
		<th>Section</th>
		<th>Quarter Section</th></tr>
	<tr><td><?php echo $place->getJurisdiction(); ?></td>
		<td><?php echo $place->getTownship(); ?></td>
		<td><?php echo $place->getSection(); ?></td>
		<td><?php echo $place->getQuarterSection(); ?></td></tr>

	<tr><th>Type</th>
		<th>Class</th>
		<th>Census Code</th>
		<th></th></tr>
	<tr><td><?php echo $place->getPlaceType(); ?></td>
		<td><?php echo $place->getClass(); ?></td>
		<td><?php echo $place->getCensusBlockFIPSCode(); ?></td>
		<td></td></tr>

	<tr><th>Mailable</th>
		<th>Livable</th>
		<th></th>
		<th></th></tr>
	<tr><td><?php echo $place->getMailable(); ?></td>
		<td><?php echo $place->getLivable(); ?></td>
		<td></td>
		<td></td></tr>

	<tr><th>State Plane X</th>
		<th>State Plane Y</th>
		<th>Latitude</th>
		<th>Longitude</th></tr>
	<tr><td><?php echo $place->getStatePlaneX(); ?></td>
		<td><?php echo $place->getStatePlaneY(); ?></td>
		<td><?php echo $place->getLatitude(); ?></td>
		<td><?php echo $place->getLongitude(); ?></td></tr>

	<tr><th>Trash Pickup</th>
		<th>Recycling Week</th>
		<th></th>
		<th></th></tr>
	<tr><td><?php echo $place->getTrashPickupDay(); ?></td>
		<td><?php echo $place->getRecyclingPickupWeek(); ?></td>
		<td></td>
		<td></td></tr>
	</table>
	</div>