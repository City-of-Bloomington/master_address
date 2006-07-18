<div class="interfaceBox">
	<div class="titleBar">Annexations</div>
	<table>
	<?php
		$annexationList = new AnnexationList();
		$annexationList->find();
		foreach($annexationList as $annexation)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/annexations/updateAnnexation.php?id={$annexation->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$annexation->getOrdinanceNumber()}</td>
				<td>{$annexation->getName()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>