<?php
/*
	$this->buildingTypeList
*/
?>
<div class="interfaceBox">
	<div class="titleBar">Building Types</div>
	<table>
	<?php
		foreach($this->buildingTypeList as $buildingType)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/buildingTypes/updateBuildingType.php?id={$buildingType->getId()}'\">Edit</button></td>"; }
			echo "<td>{$buildingType->getDescription()}</td></tr>";
		}
	?>
	</table>
</div>