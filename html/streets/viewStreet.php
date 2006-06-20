<?php
/*
	$_GET variables:	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$street = new Street($_GET['id']);
?>
<div id="mainContent">
	<h1><?php echo "Street:{$street->getId()}"; ?></h1>
	<table>
		<tr><th>Status</th><td><?php echo $street->getStatus(); ?></td></tr>
	</table>
	<p class="comments"><?php echo $street->getNotes(); ?></p>


	<div class="interfaceBox"><div class="titleBar">Street Names</div>
	<table>
	<tr><th></th><th>Name</th><th>Type</th>
	<?php
		foreach($street->getStreetNames() as $streetName)
		{
			if (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole("Administrator"))
			{ echo "<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/streetNames/updateStreetName.php?id={$streetName->getId()}';\">Edit</button></td>"; }
			else { echo "<tr><td></td>"; }
			echo "
				<td><a href=\"".BASE_URL."/names/viewName.php?id={$streetName->getName()->getId()}\">{$streetName->getFullname()}</a></td>
				<td>{$streetName->getType()}</td></tr>
			";
		}
	?>
	</table>
	</div>

	<div class="interfaceBox">
		<div class="titleBar">
			<?php
				if (isset($_SESSION['USER']) && $_SESSION['USER']->hasRole("Administrator"))
				{ echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('addSegmentForm.php?street_id=$_GET[id]');\">Add</button>"; }
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
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>