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
		<tr><td><label for="trash_pickup_day">Trash Schedule</label></td>
		<td><select name="trash_pickup_day" id="trash_pickup_day">
				<option></option>
				<?php
					foreach (Address::getTrashDays() as $day) {
						$selected = $this->address->getTrash_pickup_day()==$day
							? 'selected="selected"'
							: '';
						echo "<option $selected>$day</option>";
					}
				?>
			</select>
		</td>
	</tr>
	<tr><td><label for="recycle_week">Recycle Week</label></td>
		<td><select name="recycle_week" id="recycle_week">
				<option></option>
				<?php
					foreach (Address::getRecycleWeeks() as $week) {
						$selected = $this->address->getRecycle_week() == $week
							? 'selected="selected"'
							: '';
						echo "<option $selected>$week</option>";
					}
				?>
			</select>
		</td>
	</tr>

	
