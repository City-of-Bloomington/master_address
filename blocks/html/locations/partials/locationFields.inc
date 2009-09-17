<?php
/**
 * Draws the form fields for Locations
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->locationData (optional)
 */
$mailable = isset($this->locationData['mailable']) ? $this->locationData['mailable'] : false;
$livable = isset($this->locationData['livable']) ? $this->locationData['livable'] : false;
$locationType = isset($this->locationData['locationType'])
				? $this->locationData['locationType']
				: new LocationType();
?>
<table>
	<tr><td><label for="mailable">Mailable</label></td>
		<td><?php
				$checked = $mailable ? 'checked="checked"' : '';
				echo "<input name=\"mailable\" id=\"mailable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="livable">Livable</label></td>
		<td><?php
				$checked = $livable ? 'checked="checked"' : '';
				echo "<input name=\"livable\" id=\"livable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="location_type_id">Location Type</label></td>
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
</table>
