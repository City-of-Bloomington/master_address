<?php
/*
	$this->unitType
*/
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>UnitType</legend>
	<input name="id" type="hidden" value="<?php echo $this->unitType->getId(); ?>" />

	<table>
	<tr><td><label for="unitType-type">Type</label></td>
		<td><input name="unitType[type]" id="unitType-type" value="<?php echo $this->unitType->getType(); ?>" /></td></tr>
	<tr><td><label for="unitType-description">Description</label></td>
		<td><input name="unitType[description]" id="unitType-description" value="<?php echo $this->unitType->getDescription(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/unitTypes';">Cancel</button>
</fieldset>
</form>