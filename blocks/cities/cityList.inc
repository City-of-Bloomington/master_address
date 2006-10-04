<?php
/*
	$this->cityList
*/
?>
<div class="interfaceBox">
	<div class="titleBar">Cities</div>
	<table>
	<?php
		foreach($this->cityList as $city)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/cities/updateCity.php?id={$city->getId()}'\">Edit</button></td>"; }
			echo "<td>{$city->getName()}</td></tr>";
		}
	?>
	</table>
</div>