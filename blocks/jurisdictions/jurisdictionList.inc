<div class="interfaceBox">
	<div class="titleBar">Jurisdictions</div>
	<table>
	<?php
		$jurisdictionList = new JurisdictionList();
		$jurisdictionList->find();
		foreach($jurisdictionList as $jurisdiction)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/jurisdictions/updateJurisdiction.php?id={$jurisdiction->getId()}'\">Edit</button></td>"; }
			echo "<td>{$jurisdiction->getName()}</td></tr>";
		}
	?>
	</table>
</div>