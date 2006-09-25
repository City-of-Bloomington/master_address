<?php
/*
	$this->districtTypeList
*/
?>
<div class="interfaceBox">
	<div class="titleBar">DistrictTypes</div>
	<table>
	<?php
		foreach($this->districtTypeList as $districtType)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/districtTypes/updateDistrictType.php?id={$districtType->getId()}'\">Edit</button></td>"; }
			echo "<td>{$districtType->getType()}</td></tr>";
		}
	?>
	</table>
</div>