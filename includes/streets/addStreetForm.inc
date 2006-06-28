<?php
/*
	Required Variables:		return_url
*/

?>
<h2>Create a new Street</h2>
<form method="post" action="<?php echo BASE_URL; ?>/streets/addStreet.php">
<fieldset><legend>Street Info</legend>
	<label>Status
		<select name="street[status_id]">
		<?php
			$statusList = new StatusList($PDO);
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