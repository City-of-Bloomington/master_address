<?php
/*
	$this->place
*/
	$addButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/buildings/addBuilding.php?place_id={$this->place->getId()}');\">Add</button>" : "";
?>
<div class="titleBar"><?php echo $addButton; ?>Buildings</div>
<table>
<tr><th>Name</th><th>Tag</th><th>Start Date</th><th>End Date</th><th>Status</th></tr>
<?php
	foreach($this->place->getBuildings() as $building)
	{
		$addButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/units/addUnit.php?place_id={$this->place->getId()};building_id={$building->getId()}');\">Add</button>" : "";
		echo "
		<tr><td><a href=\"".BASE_URL."/buildings/viewBuilding.php?building_id={$building->getId()}\">{$building->getName()}</a></td>
			<td><a href=\"".BASE_URL."/buildings/viewBuilding.php?building_id={$building->getId()}\">{$building->getGISTag()}</a></td>
			<td><a href=\"".BASE_URL."/buildings/viewBuilding.php?building_id={$building->getId()}\">{$building->getStartDate()}</a></td>
			<td><a href=\"".BASE_URL."/buildings/viewBuilding.php?building_id={$building->getId()}\">{$building->getEndDate()}</a></td>
			<td><a href=\"".BASE_URL."/buildings/viewBuilding.php?building_id={$building->getId()}\">{$building->getStatus()->getStatus()}</a></td>
		</tr>
		<tr><td></td>
			<th>{$addButton}Units</th>
			<td colspan=\"3\">
				<table>
				<tr><th>ID</th>
					<th>Type</th>
					<th>Identifier</th>
					<th>Status</th>
					<th>Mailable</th>
					<th>Livable</th>
				</tr>
		";
			if (count($building->getUnits()))
			{
				foreach($building->getUnits() as $unit)
				{
					echo "
					<tr><td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getId()}</a></td>
						<td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getUnitType()->getType()}</a></td>
						<td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getIdentifier()}</a></td>
						<td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getStatus()->getStatus()}</a></td>
						<td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getMailable()}</a></td>
						<td><a href=\"".BASE_URL."/units/viewUnit.php?unit_id={$unit->getId()}\">{$unit->getLivable()}</a></td>
					</tr>
					";
				}
			}
		echo "
			</table>
		</td></tr>
		";
	}
?>
</table>