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
	<h1><?php echo $street; ?></h1>
	<div class="interfaceBox"><div class="titleBar">Street Names</div>
	<table>
	<tr><th>Name</th><th>Type</th>
	<?php
		foreach($street->getStreetNames() as $streetName)
		{
			echo "
			<tr><td>{$streetName->getFullname()}</td>
				<td>{$streetName->getType()}</td></tr>
			";
		}
	?>
	</table>
	</div>

	<div class="interfaceBox">
		<div class="titleBar">
			<?php if (isset($_SESSION['USER'])) { echo "<button type=\"button\" class=\"addSmall\" onclick=\"window.open('addSegmentForm.php?street_id=$_GET[id]');\">Add</button>"; } ?>
			Segments
		</div>
	<table>
	<tr><th>Tag</th><th>Starting Number</th><th>Ending Number</th><th>Addresses</th></tr>
	<?php
		foreach($street->getSegments() as $segment)
		{
			echo "
			<tr><td>{$segment->getTag()}</td>
				<td>{$segment->getStartingNumber()}</td>
				<td>{$segment->getEndingNumber()}</td>
				<td><ul>
			";
					foreach($segment->getAddresses() as $address)
					{
						echo "<li>{$address->getFullAddress()}</li>";
					}
			echo "
					</ul>
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