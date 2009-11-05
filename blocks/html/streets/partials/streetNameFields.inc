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
	<tr><td><label for="streetName-street_direction_code">Direction</label></td>
		<td><select name="streetName[street_direction_code]" id="streetName-street_direction_code">
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

	<tr><td><label for="streetName-street_name" class="required">Name</label></td>
		<td><input name="streetName[street_name]" id="streetName-street_name"
				value="<?php echo View::escape($streetName->getName()); ?>" />
		</td>
	</tr>

	<tr><td><label for="streetName-street_type_suffix_code">Type Suffix</label></td>
		<td><select name="streetName[street_type_suffix_code]" id="streetName-street_type_suffix_code">
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

	<tr><td><label for="streetName-post_direction_suffix_code">Post Direction</label></td>
		<td><select name="streetName[post_direction_suffix_code]" id="streetName-post_direction_suffix_code">
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
	<tr><td><label for="streetName-effective_start_date">Start Date</label></td>
		<td><input name="streetName[effective_start_date]" id="streetName-effective_start_date"
				value="<?php echo $streetName->getEffective_start_date('n/j/Y'); ?>" />
		</td>
	</tr>
	<tr><td><label for="streetName-effective_end_date">End Date</label></td>
		<td><input name="streetName[effective_end_date]" id="streetName-effective_end_date"
				value="<?php echo $streetName->getEffective_end_date('n/j/Y'); ?>" />
		</td>
	</tr>
</table>
<div>
	<div><label for="streetName-notes">Notes</label></div>
	<textarea name="streetName[notes]" id="streetName-notes" rows="3" cols="50"><?php echo View::escape($streetName->getNotes()); ?></textarea>
</div>