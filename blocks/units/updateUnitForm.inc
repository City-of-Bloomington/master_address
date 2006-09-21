<?php
/*
	$this->unit
*/
?>
<h2>Edit Unit</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Unit Info</legend>
	<input name="unit_id" type="hidden" value="<?php echo $this->unit->getId(); ?>" />
	<table>
	<tr><td><label for="unit-unitType_id">Type</label></td>
		<td><select name="unit[unitType_id]" id="unit-unitType_id">
			<?php
				$list = new UnitTypeList();
				$list->find();
				foreach($list as $type)
				{
					if ($this->unit->getType()->getId() == $type->getId())
						{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
					else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="unit-identifier">Identifier</label></td>
		<td><input name="unit[identifier]" id="unit-indentifier" value="<?php echo $this->unit->getIdentifier(); ?>" /></td></tr>
	<tr><td><label for="unit-mailable-yes">Mailable</label></td>
		<td><label><input name="unit[mailable]" id="unit-mailable-yes" type="radio" value="1" <?php if($this->unit->getMailable()==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input name="unit[mailable]" id="unit-mailable-no" type="radio" value="0" <?php if($this->unit->getMailable()==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="unit-livable-yes">Livable</label></td>
		<td><label><input name="unit[livable]" id="unit-livable-yes" type="radio" value="1" <?php if($this->unit->getLivable()==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input name="unit[livable]" id="unit-livable-no" type="radio" value="0" <?php if($this->unit->getLivable()==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="unit-unitStatus_id">Status</label></td>
		<td><select name="unit[unitStatus_id]" id="unit-unitStatus_id">
			<?php
				$list = new StatusList("unit");
				$list->find();
				foreach($list as $status)
				{
					if ($this->unit->getStatus()->getId()==$status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="unit-notes">Notes</label></div>
			<div><textarea name="unit[notes]" id="unit-notes" rows="3" cols="60"><?php echo $this->unit->getNotes(); ?></textarea></div>
	</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>