<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Suffix</legend>
	<input name="id" type="hidden" value="<?php echo $this->suffix->getId(); ?>" />

	<table>
	<tr><td><label for="suffix-suffix">Suffix</label></td>
		<td><label for="suffix-description">Description</label></td></tr>
	<tr><td><input name="suffix[suffix]" id="suffix-suffix" size="4" maxlength="4" value="<?php echo $this->suffix->getSuffix(); ?>" /></td>
		<td><input name="suffix[description]" id="suffix-description" value="<?php echo $this->suffix->getDescription(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/suffixes';">Cancel</button>
</fieldset>
</form>
