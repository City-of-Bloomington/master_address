<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<table>
	<tr><td><label for="sudtype" class="required">Type</label></td>
		<td><select name="sudtype" id="sudtype">
			<?php
				$types = new SubunitTypeList();
				$types->find();
				foreach ($types as $type) {
					$selected = $type->getSudtype() == $this->subunit->getSudtype()
											? 'selected="selected"'
											: '';
					echo "<option $selected value=\"{$type->getSudtype()}\">{$type->getDescription()}</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="street_subunit_identifier" class="required">Unit</label></td>
		<td><input name="street_subunit_identifier" id="street_subunit_identifier"
					size="10" maxlength="20"
					value="<?php echo View::escape($this->subunit->getIdentifier()); ?>" />
		</td>
	</tr>
	<tr><td><label for="notes">Notes</label></td>
		<td><input name="notes" id="notes"
					value="<?php echo View::escape($this->subunit->getNotes()); ?>" />
		</td>
	</tr>
</table>
