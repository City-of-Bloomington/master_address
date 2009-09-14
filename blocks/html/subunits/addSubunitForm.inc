<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Add Subunit</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Information</legend>
		<input name="street_address_id" type="hidden" value="<?php echo $this->address->getId() ?>" />
		<table>
			<tr><td><label for="subunit-sudtype">Type</label></td>
				<td><select name="subunit[sudtype]" id="subunit-sudtype">
						<option></option>
						<?php
							$types = new SubunitTypeList();
							$types->find();
								foreach($types as $type) {
									echo "<option value=\"{$type->getSudtype()}\">{$type->getDescription()}</option>";
								}
						?>
                   </select>
				</td>
			</tr>

			<tr><td><label for="subunit-street_subunit_identifier">Unit</label></td>
				<td><input name="subunit[street_subunit_identifier]" id="subunit-street_subunit_identifier" />
					<div>Comma separated for multiple subunts (Ex. A, B, C)</div>
				</td>
			</tr>

			<tr><td><label for="subunit-notes">notes</label></td>
				<td><input name="subunit[notes]" id="subunit-notes" />
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<?php include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc'; ?>
	</fieldset>

	<fieldset>
		<button type="submit" class="submit">Submit</button>
        <input type="checkbox" name="batch_mode" value="y" />Submit in Batch Mode
	</fieldset>
</form>