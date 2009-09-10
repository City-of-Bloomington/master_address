<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<table>
	<tr><td><label for="address_type" class="required">Type</label></td>
		<td><select name="address_type" id="address_type">
			<?php
				foreach (Address::getAddressTypes() as $type) {
					$selected = $this->address->getAddress_type() == $type
						? 'selected="selected"'
						: '';
					echo "<option $selected>$type</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="zip">Zip Code</label></td>
		<td><input name="zip" id="zip" size="5" maxlength="5"
				value="<?php echo $this->address->getZip(); ?>" />
			<input name="zipplus4" id="zipplus4" size="4" maxlength="4"
					value="<?php echo $this->address->getZipplus4(); ?>" />
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
	<tr><td><label for="jurisdiction_id">Jurisdiction</label></td>
		<td><select name="jurisdiction_id" id="jurisdiction_id">
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach ($list as $jurisdiction) {
					$name = View::escape($jurisdiction);
					$selected = $this->address->getJurisdiction_id() == $jurisdiction->getId()
						? 'selected="selected"'
						: '';
					echo "<option value=\"{$jurisdiction->getId()}\" $selected>$name</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="township_id">Township</label></td>
		<td><select name="township_id" id="township_id" >
				<option></option>
				<?php
					$list = new TownshipList();
					$list->find();
					foreach ($list as $township) {
						$name = View::escape($township->getName());
						$selected = $this->address->getTownship_id() == $township->getId()
							? 'selected="selecte"'
							: '';
						echo "<option value=\"{$township->getId()}\">$name</option>";
					}
				?>
			</select>
		</td>
	</tr>
	<tr><td><label for="section">Section</label></td>
		<td><input name="section" id="section" size="2" maxlength="2"
					value="<?php echo View::escape($this->address->getSection()); ?>" />
			<label for="quarter_section">Quarter Section</label>
			<input name="quarter_section" id="quarter_section" size="2" maxlength="2"
					value="<?php echo View::escape($this->address->getQuarter_section()); ?>" />
		</td>
	</tr>
	<tr><td><label for="census_block_fips_code">Census Block</label></td>
		<td><input name="census_block_fips_code" id="census_block_fips_code"
					value="<?php echo View::escape($this->address->getCensus_block_fips_code()); ?>" />
		</td>
	</tr>
	<tr><td><label for="latitude">Lat/Long</label></td>
		<td><input name="latitude" id="latitude" size="10" maxlength="10"
					value="<?php echo $this->address->getLatitude(); ?>" />
			<input name="longitude" id="longitude" size="10" maxlength="10"
					value="<?php echo $this->address->getLongitude(); ?>" />
		</td>
	</tr>

	<tr><td><label for="state_plane_x_coordinate">State Plane X/Y</label></td>
		<td><input name="state_plane_x_coordinate" id="state_plane_x_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_x_coordinate(); ?>" />
			<input name="state_plane_y_coordinate" id="state_plane_y_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_y_coordinate(); ?>" />
		</td>
	</tr>
	<tr><td><label for="tax_jurisdiction">Tax Jurisdiction</label></td>
		<td><input name="tax_jurisdiction" id="tax_jurisdiction" size="3" maxlength="3"
					value="<?php echo $this->address->getTax_jurisdiction(); ?>" />
		</td>
	</tr>
</table>
