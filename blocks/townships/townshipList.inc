<div class="interfaceBox">
	<div class="titleBar">Townships</div>
	<table>
	<?php
		$townshipList = new TownshipList();
		$townshipList->find();
		foreach($townshipList as $township)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/townships/updateTownship.php?id={$township->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$township->getName()} ({$township->getAbbreviation()})</td>
				<td>{$township->getQuarterCode()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>