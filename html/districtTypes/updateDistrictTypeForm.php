<?php
/*
	$_GET variables:	districtTypeID
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/DistrictType.inc");
	$districtType = new DistrictType($_GET['districtTypeID']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateDistrictType.php">
	<fieldset><legend>DistrictType</legend>
		<input name="districtTypeID" type="hidden" value="<?php echo $_GET['districtTypeID']; ?>" />

		<table>
		<tr><td><label for="type">Type</label></td>
			<td><input name="type" id="type" value="<?php echo $districtType->getType(); ?>" /></td></tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>