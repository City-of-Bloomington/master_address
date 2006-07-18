<div class="interfaceBox">
	<div class="titleBar">Towns</div>
	<table>
	<?php
		$townList = new TownList();
		$townList->find();
		foreach($townList as $town)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/towns/updateTown.php?id={$town->getId()}'\">Edit</button></td>"; }
			echo "<td>{$town->getName()}</td></tr>";
		}
	?>
	</table>
</div>