<?php
/*
	$_GET variables:	type
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/PlatType.inc");
	$platType = new PlatType($_GET['type']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updatePlatType.php">
	<fieldset><legend>PlatType</legend>
		<input name="type" type="hidden" value="<?php echo $_GET['type']; ?>" />

		<table>
		<tr><td><label for="description">Description</label></td>
			<td><input name="description" id="description" value="<?php echo $platType->getDescription(); ?>" /></td></tr>
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