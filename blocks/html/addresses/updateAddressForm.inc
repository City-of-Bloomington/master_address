<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Update Address</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Info</legend>
		<input name="street_address_id" type="hidden" value="<?php echo $this->address->getStreet_address_id(); ?>" />
		<table>

			<tr><td><label for="address-street_number">Street Number</label></td>
				<td><input name="address[street_number]" id="address-street_number" value="<?php echo $this->address->getStreet_number(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-street_id" class="required">Street ID</label></td>
				<td><input name="address[street_id]" id="address-street_id" value="<?php echo $this->address->getStreet_id(); ?>" />
				</td>
			</tr>

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

			<tr><td><label for="address-tax_jurisdiction">Tax Jurisdiction</label></td>
				<td><input name="address[tax_jurisdiction]" id="address-tax_jurisdiction" value="<?php echo $this->address->getTax_jurisdiction(); ?>" />
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
							foreach ($list as $township){
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
				<td><input name="address[section]" id="address-section" value="<?php echo $this->address->getSection(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-quarter_section">Quarter Section</label></td>
				<td><input name="address[quarter_section]" id="address-quarter_section" value="<?php echo $this->address->getQuarter_section(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-subdivision_id">Subdivision ID</label></td>
				<td><input name="address[subdivision_id]" id="address-subdivision_id" value="<?php echo $this->address->getSubdivision_id(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-plat_id">Plat ID</label></td>
				<td><input name="address[plat_id]" id="address-plat_id" value="<?php echo $this->address->getPlat_id(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-plat_lot_number">Plat Lot Number</label></td>
				<td><input name="address[plat_lot_number]" id="address-plat_lot_number" value="<?php echo $this->address->getPlat_lot_number(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-street_address_2">Street Address 2</label></td>
				<td><input name="address[street_address_2]" id="address-street_address_2" value="<?php echo $this->address->getStreet_address_2(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-city">City</label></td>
				<td><input name="address[city]" id="address-city" value="<?php echo $this->address->getCity(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-state">State</label></td>
				<td><input name="address[state]" id="address-state" value="<?php echo $this->address->getState(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-zip">Zip</label></td>
				<td><input name="address[zip]" id="address-zip" value="<?php echo $this->address->getZip(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-zipplus4">Zip Plus 4</label></td>
				<td><input name="address[zipplus4]" id="address-zipplus4" value="<?php echo $this->address->getZipplus4(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-census_block_fips_code">Census Nlock Fips Code</label></td>
				<td><input name="address[census_block_fips_code]" id="address-census_block_fips_code" value="<?php echo $this->address->getCensus_block_fips_code(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-state_plane_x_coordinate">State Plane X Coordinate</label></td>
				<td><input name="address[state_plane_x_coordinate]" id="address-state_plane_x_coordinate" value="<?php echo $this->address->getState_plane_x_coordinate(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-state_plane_y_coordinate">State Plane Y Coordinate</label></td>
				<td><input name="address[state_plane_y_coordinate]" id="address-state_plane_y_coordinate" value="<?php echo $this->address->getState_plane_y_coordinate(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-latitude">Latitude</label></td>
				<td><input name="address[latitude]" id="address-latitude" value="<?php echo $this->address->getLatitude(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-longitude">Longitude</label></td>
				<td><input name="address[longitude]" id="address-longitude" value="<?php echo $this->address->getLongitude(); ?>" />
				</td>
			</tr>

			<tr><td><label for="address-status_code">Status</label></td>
				<td><select name="address[status_code]" id="address-status_code" >
						<option></option>
						<?php
							$list = new AddressStatusList();
							$list->find();
							foreach ($list as $status) {
							  $selected = $this->address->getStatus_code() == $status->getStatus_code()
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$status->getStatus_code()}\" $selected>$status</option>";
							}
						?>
				    </select>
				</td>
			</tr>

			<tr><td><label for="address-trash_pickup_day">Trash Pickup Day</label></td>
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

			<tr><td colspan="2">
					<label for="address-notes">Notes</label>
					<div><textarea name="address[notes]" id="address-notes" rows="3" cols="50"><?php echo View::escape($this->address->getNotes()); ?></textarea>
					</div>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>