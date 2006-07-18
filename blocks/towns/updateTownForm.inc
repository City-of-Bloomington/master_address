<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Town</legend>
	<input name="id" type="hidden" value="<?php echo $this->town->getId(); ?>" />

	<table>
	<tr><td><label for="town-name">Name</label></td>
		<td><input name="town[name]" id="town-name" value="<?php echo $this->town->getName(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/towns';">Cancel</button>
</fieldset>
</form>