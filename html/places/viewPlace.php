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

	<div class="interfaceBox"><div class="titleBar">Addresses</div>
	<?php
		foreach($place->getAddresses() as $address)
		{
			include(APPLICATION_HOME."/includes/addresses/addressInfo.inc");
		}
	?>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>