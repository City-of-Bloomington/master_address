<div class="interfaceBox">
	<div class="titleBar">Plats</div>
	<table>
	<?php
		$platList = new PlatList();
		$platList->find();
		foreach($platList as $plat)
		{
			$township = $plat->getTownship_id() ? $plat->getTownship()->getName() : "";
			$type = $plat->getPlatType_id() ? $plat->getPlatType()->getType() : "";

			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/plats/updatePlat.php?id={$plat->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$plat->getName()}</td>
				<td>$township</td>
				<td>$type</td>
				<td>{$plat->getCabinet()}</td>
				<td>{$plat->getEnvelope()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>