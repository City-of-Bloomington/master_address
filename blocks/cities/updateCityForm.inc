<?php
/*
	$this->city
*/
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>City</legend>
	<input name="id" type="hidden" value="<?php echo $this->city->getId(); ?>" />

	<table>
	<tr><td><label for="city-name">Name</label></td>
		<td><input name="city[name]" id="city-name" value="<?php echo $this->city->getName(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/cities';">Cancel</button>
</fieldset>
</form>