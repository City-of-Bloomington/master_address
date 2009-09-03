<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<table>
	<tr><td><label for="streetName-street_direction_code">Direction</label></td>
		<td><select name="streetName[street_direction_code]" id="streetName-street_direction_code">
				<option></option>
				<?php
					$directions = new DirectionList();
					$directions->find();
					foreach ($directions as $direction) {
						echo "<option value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>";
					}
				?>
				</select>
		</td>
	</tr>

	<tr><td><label for="streetName-street_name" class="required">Name</label></td>
		<td><input name="streetName[street_name]" id="streetName-street_name" />
		</td>
	</tr>

	<tr><td><label for="streetName-street_type_suffix_code">Street Type</label></td>
		<td><select name="streetName[street_type_suffix_code]" id="streetName-street_type_suffix_code">
				<option></option>
				<?php
					$types = new StreetTypeList();
					$types->find();
					foreach ($types as $type) {
						echo "<option value=\"{$type->getCode()}\">$type</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="streetName-post_direction_suffix_code">Post Direction</label></td>
		<td><select name="streetName[post_direction_suffix_code]" id="streetName-post_direction_suffix_code">
				<option></option>
				<?php
					foreach ($directions as $direction) {
						echo "<option value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>";
					}
				?>
			</select>
		</td>
	</tr>
</table>
