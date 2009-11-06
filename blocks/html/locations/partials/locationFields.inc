<?php
/**
 * Draws the form fields for Locations
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->locationData (optional)
 */
if (isset($this->location)) {
	echo "
	<tr><td colspan=\"2\">
			<input type=\"hidden\" name=\"location_id\" value=\"{$this->location->getId()}\" />
		</td>
	</tr>
	";
	$this->locationData = $this->location->getUpdatableData($this->address);
}
$mailable = isset($this->locationData['mailable']) ? $this->locationData['mailable'] : 'unknown';
$livable = isset($this->locationData['livable']) ? $this->locationData['livable'] : 'unknown';
$locationType = isset($this->locationData['locationType'])
				? $this->locationData['locationType']
				: new LocationType("RESIDENTIAL SFR");
?>
	<tr><td><label for="location_type_id" class="required">Location Use Type</label></td>
		<td><select name="location_type_id">
				<?php
					$types = new LocationTypeList();
					$types->find();
					foreach ($types as $type) {
						$selected = ($locationType->getId() == $type->getId())
									? $selected = 'selected="selected"'
									: '';
						echo "<option $selected >{$type->getId()}</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="mailable" class="required">Mailable</label></td>
		<td><?php
				foreach (Location::getFlagValues() as $value) {
					$checked = $mailable==$value ? 'checked="checked"' : '';
					echo "
					<label style=\"float:none;\">
						<input name=\"mailable\" id=\"mailable_$value\" type=\"radio\" value=\"$value\" $checked />
						$value
					</label>
					";
				}
			?>
		</td>
	</tr>

	<tr><td><label for="livable" class="required">Livable</label></td>
		<td><?php
				foreach (Location::getFlagValues() as $value) {
					$checked = $livable==$value ? 'checked="checked"' : '';
					echo "
					<label style=\"float:none;\">
						<input name=\"livable\" id=\"livable_$value\" type=\"radio\" value=\"$value\" $checked />
						$value
					</label>
					";
				}
			?>
		</td>
	</tr>
