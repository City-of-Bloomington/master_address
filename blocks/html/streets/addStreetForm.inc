<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
?>
<h1>Streets / Add Street</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Information</legend>
		<table>
			<tr><td><label for="street-street_direction_code">Direction</label></td>
				<td><select name="street[street_direction_code]" id="street-street_direction_code" >
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

			<tr><td><label for="street-name">Name</label></td>
				<td><input name="streetName[street_name]" id="street-name" />
				</td>
			</tr>
			
			<tr><td><label for="street-suffix">Suffix</label></td>
				<td><select name="streetName[street_type_suffix_code]" id="street-suffix" >
						<option></option>
						<?php
							$suffixes = new StreetTypeList();
							$suffixes->find();
							foreach ($suffixes as $suffix) {
								echo "<option value=\"{$suffix->getSuffix_code()}\">{$suffix->getDescription()}</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="street-post_direction_suffix_code">Post Direction</label></td>
				<td><select name="street[post_direction_suffix_code]" id="street-post_direction_suffix_code">
						<option></option>
						<?php
							foreach ($directions as $direction) {
								echo "<option value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="street-town_id">Town</label></td>
				<td><select name="street[town_id]" id="street-town_id" >
						<option></option>
						<?php
							$towns = new TownList();
							$towns->find();
							foreach ($towns as $town) {
								$description = View::escape($town->getDescription());
								echo "<option value=\"{$town->getId()}\">$description</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="street-status_code">Status</label></td>
				<td><select name="street[status_code]" id="street-status_code" >
						<option></option>
						<?php
							$statuses = new StreetStatusList();
							$statuses->find();
							foreach ($statuses as $status) {
								$description = View::escape($status->getDescription());
								echo "<option value=\"{$status->getId()}\">$description</option>";
							}
						?>
                </select>
				</td>
			</tr>

			<tr><td><label for="street-type">Type</label></td>
				<td><select name="streetName[street_name_type]" id="street-type" >
						<option></option>
						<?php
							$types = new StreetNameTypeList();
							$types->find();
							foreach ($types as $type) {
								echo "<option value=\"{$type->getStreet_name_type()}\">{$type->getDescription()}</option>";
							}
						?>
					</select>
				</td>
			</tr>			

			<tr><td><label for="street-notes">Notes</label></td>
				<td><textarea name="street[notes]" id="street-notes" rows="4" cols="30" wrap="wrap">
				</textarea>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit Street</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
