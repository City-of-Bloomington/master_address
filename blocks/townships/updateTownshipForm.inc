<?php
/*
	$this->township
*/
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Township</legend>
	<input name="id" type="hidden" value="<?php echo $this->township->getId(); ?>" />

	<table>
	<tr><td><label for="township-name">Name</label></td>
		<td><input name="township[name]" id="township-name" value="<?php echo $this->township->getName(); ?>" /></td></tr>
	<tr><td><label for="township-abbreviation">Abbreviation</label></td>
		<td><input name="township[abbreviation]" id="township-abbreviation" size="2" maxlength="2" value="<?php echo $this->township->getAbbreviation(); ?>" /></td></tr>
	<tr><td><label for="township-quarterCode">Quarter Code</label></td>
		<td><input name="township[quarterCode]" id="township-quarterCode" size="1" maxlength="1" value="<?php echo $this->township->getQuarterCode(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/townships';">Cancel</button>
</fieldset>
</form>