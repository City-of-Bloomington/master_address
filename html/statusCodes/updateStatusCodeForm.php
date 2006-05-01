<?php
/*
	$_GET variables:	statusCode
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/StatusCode.inc");
	$statusCode = new StatusCode($_GET['statusCode']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateStatusCode.php">
	<fieldset><legend>StatusCode</legend>
		<input name="statusCode" type="hidden" value="<?php echo $_GET['statusCode']; ?>" />

		<table>
		<tr><td><label for="status">Status</label></td>
			<td><input name="status" id="status" value="<?php echo $statusCode->getStatus(); ?>" /></td></tr>
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