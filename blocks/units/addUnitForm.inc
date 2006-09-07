<h2>Add a new Unit</h2>
<p>Use this form to add multiple units, one at a time.  Close this window when you are done.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Unit Info</legend>
	<table>
	<tr><td><label for="unit-unitType_id">Type</label></td>
		<td><select name="unit[unitType_id]" id="unit-unitType_id">
			<?php
				$list = new UnitTypeList();
				$list->find();
				foreach($list as $type)
				{
					if (isset($_POST['unit']['unitType_id']) && $_POST['unit']['unitType_id']==$type->getId())
						{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
					else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="unit-identifier">Identifier</label></td>
		<td><input name="unit[identifier]" id="unit-indentifier" value="<?php if(isset($_POST['unit']['identifier'])) echo $_POST['unit']['identifier']; ?>" /></td></tr>
	<tr><td><label for="unit-mailable-yes">Mailable</label></td>
		<td><label><input name="unit[mailable]" id="unit-mailable-yes" type="radio" value="1" <?php if(isset($_POST['unit']['mailable']) && $_POST['unit']['mailable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input name="unit[mailable]" id="unit-mailable-no" type="radio" value="0" <?php if(isset($_POST['unit']['mailable']) && $_POST['unit']['mailable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="unit-livable-yes">Livable</label></td>
		<td><label><input name="unit[livable]" id="unit-livable-yes" type="radio" value="1" <?php if(isset($_POST['unit']['livable']) && $_POST['unit']['livable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input name="unit[livable]" id="unit-livable-no" type="radio" value="0" <?php if(isset($_POST['unit']['livable']) && $_POST['unit']['livable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="unit-status_id">Status</label></td>
		<td><select name="unit[status_id]" id="unit-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if (isset($_POST['unit']['status_id']) && $_POST['unit']['status_id']==$status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="unit-notes">Notes</label></div>
			<div><textarea name="unit[notes]" id="unit-notes" rows="3" cols="60"><?php if(isset($_POST['unit']['notes'])) echo $_POST['unit']['notes']; ?></textarea></div>
	</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="done" onclick="self.close();">Done</button>
</fieldset>
</form>