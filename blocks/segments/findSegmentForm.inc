<h2>Find a Street Segment</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Address Info</legend>
	<table>
	<tr><td><label for="address-number">Number</label></td>
		<td><input name="address[number]" id="address-number" size="5" /></td></tr>
	</table>
</fieldset>
<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
<fieldset><legend>Search</legend>
	<button type="submit" class="search">Search</button>
</fieldset>
</form>