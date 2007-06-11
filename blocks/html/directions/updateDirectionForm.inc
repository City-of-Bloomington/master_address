<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Direction</legend>
	<input name="id" type="hidden" value="<?php echo $this->direction->getId(); ?>" />

	<table>
	<tr><td><label for="direction-code">Code</label></td>
		<td><input name="direction[code]" id="direction-code" size="1" maxlength="1" value="<?php echo $this->direction->getCode(); ?>" /></td></tr>
	<tr><td><label for="direction-direction">Direction</label></td>
		<td><input name="direction[direction]" id="direction-direction" value="<?php echo $this->direction->getDirection(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/directions';">Cancel</button>
</fieldset>
</form>
