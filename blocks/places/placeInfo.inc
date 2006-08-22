<div class="interfaceBox">
	<div class="titleBar">
	<?php
		$editButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/places/updatePlace.php?place_id={$this->place->getId()}';\">Edit</button>" : "";
		echo "
		$editButton
		Place:
		<a href=\"".BASE_URL."/places/viewPlace.php?place_id={$this->place->getId()}\">{$this->place->getId()}:
		{$this->place->getName()}</a>
		";
	?>
	</div>
<table>
<tr><th>Status</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th></th></tr>
<tr><td><?php echo $this->place->getStatus(); ?></td>
	<td><?php echo $this->place->getStartDate(); ?></td>
	<td><?php echo $this->place->getEndDate(); ?></td>
	<td></td></tr>

<tr><th>Jurisdiction</th>
	<th>Township</th>
	<th>Section</th>
	<th>Quarter Section</th></tr>
<tr><td><?php echo $this->place->getJurisdiction(); ?></td>
	<td><?php echo $this->place->getTownship(); ?></td>
	<td><?php echo $this->place->getSection(); ?></td>
	<td><?php echo $this->place->getQuarterSection(); ?></td></tr>

<tr><th>Type</th>
	<th>Class</th>
	<th>Census Code</th>
	<th></th></tr>
<tr><td><?php echo $this->place->getPlaceType(); ?></td>
	<td><?php echo $this->place->getClass(); ?></td>
	<td><?php echo $this->place->getCensusBlockFIPSCode(); ?></td>
	<td></td></tr>

<tr><th>Mailable</th>
	<th>Livable</th>
	<th></th>
	<th></th></tr>
<tr><td><?php echo $this->place->getMailable(); ?></td>
	<td><?php echo $this->place->getLivable(); ?></td>
	<td></td>
	<td></td></tr>

<tr><th>State Plane X</th>
	<th>State Plane Y</th>
	<th>Latitude</th>
	<th>Longitude</th></tr>
<tr><td><?php echo $this->place->getStatePlaneX(); ?></td>
	<td><?php echo $this->place->getStatePlaneY(); ?></td>
	<td><?php echo $this->place->getLatitude(); ?></td>
	<td><?php echo $this->place->getLongitude(); ?></td></tr>

<tr><th>Trash Pickup</th>
	<th>Recycling Week</th>
	<th></th>
	<th></th></tr>
<tr><td><?php echo $this->place->getTrashPickupDay(); ?></td>
	<td><?php echo $this->place->getRecyclingPickupWeek(); ?></td>
	<td></td>
	<td></td></tr>

<tr><th colspan="2">Plat</th>
	<th>Lot</th>
	<th></th></tr>
<tr><td colspan="2"><?php if ($this->place->getPlat()) echo $this->place->getPlat(); ?></td>
	<td><?php echo $this->place->getLotNumber(); ?></td>
	<td></td></tr>

</table>
</div>