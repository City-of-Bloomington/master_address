<?php
/*
	$this->placeList
*/
?>
<div class="interfaceBox">
	<div class="titleBar">Places</div>
	<table>
	<tr><th>ID</th><th>Name</th><th>Start Date</th><th>Addresses</th></tr>
	<?php
		foreach($this->placeList as $place)
		{
			echo "
			<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getId()}</a></td>
				<td><a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getName()}</a></td>
				<td><a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getStartDate()}</a></td>
				<td><ul class=\"compact\">
			";
			foreach($place->getAddressList() as $address) { echo "<li>{$address->getFullAddress()}</li>"; }
			echo "
				</ul></td>
			</tr>
			";
		}
	?>
	</table>
</div>