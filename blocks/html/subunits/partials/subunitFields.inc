<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<table>
	<tr><td><label for="subunit-sudtype" class="required">Type</label></td>
		<td><select name="subunit[sudtype]" id="subunit-sudtype">
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
	<tr><td><label for="subunit-identifier">#</label></td>
		<td><input name="subunit[street_subunit_identifier]" id="subunit-identifier"
					size="10" maxlength="20"
					value="<?php echo View::escape($this->subunit->getIdentifier()); ?>" />
		</td>
	</tr>
	<tr><td><label for="subunit-notes">Notes</label></td>
		<td><input name="subunit[notes]" id="subunit-notes"
					value="<?php echo View::escape($this->subunit->getNotes()); ?>" />
		</td>
	</tr>
</table>
