<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Plat</legend>
	<input name="id" type="hidden" value="<?php echo $this->plat->getId(); ?>" />

	<table>
	<tr><td><label for="plat-name">Name</label></td>
		<td><input name="plat[name]" id="plat-name" value="<?php echo $this->plat->getName(); ?>" /></td></tr>
	<tr><td><label for="plat-township_id">Township</label></td>
		<td><select name="plat[township_id]" id="plat-township_id">
			<?php
				$townships = new TownshipList();
				$townships->find();
				foreach($townships as $township)
				{
					if ($this->plat->getTownship_id() != $township->getId()) { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
					else { echo "<option value=\"{$township->getId()}\" selected=\"selected\">{$township->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-platType_id">Plat Type</label></td>
		<td><select name="plat[platType_id]" id="plat-platType_id">
			<?php
				$platTypes = new PlatTypeList();
				$platTypes->find();
				foreach($platTypes as $platType)
				{
					if ($this->plat->getPlatType_id() != $platType->getId()) { echo "<option value=\"{$platType->getId()}\">{$platType->getType()}</option>"; }
					else { echo "<option value=\"{$platType->getId()}\" selected=\"selected\">{$platType->getType()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-cabinet">Cabinet</label></td>
		<td><input name="plat[cabinet]" id="plat-cabinet" value="<?php echo $this->plat->getCabinet(); ?>" /></td></tr>
	<tr><td><label for="plat-envelope">Envelope</label></td>
		<td><input name="plat[envelope]" id="plat-envelope" value="<?php echo $this->plat->getEnvelope(); ?>" /></td></tr>
	<tr><td colspan="2">
			<div><label for="plat-notes">Notes</label></div>
			<textarea name="plat[notes]" id="plat-notes" rows="3" cols="60"><?php echo $this->plat->getNotes(); ?></textarea>
	</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/platTypes';">Cancel</button>
</fieldset>
</form>