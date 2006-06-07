<?php
/*
	Meant to be used in a pop-up form.
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");
?>
<body>
	<form method="get" action="chooseAddressFormResults.php">
		<?php include(APPLICATION_HOME."/includes/addresses/findAddressFormFields.inc"); ?>

		<fieldset><legend>Find</legend>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>
</body>
<?php
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>