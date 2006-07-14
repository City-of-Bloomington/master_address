<?php
/*
	$_GET variables:	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$place = new Place($_GET['id']);
?>
<div id="mainContent">
	<?php include(APPLICATION_HOME."/includes/places/placeInfo.inc"); ?>

	<div class="titleBar">Buildings</div>
	<table>
	<tr><th>Name</th><th>Tag</th><th>Start Date</th><th>End Date</th><th>Status</th></tr>
	<?php
		$buildings = new BuildingList(array("place_id"=>$place->getId()));
		foreach($buildings as $building)
		{
			echo "
			<tr><td>{$building->getName()}</td>
				<td>{$building->getGISTag()}</td>
				<td>{$building->getStartDate()}</td>
				<td>{$building->getEndDate()}</td>
				<td>{$building->getStatus()->getStatus()}</td>
			</tr>
			";
		}
	?>
	</table>

	<div class="titleBar">Addresses</div>
	<table>
	<tr><th>ID</th><th>Address</th><th>Status</th><th>Start Date</th><th>End Date</th></tr>
	<?php
		foreach($place->getAddresses() as $address)
		{
			echo "
			<tr><td><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getId()}</a></td>
				<td><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getFullAddress()}</a></td>
				<td><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getStatus()->getStatus()}</a></td>
				<td><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getStartDate()}</a></td>
				<td><a href=\"".BASE_URL."/addresses/viewAddress.php?id={$address->getId()}\">{$address->getEndDate()}</a></td>
			</tr>
			";
		}
	?>
	</table>

	<div class="titleBar">History</div>
	<table>
	<tr><th>Action</th><th>Date</th></tr>
	<?php
		foreach($place->getHistory() as $action)
		{
			echo "
			<tr><td>{$action->getAction()}</td>
				<td>{$action->getDate()}</td>
			</tr>
			";
		}
	?>
	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>