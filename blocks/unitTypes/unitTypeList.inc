<div class="interfaceBox">
	<div class="titleBar">Unit Types</div>
	<table>
	<?php
		$unitTypeList = new UnitTypeList();
		$unitTypeList->find();
		foreach($unitTypeList as $unitType)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/unitTypes/updateUnitType.php?id={$unitType->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$unitType->getType()}</td>
				<td>{$unitType->getDescription()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>