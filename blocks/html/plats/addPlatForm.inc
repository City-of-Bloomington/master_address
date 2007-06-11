<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
?>
<h2>Add A Plat</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Plat Info</legend>
	<table>
	<tr><td><label for="plat-name">Name</label></td>
		<td><input name="plat[name]" id="plat-name" /></td></tr>
	<tr><td><label for="plat-township_id">Township</label></td>
		<td><select name="plat[township_id]" id="plat-township_id">
			<?php
				$townships = new TownshipList();
				$townships->find();
				foreach($townships as $township) { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-platType_id">Plat Type</label></td>
		<td><select name="plat[platType_id]" id="plat-platType_id">
			<?php
				$platTypes = new PlatTypeList();
				$platTypes->find();
				foreach($platTypes as $platType) { echo "<option value=\"{$platType->getId()}\">{$platType->getType()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-cabinet">Cabinet</label></td>
		<td><input name="plat[cabinet]" id="plat-cabinet" /></td></tr>
	<tr><td><label for="plat-envelope">Envelope</label></td>
		<td><input name="plat[envelope]" id="plat-envelope" /></td></tr>
	<tr><td colspan="2">
			<div><label for="plat-notes">Notes</label></div>
			<textarea name="plat[notes]" id="plat-notes" rows="3" cols="60"></textarea>
	</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/plats';">Cancel</button>
</fieldset>
</form>