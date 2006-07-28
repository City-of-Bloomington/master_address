<h1>Find a Street</h1>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Info</legend>
	<table>
	<tr><td><label for="street-id">ID</label></td>
		<td><input name="street[id]" id="street-id" /></td></tr>
	</table>
</fieldset>
	<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="search">Search</button>
</fieldset>
</form>