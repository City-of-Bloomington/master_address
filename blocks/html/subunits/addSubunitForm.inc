<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Add Subunit</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
	onsubmit="getChangeLog(this); return false;">
	<fieldset><legend>Subunit Information</legend>
		<input name="street_address_id" type="hidden" value="<?php echo $this->address->getId() ?>" />
		<table>
			<tr><td><label for="sudtype" class="required">Type</label></td>
				<td><select name="sudtype" id="sudtype">
						<option></option>
						<?php
							$types = new SubunitTypeList();
							$types->find();
								foreach ($types as $type) {
									echo "<option value=\"{$type->getSudtype()}\">{$type->getDescription()}</option>";
								}
						?>
                   </select>
				</td>
			</tr>

			<tr><td><label for="street_subunit_identifier" class="required">Unit</label></td>
				<td><input name="street_subunit_identifier" id="street_subunit_identifier" />
					<div>Comma separated for multiple subunts (Ex. A, B, C)</div>
				</td>
			</tr>

			<tr><td><label for="notes">Notes</label></td>
				<td><input name="notes" id="notes" />
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<?php
			$this->locationData = $this->address->getLocation()->getUpdatableData($this->address);
			include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
		?>
	</fieldset>
	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',array('action'=>'assign'));
		echo $block->render('html');
	?>
</form>