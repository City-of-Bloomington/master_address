<div class="interfaceBox">
	<div class="titleBar">PlaceTypes</div>
	<table>
	<?php
		$placeTypeList = new PlaceTypeList();
		$placeTypeList->find();
		foreach($placeTypeList as $placeType)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/placeTypes/updatePlaceType.php?id={$placeType->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$placeType->getType()}</td>
				<td>{$placeType->getDescription()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>