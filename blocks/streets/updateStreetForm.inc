<?php
/*
	$this->street
	$this->response
*/
?>
<h2>Edit Street: <?php echo $this->street->getId(); ?></h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Info</legend>
	<input name="street_id" type="hidden" value="<?php echo $this->street->getId(); ?>" />
	<input name="response" type="hidden" value="<?php echo $this->response->getURL(); ?>" />
	<table>
	<tr><td><label for="street-streetStatus_id">Status</label></td>
		<td><select name="street[streetStatus_id]" id="street-streetStatus">
			<?php
				$list = new StatusList("street");
				$list->find();
				foreach($list as $status)
				{
					if ($this->street->getStatus_id() != $status->getId()) { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="street-notes">Notes</label></div>
			<textarea name="street[notes]" id="street-notes" rows="3" cols="60"><?php echo $this->street->getNotes(); ?></textarea>
	</td></tr>
	</table>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo $this->response->getURL(); ?>';">Cancel</button>
</fieldset>
</form>