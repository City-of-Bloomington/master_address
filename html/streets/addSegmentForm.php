<?php
/*
	This is designed to run as a pop up.  When done, it needs to
	refresh the parent window

	$_GET variables:	street_id
*/
	verifyUser("Administrator");
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");
	include(GLOBAL_INCLUDES."/errorMessages.inc");
?>
	<h1>Add Segments</h1>
	<?php include(APPLICATION_HOME."/includes/segments/findSegmentForm.inc"); ?>

<?php include(GLOBAL_INCLUDES."/xhtmlFooter.inc"); ?>