<?php
/*
	$this->platType
*/
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>PlatType</legend>
	<input name="id" type="hidden" value="<?php echo $this->platType->getId(); ?>" />

	<table>
	<tr><td><label for="platType-type">Type</label></td>
		<td><label for="platType-description">Description</label></td></tr>
	<tr><td><input name="platType[type]" id="platType-type" size="1" maxlength="1" value="<?php echo $this->platType->getType(); ?>" /></td>
		<td><input name="platType[description]" id="platType-description" value="<?php echo $this->platType->getDescription(); ?>" /></td>
	</tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/platTypes';">Cancel</button>
</fieldset>
</form>