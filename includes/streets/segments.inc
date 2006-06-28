<?php
/*
	Required variables:		street
							return_url
*/
?>
<div class="interfaceBox">
	<div class="titleBar">
		<?php
			if (userHasRole("Administrator"))
			{
				echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('addSegmentForm.php?street_id={$street->getId()};return_url=$return_url');\">Add</button>";
			}
		?>
		Segments
	</div>
	<table>
	<tr><th>Tag</th>
		<th>Range</th>
		<th><?php
				if (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole("Administrator"))
				{ echo "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='BASE_URL/places/addPlaceForm.php';\">Add</button>"; }
			?>
			Places
		</th>
	</tr>
	<?php
		foreach($street->getSegments() as $segment)
		{
			echo "
			<tr><td>{$segment->getTag()}</td>
				<td>{$segment->getStartingNumber()} - {$segment->getEndingNumber()}</td>
				<td><table><tr><th></th><th>This Street</th><th>Other</th><th>Add Address</th></tr>
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