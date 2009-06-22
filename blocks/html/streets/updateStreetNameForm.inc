<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param StreetName $this->streetName
 */
?>
<h1>Update Street Name</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Name Info</legend>
		<input name="streetName_id" type="hidden" value="<?php echo $this->streetName->getId(); ?>" />
   		<input name="streetName[street_id]" type="hidden" value="<?php echo $this->streetName->getStreet_id(); ?>" />
		<table>
  			<tr><td><label for="streetName-street_direction_code">Direction</label></td>
				<td><select name="streetName[street_direction_code]" id="streetName-street_direction_code" >
						<option></option>
						<?php
							$directions = new DirectionList();
							$directions->find();
							foreach ($directions as $direction) {
								$selected = ($direction->getCode() == $this->streetName->getDirectionCode())
											? 'selected="selected"'
											: '';
								echo "<option $selected value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>";
							}
						?>
                     </select>
				</td>
			</tr>

			<tr><td><label for="streetName-street_name" class="required">Name</label></td>
				<td><input name="streetName[street_name]" id="streetName-street_name" value="<?php echo View::escape($this->streetName->getName()); ?>" />
				</td>
			</tr>

			<tr><td><label for="streetName-street_type_suffix_code">Street Type</label></td>
				<td><select name="streetName[street_type_suffix_code]" id="streetName-street_type_suffix_code">
						<option></option>
						<?php
							$types = new StreetTypeList();
							$types->find();
							foreach ($types as $streetType) {
								$selected = ($streetType->getCode() == $this->streetName->getStreet_type_suffix_code())
											? 'selected="selected"'
											: '';
								echo "<option $selected value=\"{$streetType->getCode()}\">$streetType</option>";
							}
						?>
                    </select>
				</td>
			</tr>
			<tr><td><label for="streetName-post_direction_suffix_code">Post Direction</label></td>
				<td><select name="streetName[post_direction_suffix_code]" id="streetName-post_direction_suffix_code" >
						<option></option>
						<?php
							foreach ($directions as $direction) {
								$selected = ($direction->getCode() == $this->streetName->getPost_direction_suffix_code())
											? 'selected="selected"'
											: '';
								echo "<option $selected value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>";
							}
						?>
                     </select>
				</td>
			</tr>
			<tr><td><label for="streetName-street_name_type">Street Name Type</label></td>
				<td><select name="streetName[street_name_type]" id="streetName-street_name_type" >
						<option></option>
						<?php
							$types = new StreetNameTypeList();
							$types->find();
							foreach ($types as $type) {
								$selected = ($type->getType() == $this->streetName->getStreet_name_type())
											? 'selected="selected"'
											: '';
								echo "<option $selected value=\"{$type->getType()}\">$type</option>";
							}
						?>
                     </select>
				</td>
			</tr>
			<tr><td><label for="streetName-effective_start_date">Effective Start Date</label></td>
				<td><input name="streetName[effective_start_date]"
							id="streetName-effective_start_date"
							size="10" maxlenght="10"
							value="<?php echo $this->streetName->getEffective_start_date('n/j/Y'); ?>" />
				</td>
			</tr>
			<tr><td><label for="streetName-effective_end_date">Effective End Date</label></td>
				<td><input name="streetName[effective_end_date]"
							id="streetName-effective_end_date"
							size="10" maxlength="10"
							value="<?php echo $this->streetName->getEffective_end_date('n/j/Y'); ?>" />
				</td>
			</tr>

			<tr><td colspan="2">
					<div>
						<label for="streetName-notes">Notes</label>
					</div>
					<textarea name="streetName[notes]" id="streetName-notes" rows="3" cols="50"><?php echo View::escape($this->streetName->getNotes()); ?></textarea>
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>