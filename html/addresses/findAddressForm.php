<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Find an Address</h1>
	<form method="get" action="findAddressResults.php">
	<?php include(APPLICATION_HOME."/includes/addresses/findAddressFormFields.inc"); ?>

	<fieldset>
		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>