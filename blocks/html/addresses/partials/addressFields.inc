<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<table>
	<tr><td><label for="address-address_type" class="required">Type</label></td>
		<td><select name="address[address_type]" id="address-address_type">
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
	<tr><td><label for="address-zip">Zip Code</label></td>
		<td><input name="address[zip]" id="address-zip"
					value="<?php echo $this->address->getZip(); ?>"
					size="5" maxlength="5" />
			<input name="address[zipplus4]" id="address-zipplus4"
					size="4" maxlength="4"
					value="<?php echo $this->address->getZipplus4(); ?>" />
		</td>
	</tr>
	<tr><td><label for="address-trash_pickup_day">Trash Schedule</label></td>
		<td><select name="address[trash_pickup_day]" id="address-trash_pickup_day">
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

	<tr><td><label for="address-recycle_week">Recycle Week</label></td>
		<td><select name="address[recycle_week]" id="address-recycle_week">
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
	<tr><td><label for="address-jurisdiction_id">Jurisdiction</label></td>
		<td><select name="address[jurisdiction_id]" id="address-jurisdiction_id">
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
	<tr><td><label for="address-township_id">Township</label></td>
		<td><select name="address[township_id]" id="address-township_id" >
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
	<tr><td><label for="address-section">Section</label></td>
		<td><input name="address[section]" id="address-section"
					size="2" maxlength="2"
					value="<?php echo View::escape($this->address->getSection()); ?>" />
			<label for="address-quarter_section">Quarter Section</label>
			<input name="address[quarter_section]" id="address-quarter_section"
					size="2" maxlength="2"
					value="<?php echo View::escape($this->address->getQuarter_section()); ?>" />
		</td>
	</tr>
	<tr><td><label for="address-census_block_fips_code">Census Block</label></td>
		<td><input name="address[census_block_fips_code]" id="address-census_block_fips_code"
					value="<?php echo View::escape($this->address->getCensus_block_fips_code()); ?>" />
		</td>
	</tr>
	<tr><td><label for="address-latitude">Lat/Long</label></td>
		<td><input name="address[latitude]" id="address-latitude"
					size="10" maxlength="10"
					value="<?php echo $this->address->getLatitude(); ?>" />
			<input name="address[longitude]" id="address-longitude"
					size="10" maxlength="10"
					value="<?php echo $this->address->getLongitude(); ?>" />
		</td>
	</tr>

	<tr><td><label for="address-state_plane_x_coordinate">State Plane X/Y</label></td>
		<td><input name="address[state_plane_x_coordinate]"
					id="address-state_plane_x_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_x_coordinate(); ?>" />
			<input name="address[state_plane_y_coordinate]"
					id="address-state_plane_y_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_y_coordinate(); ?>" />
		</td>
	</tr>
	<tr><td><label for="address-tax_jurisdiction">Tax Jurisdiction</label></td>
		<td><input name="address[tax_jurisdiction]" id="address-tax_jurisdiction"
					size="3" maxlength="3"
					value="<?php echo $this->address->getTax_jurisdiction(); ?>" />
		</td>
	</tr>
</table>
