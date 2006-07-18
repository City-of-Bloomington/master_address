<form method="post" action="updateBuildingType.php">
<fieldset><legend>BuildingType</legend>
	<input name="id" type="hidden" value="<?php echo $this->buildingType->getId(); ?>" />

	<table>
	<tr><td><label for="buildingType-description">Description</label></td>
		<td><input name="buildingType[description]" id="buildingType-description" value="<?php echo $this->buildingType->getDescription(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/buildingTypes';">Cancel</button>
</fieldset>
</form>