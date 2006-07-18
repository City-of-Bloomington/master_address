<div class="interfaceBox">
	<div class="titleBar">Districts</div>
	<table>
	<?php
		$districtList = new DistrictList();
		$districtList->find();
		foreach($districtList as $district)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateDistrict.php?id={$district->getId()}'\">Edit</button></td>"; }
			echo "
				<td><a href=\"".BASE_URL."/districts/viewDistrict.php?id={$district->getId()}\">{$district->getName()}</a></td>
				<td><a href=\"".BASE_URL."/districts/viewDistrict.php?id={$district->getId()}\">{$district->getDistrictType()->getType()}</a></td>
			</tr>
			";
		}
	?>
	</table>
</div>
