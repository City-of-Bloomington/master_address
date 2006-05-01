<?php
/*
	$_GET variables:	directionCode
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/Direction.inc");
	$direction = new Direction($_GET['directionCode']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateDirection.php">
	<fieldset><legend>Direction</legend>
		<input name="directionCode" type="hidden" value="<?php echo $_GET['directionCode']; ?>" />

		<table>
		<tr><td><label for="direction">Direction</label></td>
			<td><input name="direction" id="direction" value="<?php echo $direction->getDirection(); ?>" /></td></tr>
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