<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$direction = new Direction($_GET['directionCode']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateDirection.php">
	<fieldset><legend>Direction</legend>
		<input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />

		<table>
		<tr><td><label for="code">Code</label></td>
			<td><input name="code" id="code" size="1" maxlength="1" value="<?php echo $direction->getCode(); ?>" /></td></tr>
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