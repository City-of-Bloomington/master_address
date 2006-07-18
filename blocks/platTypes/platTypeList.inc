<div class="interfaceBox">
	<div class="titleBar">PlatTypes</div>
	<table>
	<?php
		$list = new PlatTypeList();
		$list->find();
		foreach($list as $type)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/platTypes/updatePlatType.php?id={$type->getId()}'\">Edit</button></td>"; }
			echo "
				<td>{$type->getType()}</td>
				<td>{$type->getDescription()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>