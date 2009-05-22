<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Plat $this->plat
 */
?>
<h1>Update Plat</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Plat Info</legend>
		<input name="plat_id" type="hidden" value="<?php echo $this->plat->getPlat_id(); ?>" />
		<table>

			<tr><td><label for="plat-name" class="required">Name</label></td>
				<td><input name="plat[name]" id="plat-name" value="<?php echo View::escape($this->plat->getName()); ?>" />
				</td>
			</tr>

			<tr><td><label for="plat-township_id" class="required">Township</label></td>
				<td><select name="plat[township_id]" id="plat-township_id">
					<?php
						$list = new TownshipList();
						$list->find();
						foreach ($list as $township) {
							$name = View::escape($township->getName());
							$selected = ($this->plat->getTownship_id()==$township->getId()) ? 'selected="selected"' : '';
							echo "<option value=\"{$township->getId()}\" $selected>$name</option>";
						}
					?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-effective_start_date">Effective Start Date</label></td>
				<td><input name="plat[effective_start_date]"
							id="plat-effective_start_date"
							size="11" maxlength="10"
							value="<?php echo $this->plat->getEffective_start_date('n/j/Y'); ?>" />
				</td>
			</tr>
			<tr><td><label for="plat-effective_end_date">Effective End Date</label></td>
				<td><input name="plat[effective_end_date]"
							id="plat-effective_end_date"
							size="11" maxlength="10"
							value="<?php echo $this->plat->getEffective_end_date('n/j/Y'); ?>" />
				</td>
			</tr>
			<tr><td><label for="plat-plat_type" class="required">Type</label></td>
				<td><select name="plat[plat_type]" id="plat-plat_type">
					<?php
						foreach (Plat::getPlat_types() as $type) {
							$selected = $this->plat->getPlat_type()==$type ? 'selected="selected"' : '';
							echo "<option $selected>$type</option>";
						}
					?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-plat_cabinet">Cabinet</label></td>
				<td><input name="plat[plat_cabinet]" id="plat-plat_cabinet" value="<?php echo View::escape($this->plat->getPlat_cabinet()); ?>" />
				</td>
			</tr>

			<tr><td><label for="plat-envelope">Envelope</label></td>
				<td><input name="plat[envelope]" id="plat-envelope" value="<?php echo View::escape($this->plat->getEnvelope()); ?>" />
				</td>
			</tr>

			<tr><td><label for="plat-notes">Notes</label></td>
				<td><textarea name="plat[notes]" id="plat-notes" rows="3" cols="50"><?php echo $this->plat->getNotes(); ?></textarea>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/plats';">
			Cancel
		</button>
	</fieldset>
</form>
