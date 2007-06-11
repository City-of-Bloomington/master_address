<?php
/*
	$this->return_url
*/

?>
<h2>Create a new Street</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Info</legend>
	<input name="form" type="hidden" value="addStreetForm" />
	<input name="return_url" type="hidden" value="<?php echo $this->return_url; ?>" />
	<label>Status
		<select name="street[streetStatus_id]">
		<?php
			$statusList = new StatusList("street");
			$statusList->find();
			foreach($statusList as $status) { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
		?>
		</select>
	</label>
	<div><label for="street-notes">Notes</label></div>
	<div><textarea name="street[notes]" id="street-notes" rows="3" cols="60"></textarea></div>

	<button type="submit">Submit</button>
</fieldset>
</form>