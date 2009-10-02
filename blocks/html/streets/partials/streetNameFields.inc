<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetName $this->streetName (optional)
 */
$streetName = $this->streetName ? $this->streetName : new StreetName();
?>
<table>
	<tr><td><label for="street_direction_code">Direction</label></td>
		<td><select name="street_direction_code" id="street_direction_code">
				<option></option>
				<?php
					$directions = new DirectionList();
					$directions->find();
					foreach ($directions as $direction) {
						$selected = $streetName->getDirectionCode()==$direction->getCode()
									? 'selected="selected"'
									: '';
						echo "<option value=\"{$direction->getCode()}\" $selected>{$direction->getDescription()}</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="street_name" class="required">Name</label></td>
		<td><input name="street_name" id="street_name" value="<?php echo View::escape($streetName->getName()); ?>" />
		</td>
	</tr>

	<tr><td><label for="street_type_suffix_code">Street Type</label></td>
		<td><select name="street_type_suffix_code" id="street_type_suffix_code">
				<option></option>
				<?php
					$types = new StreetTypeList();
					$types->find();
					foreach ($types as $type) {
						$selected = $streetName->getStreet_type_suffix_code()==$type->getCode()
									? 'selected="selected"'
									: '';
						echo "<option value=\"{$type->getCode()}\" $selected>$type</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="post_direction_suffix_code">Post Direction</label></td>
		<td><select name="post_direction_suffix_code" id="post_direction_suffix_code">
				<option></option>
				<?php
					foreach ($directions as $direction) {
						$selected = $streetName->getPostDirectionCode()==$direction->getCode()
									? 'selected="selected"'
									: '';
						echo "<option value=\"{$direction->getCode()}\" $selected>{$direction->getDescription()}</option>";
					}
				?>
			</select>
		</td>
	</tr>
</table>
