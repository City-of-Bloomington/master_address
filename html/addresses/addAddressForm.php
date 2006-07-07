<?php
/*
	$_GET variables:	street_id
						segment_id
						place_id

						return_url
*/
	verifyUser(array("Administrator","ADDRESS COORDINATOR"));

	# Check for required parameters.  This form willl not work without them
	if (!$_GET['street_id'] || !$_GET['place_id'])
	{
		$_SESSION['errorMessages'][] = new Exception("missingRequiredFields");
		Header("Location: $_GET[return_url]");
		exit();
	}

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$segment = new Segment($_GET['segment_id']);
?>
<div id="mainContent">
	<h1>Add Address</h1>
	<h2><?php echo "$_GET[number] $_GET[suffix] {$segment->getFullStreetName()}"; ?></h2>
	<form method="post" action="addAddress.php">

	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>