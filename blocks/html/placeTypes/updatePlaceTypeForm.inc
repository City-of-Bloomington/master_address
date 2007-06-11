<?php
/*
	$this->placeType
*/
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>PlaceType</legend>
	<input name="id" type="hidden" value="<?php echo $this->placeType->getId(); ?>" />

	<table>
	<tr><td><label for="placeType-type">Type</label></td>
		<td><input name="placeType[type]" id="placeType-type" value="<?php echo $this->placeType->getType(); ?>" /></td></tr>
	<tr><td colspan="2">
			<div><label for="placeType-description">Description</label></div>
			<textarea name="placeType[description]" id="placeType-description" rows="3" cols="60"><?php echo $this->placeType->getDescription(); ?></textarea>
	</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/placeTypes';">Cancel</button>
</fieldset>
</form>