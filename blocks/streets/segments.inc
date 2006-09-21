<?php
/*
	$this->street
*/
	$addSegmentButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/streets/addSegments.php?street_id={$this->street->getId()}');\">Add</button>" : "";
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addSegmentButton; ?>Segments</div>
	<table>
	<tr><th>Segment ID</th><th>Tag</th><th>Range</th><th>Places</th></tr>
	<?php
		foreach($this->street->getSegments() as $segment)
		{
			$addPlaceButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/streets/addPlace.php?street_id={$this->street->getId()};segment_id={$segment->getId()}');\">Add</button>" : "";
			echo "
			<tr><td>{$segment->getId()}</td>
				<td>{$segment->getTag()}</td>
				<td>{$segment->getLowAddressNumber()} - {$segment->getHighAddressNumber()}</td>
				<td><table>
					<tr><th>{$addPlaceButton} Place ID</th>
						<th colspan=\"2\">This Name</th>
						<th colspan=\"2\">Alias</th>
					</tr>
			";
					foreach($segment->getPlaces() as $place)
					{
						echo "
						<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?place_id={$place->getId()}\">{$place->getId()}</a></td>
							<td>
						";
								if (userHasRole("Administrator")) { echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/addresses/addAddress.php?street_id={$this->street->getId()};segment_id={$segment->getId()};place_id={$place->getId()}');\">Add</button>"; }
						echo "
							</td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address)
								{
									if ($address->getStreet_id() == $this->street->getId())
									{
										echo "<li><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getFullAddress()}</a></li>";
									}
								}
						echo "
								</ul>
							</td>
							<td></td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address)
								{
									if ($address->getStreet_id() != $this->street->getId())
									{
										echo "<li><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getFullAddress()}</a></li>";
									}
								}
						echo "
								</ul>
							</td>
						</tr>
						";
					}
			echo "
					</table>
				</td>
			</tr>
			";
		}
	?>
	</table>
</div>