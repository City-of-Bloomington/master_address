<?php
/**
 * Draws the form fields for Locations
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->locationData (optional)
 */
$mailable = isset($this->locationData['mailable']) ? $this->locationData['mailable'] : 'unknown';
$livable = isset($this->locationData['livable']) ? $this->locationData['livable'] : 'unknown';
$locationType = isset($this->locationData['locationType'])
				? $this->locationData['locationType']
				: new LocationType("RESIDENTIAL SFR");
?>
	<tr><td><label for="location_type_id">Location Use Type</label></td>
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

	<tr><td><label for="mailable">Mailable</label></td>
		<td style="float:left;"><?php
				foreach (Location::getFlagValues() as $value) {
					$checked = $mailable==$value ? 'checked="checked"' : '';
					echo "
					<label>
						<input name=\"mailable\" id=\"mailable_$value\" type=\"radio\" value=\"$value\" $checked />
						$value
					</label>
					";
				}
			?>
		</td>
	</tr>

	<tr><td><label for="livable">Livable</label></td>
		<td style="float:left;"><?php
				foreach (Location::getFlagValues() as $value) {
					$checked = $livable==$value ? 'checked="checked"' : '';
					echo "
					<label>
						<input name=\"livable\" id=\"livable_$value\" type=\"radio\" value=\"$value\" $checked />
						$value
					</label>
					";
				}
			?>
		</td>
	</tr>

	
