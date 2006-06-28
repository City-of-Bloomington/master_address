<?php
/*
	$_GET variables;	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		$address = new Address($PDO,$_GET['id']);
		include(APPLICATION_HOME."/includes/addresses/addressInfo.inc");

		$place = $address->getPlace();
		include(APPLICATION_HOME."/includes/places/placeInfo.inc");
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>