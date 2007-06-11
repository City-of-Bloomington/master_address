<?php
/*
	$this->placeList
	$this->addPlaceURL
	$this->deletePlaceURL
*/
	if (userHasRole("Administrator"))
	{
		$addButton = "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('{$this->addPlaceURL->getURL()}');\">Add</button>";
	}
	else { $addButton = ""; }
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addButton; ?>Places</div>
	<table>
	<tr><th>ID</th><th>Name</th><th>Start Date</th><th>Addresses</th></tr>
	<?php
		foreach($this->placeList as $place)
		{
			if (userHasRole('Administrator'))
			{
				$this->deletePlaceURL->parameters['place_id'] = $place->getId();
				$deleteButton = "<button type=\"button\" class=\"deleteSmall\" onclick=\"deleteConfirmation('{$this->deletePlaceURL->getURL()}');\">Delete</button>";
			}
			else { $deleteButton = ""; }

			echo "
			<tr><td>$deleteButton
					<a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getId()}</a></td>
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