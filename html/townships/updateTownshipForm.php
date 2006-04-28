<?php
/*
	$_GET variables:	townshipID
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/Township.inc");
	$township = new Township($_GET['townshipID']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateTownship.php">
	<fieldset><legend>Township</legend>
		<input name="townshipID" type="hidden" value="<?php echo $_GET['townshipID']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $township->getName(); ?>" /></td></tr>
		<tr><td><label for="abbreviation">Abbreviation</label></td>
			<td><input name="abbreviation" id="abbreviation" value="<?php echo $township->getAbbreviation(); ?>" /></td></tr>
		<tr><td><label for="quarterCode">Quarter Code</label></td>
			<td><input name="quarterCode" id="quarterCode" value="<?php echo $township->getQuarterCode(); ?>" /></td></tr>
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