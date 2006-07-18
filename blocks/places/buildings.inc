<div class="titleBar">Buildings</div>
<table>
<tr><th>Name</th><th>Tag</th><th>Start Date</th><th>End Date</th><th>Status</th></tr>
<?php
	foreach($this->place->getBuildings() as $building)
	{
		echo "
		<tr><td>{$building->getName()}</td>
			<td>{$building->getGISTag()}</td>
			<td>{$building->getStartDate()}</td>
			<td>{$building->getEndDate()}</td>
			<td>{$building->getStatus()->getStatus()}</td>
		</tr>
		";
	}
?>
</table>