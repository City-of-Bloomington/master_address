<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>DistrictType</legend>
	<input name="id" type="hidden" value="<?php echo $this->districtType->getId(); ?>" />

	<table>
	<tr><td><label for="districtType-type">Type</label></td>
		<td><input name="districtType[type]" id="districtType-type" value="<?php echo $this->districtType->getType(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/districtTypes';">Cancel</button>
</fieldset>
</form>