<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 * @param Location $this->location
 */
?>
<table>
	<tr><th>Location</th>
		<th><?php echo $this->location->getId(); ?></th>
	</tr>

	<tr><td><label for="mailable">Mailable</label></td>
		<td><?php
				$checked = $this->location->isMailable($this->address)
							? 'checked="checked"' : '';
				echo "<input name=\"mailable\" id=\"mailable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="livable">Livable</label></td>
		<td><?php
				$checked = $this->location->isLivable($this->address)
							? 'checked="checked"' : '';
				echo "<input name=\"livable\" id=\"livable\" type=\"checkbox\" $checked />";
			?>
		</td>
	</tr>

	<tr><td><label for="location_type_id">Location Type</label></td>
		<td><select name="location_type_id">
				<?php
					$locationType = $this->location->getLocationType($this->address);

					$types = new LocationTypeList();
					$types->find();
					foreach ($types as $type) {
						$selected = ($locationType && $locationType->getId() == $type->getId())
									? $selected = 'selected="selected"'
									: '';
						echo "<option $selected >{$type->getId()}</option>";
					}
				?>
			</select>
		</td>
	</tr>
</table>
