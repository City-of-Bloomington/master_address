<?php
/*
	Required variables:		place  # Must be a place object
*/
?>
	<div class="interfaceBox"><div class="titleBar">Place Information: <?php echo $place->getName(); ?></div>
	<table>
	<tr><th>Township</th><td><?php echo $place->getTownship(); ?></td></tr>
	<tr><th>Jurisdiction</th><td><?php echo $place->getJurisdiction(); ?></td></tr>
	<tr><th>Trash Pickup</th><td><?php echo $place->getTrashPickupDay(); ?></td></tr>
	<tr><th>Large Item Pickup</th><td><?php echo $place->getTrashLargeItemPickupDay(); ?></td></tr>
	<tr><th>Recycling Week</th><td><?php echo $place->getRecyclingPickupWeek(); ?></td></tr>
	<tr><th>Mailable</th><td><?php echo $place->getMailable(); ?></td></tr>
	<tr><th>Livable</th><td><?php echo $place->getLivable(); ?></td></tr>
	<tr><th>Section</th><td><?php echo $place->getSection(); ?></td></tr>
	<tr><th>Quarter Section</th><td><?php echo $place->getQuarterSection(); ?></td></tr>
	<tr><th>Class</th><td><?php echo $place->getClass(); ?></td></tr>
	<tr><th>Type</th><td><?php echo $place->getPlaceType(); ?></td></tr>
	<tr><th>Census Code</th><td><?php echo $place->getCensusBlockFIPSCode(); ?></td></tr>
	<tr><th>State Plane X</th><td><?php echo $place->getStatePlaneX(); ?></td></tr>
	<tr><th>State Plane Y</th><td><?php echo $place->getStatePlaneY(); ?></td></tr>
	<tr><th>Latitude</th><td><?php echo $place->getLatitude(); ?></td></tr>
	<tr><th>Longitude</th><td><?php echo $place->getLongitude(); ?></td></tr>
	<tr><th>Start Date</th><td><?php echo $place->getStartDate(); ?></td></tr>
	<tr><th>End Date</th><td><?php echo $place->getEndDate(); ?></td></tr>
	<tr><th>Status</th><td><?php echo $place->getStatus(); ?></td></tr>
	</table>
	</div>
