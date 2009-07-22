<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Subunit</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Info</legend>
		<input name="subunit_id" type="hidden" value="<?php echo $this->subunit->getSubunit_id(); ?>" />
		<table>

			<tr><td><label>Address </label></td>
                <td><?php echo $this->subunit->getStreet_address_id(); ?>
				<input type="hidden" name="subunit[street_address_id]" id="subunit-street_address_id" value="<?php echo $this->subunit->getStreet_address_id(); ?>" />
				</td>
			</tr>

			<tr><td><label for="subunit-sudtype">Type</label></td>
				<td><select name="subunit[sudtype]" id="subunit-sudtype">
						<option></option>
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

			<tr><td><label for="subunit-street_subunit_identifier">Number</label></td>
				<td><input name="subunit[street_subunit_identifier]" id="subunit-street_subunit_identifier" value="<?php echo $this->subunit->getStreet_subunit_identifier(); ?>" />
				</td>
			</tr>

			<tr><td><label for="subunit-notes">Notes</label></td>
				<td><input name="subunit[notes]" id="subunit-notes" value="<?php echo View::escape($this->subunit->getNotes()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>