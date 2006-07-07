<?php
/*
	Required variables:		street
*/
	$addSegmentButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/streets/addSegmentForm.php?street_id={$street->getId()}');\">Add</button>" : "";
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addSegmentButton; ?>Segments</div>
	<table>
	<tr><th>Tag</th><th>Range</th><th>Places</th></tr>
	<?php
		foreach($street->getSegments() as $segment)
		{
			$addPlaceButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('".BASE_URL."/streets/addPlaceForm.php?street_id={$street->getId()};segment_id={$segment->getId()}');\">Add</button>" : "";
			echo "
			<tr><td>{$segment->getTag()}</td>
				<td>{$segment->getStartingNumber()} - {$segment->getEndingNumber()}</td>
				<td><table>
					<tr><th>$addPlaceButton</th>
						<th>This Street</th>
						<th>Other</th>
						<th>Add Address</th>
					</tr>
			";
					foreach($segment->getPlaces() as $place)
					{
						echo "
						<tr><td><a href=\"".BASE_URL."/places/viewPlace.php?id={$place->getId()}\">{$place->getId()}</a></td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address) { if ($address->getStreet_id() == $_GET['id']) echo "<li>{$address->getFullAddress()}</li>"; }
						echo "
								</ul>
							</td>
							<td><ul class=\"compact\">
						";
								foreach($place->getAddresses() as $address) { if ($address->getStreet_id() != $_GET['id']) echo "<li>{$address->getFullAddress()}</li>"; }
						echo "
								</ul>
							</td>
							<td><button type=\"button\" class=\"addSmall\">Add</button>
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