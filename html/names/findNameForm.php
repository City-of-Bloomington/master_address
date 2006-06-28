<?php
/*
	$_GET variables:	name[	direction_id		town_id
								name
								suffix_id
								postDirection_id
							]
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");

		$error_url = getCurrentURL();
		$return_url = "viewName.php?id=";
		include(APPLICATION_HOME."/includes/names/findNameForm.inc");


		if (userHasRole("Administrator")) { include(APPLICATION_HOME."/includes/names/addNameForm.inc"); }
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>