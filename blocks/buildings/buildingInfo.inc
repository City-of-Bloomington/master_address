<?php
/*
	$this->building
*/
	$editButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/buildings/updateBuilding.php?building_id={$this->building->getId()}';\">Edit</button>" : "";
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $editButton; ?>Building: <?php echo $this->building->getId(); ?></div>
	<table>
	<tr><th>Name</th><td><?php echo $this->building->getName(); ?></td></tr>
	<tr><th>Tag</th><td><?php echo $this->building->getGISTag(); ?></td></tr>
	<tr><th>Start Date</th><td><?php echo $this->building->getStartDate(); ?></td></tr>
	<tr><th>End Date</th><td><?php echo $this->building->getEndDate(); ?></td></tr>
	<tr><th>Status</th><td><?php echo $this->building->getStatus(); ?></td></tr>
	</table>
</div>