<div class="interfaceBox">
	<div class="titleBar">Suffixs</div>
	<table>
	<?php
		$suffixList = new SuffixList();
		$suffixList->find();
		foreach($suffixList as $suffix)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/suffixes/updateSuffix.php?id={$suffix->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$suffix->getSuffix()}</td>
				<td>{$suffix->getDescription()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>