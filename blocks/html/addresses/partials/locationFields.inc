<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Location $this->location
 */
?>
<table>
	<tr><th>Location</th>
		<th><?php echo $this->location->getLocation_id(); ?></th>
	</tr>

	<tr><td><label for="mailable">Mailable</label></td>
		<td><?php
				$checked = $this->location->isMailable() ? 'checked="checked"' : '';
				echo "<input name=\"mailable\" value=\"y\" id=\"mailable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="livable">Livable</label></td>
		<td><?php
				$checked = $this->location->isLivable() ? 'checked="checked"' : '';
				echo "<input name=\"livable\" value=\"y\" id=\"livable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="location_type_id">Location Type</label></td>
		<td><select name="location_type_id">
				<?php
					$types = new LocationTypeList();
					$types->find();
					foreach ($types as $type) {
						$selected = $this->location->getLocation_type_id()==$type->getId()
									? $selected = 'selected="selected"'
									: '';
						echo "<option $selected >{$type->getId()}</option>";
					}
				?>
			</select>
		</td>
	</tr>
</table>
