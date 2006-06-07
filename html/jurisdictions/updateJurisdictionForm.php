<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$jurisdiction = new Jurisdiction($_GET['id']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateJurisdiction.php">
	<fieldset><legend>Jurisdiction</legend>
		<input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $jurisdiction->getName(); ?>" /></td></tr>
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