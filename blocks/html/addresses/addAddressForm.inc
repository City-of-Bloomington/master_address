<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Address</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Info</legend>
		<table>
			<tr><td><label for="address-street_number">Street Number</label></td>
				<td><input name="address[street_number]" id="address-street_number" />
				</td>
			</tr>

			<tr><td><label for="address-street_id" class="required">Street ID</label></td>
				<td><input name="address[street_id]" id="address-street_id" />
				</td>
			</tr>

			<tr><td><label for="address-address_type" class="required">Type</label></td>
				<td><select name="address[address_type]" id="address-address_type" >
						<?php
							foreach(Address::getAddressTypes() as $type){
								echo "<option>$type</option>";
							}
						?>
                  </select>
               </td>
			</tr>

			<tr><td><label for="address-tax_jurisdiction">Tax Jurisdiction</label></td>
				<td><input name="address[tax_jurisdiction]" id="address-tax_jurisdiction" />
				</td>
			</tr>

			<tr><td><label for="address-jurisdiction_id">Jurisdiction</label></td>
				<td><select name="address[jurisdiction_id]" id="address-jurisdiction_id">
						<?php
							$list = new JurisdictionList();
							$list->find();
							foreach ($list as $jurisdiction) {
								$name = View::escape($jurisdiction);
								echo "<option>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="address-township_id">Township</label></td>
				<td><select name="address[township_id]" id="address-township_id" >
						<option></option>
						<?php
							$townships = new TownshipList();
							$townships->find();
							foreach ($townships as $township) {
								$name = View::escape($township->getName());
								echo "<option value='{$township->getId()}'>$name</option>";
							}
						?>
                   </select>
				</td>
			</tr>

			<tr><td><label for="address-section">Section</label></td>
				<td><input name="address[section]" id="address-section" />
				</td>
			</tr>

			<tr><td><label for="address-quarter_section">Quarter Section</label></td>
				<td><input name="address[quarter_section]" id="address-quarter_section" />
				</td>
			</tr>

			<tr><td><label for="address-subdivision_id">Subdivision ID</label></td>
				<td><input name="address[subdivision_id]" id="address-subdivision_id" />
				</td>
			</tr>

			<tr><td><label for="address-plat_id">Plat ID</label></td>
				<td><input name="address[plat_id]" id="address-plat_id" />
				</td>
			</tr>

			<tr><td><label for="address-plat_lot_number">Plat Lot Number</label></td>
				<td><input name="address[plat_lot_number]" id="address-plat_lot_number" />
				</td>
			</tr>

			<tr><td><label for="address-street_address_2">Street Address 2</label></td>
				<td><input name="address[street_address_2]" id="address-street_address_2" />
				</td>
			</tr>

			<tr><td><label for="address-city">City</label></td>
				<td><input name="address[city]" id="address-city" />
				</td>
			</tr>

			<tr><td><label for="address-state">State</label></td>
				<td><input name="address[state]" id="address-state" />
				</td>
			</tr>

			<tr><td><label for="address-zip">Zip</label></td>
				<td><input name="address[zip]" id="address-zip" />
				</td>
			</tr>

			<tr><td><label for="address-zipplus4">Zip Plus 4</label></td>
				<td><input name="address[zipplus4]" id="address-zipplus4" />
				</td>
			</tr>

			<tr><td><label for="address-census_block_fips_code">Census Block Fips_code</label></td>
				<td><input name="address[census_block_fips_code]" id="address-census_block_fips_code" />
				</td>
			</tr>

			<tr><td><label for="address-state_plane_x_coordinate">State Plane X Coordinate</label></td>
				<td><input name="address[state_plane_x_coordinate]" id="address-state_plane_x_coordinate" />
				</td>
			</tr>

			<tr><td><label for="address-state_plane_y_coordinate">State Plane Y Ccoordinate</label></td>
				<td><input name="address[state_plane_y_coordinate]" id="address-state_plane_y_coordinate" />
				</td>
			</tr>

			<tr><td><label for="address-latitude">Latitude</label></td>
				<td><input name="address[latitude]" id="address-latitude" />
				</td>
			</tr>

			<tr><td><label for="address-longitude">Longitude</label></td>
				<td><input name="address[longitude]" id="address-longitude" />
				</td>
			</tr>

			<tr><td><label for="address-status_code">Status</label></td>
				<td><select name="address[status_code]" id="address-status_code" >
						<option></option>
						<?php
							$list = new AddressStatusList();
							$list->find();
							foreach($list as $status){
								echo "<option value=\"{$status->getId()}\">$status</option>";
							}
						?>
				    </select>
				</td>
			</tr>

			<tr><td colspan="2">
					<label for="address-notes">Notes</label>
					<div><textarea name="address[notes]" id="address-notes" rows="3" cols="50"></textarea>
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