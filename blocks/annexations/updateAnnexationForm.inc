<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Annexation</legend>
	<input name="id" type="hidden" value="<?php echo $this->annexation->getId(); ?>" />

	<table>
	<tr><td><label for="annexation-ordinanceNumber">Ordinance</label></td>
		<td><input name="annexation[ordinanceNumber]" id="annexation-ordinanceNumber" value="<?php echo $this->annexation->getOrdinanceNumber(); ?>" /></td></tr>
	<tr><td><label for="annexation-name">Name</label></td>
		<td><input name="annexation[name]" id="annexation-name" value="<?php echo $this->annexation->getName(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/annexations';">Cancel</button>
</fieldset>
</form>
