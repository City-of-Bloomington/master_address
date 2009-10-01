<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit (optional)
 */
$subunit = $this->subunit ? $this->subunit : new Subunit();
?>
<table>
	<tr><td><label for="sudtype" class="required">SUD Type</label></td>
		<td><select name="sudtype" id="sudtype">
			<?php
				$types = new SubunitTypeList();
				$types->find();
				foreach ($types as $type) {
					$selected = $type->getSudtype() == $subunit->getSudtype()
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
					value="<?php echo View::escape($subunit->getIdentifier()); ?>" />
		</td>
	</tr>
</table>
<div>
	<div><label for="notes">Notes</label></div>
	<textarea name="notes" id="notes" rows="3" cols="50"><?php echo View::escape($subunit->getNotes()); ?></textarea>
</div>