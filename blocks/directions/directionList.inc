<div class="interfaceBox">
	<div class="titleBar">Directions</div>
	<table>
	<?php
		$directionList = new DirectionList();
		$directionList->find();
		foreach($directionList as $direction)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/directions/updateDirection.php?id={$direction->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$direction->getCode()}</td>
				<td>{$direction->getDirection()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>