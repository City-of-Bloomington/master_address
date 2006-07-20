<?php
	$addSegmentButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/streets/addSegment.php?street_id={$this->street->getId()}');\">Add</button>" : "";
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
				<td>{$segment->getStartingNumber()} - {$segment->getEndingNumber()}</td>
				<td><table>
					<tr><th>{$addPlaceButton} Place ID</th>
						<th colspan=\"2\">This Street</th>
						<th colspan=\"2\">Other</th>
					</tr>
			";
					foreach($segment->getPlaces() as $place)
					{
						echo "
						<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?id={$place->getId()}\">{$place->getId()}</a></td>
							<td>
						";
								if (userHasRole("Administrator")) { echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/addresses/add/addAddress.php?street_id={$this->street->getId()};segment_id={$segment->getId()};place_id={$place->getId()}');\">Add</button>"; }
						echo "
							</td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address)
								{
									if ($address->getStreet_id() == $this->street->getId())
									{
										echo "<li><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getFullAddress()}</a></li>";
									}
								}
						echo "
								</ul>
							</td>
							<td>
						";
								if (userHasRole("Administrator")) { echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/addresses/add/addAddress.php?place_id={$place->getId()}');\">Add</button>"; }
						echo "
							</td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address)
								{
									if ($address->getStreet_id() != $this->street->getId())
									{
										echo "<li><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getFullAddress()}</a></li>";
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