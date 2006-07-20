<h2>Edit Street: <?php echo $this->street->getId(); ?></h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Info</legend>
	<input name="id" type="hidden" value="<?php echo $this->street->getId(); ?>" />
	<input name="return_url" type="hidden" value="<?php echo $this->return_url; ?>" />
	<table>
	<tr><td><label for="street-status_id">Status</label></td>
		<td><select name="street[status_id]" id="street-status">
			<?php
				$list = new StatusList();
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
	<button type="button" class="cancel" onclick="document.location.href='<?php echo $this->return_url; ?>';">Cancel</button>
</fieldset>
</form>