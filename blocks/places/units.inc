<div class="titleBar">Unassigned Units</div>
<table>
<tr><th>ID</th><th>Identifier</th><th>Type</th><th>Status</th><th>Mailable</th><th>Livable</th></tr>
<?php
	foreach($this->place->getUnassignedUnits() as $unit)
	{
		echo "
		<tr><td>{$unit->getId()}</td>
			<td>{$unit->getIdentifier()}</td>
			<td>{$unit->getUnitType()->getType()}</td>
			<td>{$unit->getStatus()->getStatus()}</td>
			<td>{$unit->getMailable()}</td>
			<td>{$unit->getLivable()}</td>
		</tr>
		";
	}
?>
</table>