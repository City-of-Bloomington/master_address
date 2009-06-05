<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update MastAddress</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>MastAddress Info</legend>
		<input name="street_address_id" type="hidden" value="<?php echo $this->mast_address->getStreet_address_id(); ?>" />
		<table>

			<tr><td><label for="mast_address-street_number">street_number</label></td>
				<td><input name="mast_address[street_number]" id="mast_address-street_number" value="<?php echo $this->mast_address->getStreet_number(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-street_id">street_id</label></td>
				<td><input name="mast_address[street_id]" id="mast_address-street_id" value="<?php echo $this->mast_address->getStreet_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-address_type">address_type</label></td>
				<td><input name="mast_address[address_type]" id="mast_address-address_type" value="<?php echo $this->mast_address->getAddress_type(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-tax_jurisdiction">tax_jurisdiction</label></td>
				<td><input name="mast_address[tax_jurisdiction]" id="mast_address-tax_jurisdiction" value="<?php echo $this->mast_address->getTax_jurisdiction(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-jurisdiction_id">jurisdiction_id</label></td>
				<td><input name="mast_address[jurisdiction_id]" id="mast_address-jurisdiction_id" value="<?php echo $this->mast_address->getJurisdiction_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-gov_jur_id">gov_jur_id</label></td>
				<td><input name="mast_address[gov_jur_id]" id="mast_address-gov_jur_id" value="<?php echo $this->mast_address->getGov_jur_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-township_id">township_id</label></td>
				<td><input name="mast_address[township_id]" id="mast_address-township_id" value="<?php echo $this->mast_address->getTownship_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-section">section</label></td>
				<td><input name="mast_address[section]" id="mast_address-section" value="<?php echo $this->mast_address->getSection(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-quarter_section">quarter_section</label></td>
				<td><input name="mast_address[quarter_section]" id="mast_address-quarter_section" value="<?php echo $this->mast_address->getQuarter_section(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-subdivision_id">subdivision_id</label></td>
				<td><input name="mast_address[subdivision_id]" id="mast_address-subdivision_id" value="<?php echo $this->mast_address->getSubdivision_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-plat_id">plat_id</label></td>
				<td><input name="mast_address[plat_id]" id="mast_address-plat_id" value="<?php echo $this->mast_address->getPlat_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-plat_lot_number">plat_lot_number</label></td>
				<td><input name="mast_address[plat_lot_number]" id="mast_address-plat_lot_number" value="<?php echo $this->mast_address->getPlat_lot_number(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-street_address_2">street_address_2</label></td>
				<td><input name="mast_address[street_address_2]" id="mast_address-street_address_2" value="<?php echo $this->mast_address->getStreet_address_2(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-city">city</label></td>
				<td><input name="mast_address[city]" id="mast_address-city" value="<?php echo $this->mast_address->getCity(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-state">state</label></td>
				<td><input name="mast_address[state]" id="mast_address-state" value="<?php echo $this->mast_address->getState(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-zip">zip</label></td>
				<td><input name="mast_address[zip]" id="mast_address-zip" value="<?php echo $this->mast_address->getZip(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-zipplus4">zipplus4</label></td>
				<td><input name="mast_address[zipplus4]" id="mast_address-zipplus4" value="<?php echo $this->mast_address->getZipplus4(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-census_block_fips_code">census_block_fips_code</label></td>
				<td><input name="mast_address[census_block_fips_code]" id="mast_address-census_block_fips_code" value="<?php echo $this->mast_address->getCensus_block_fips_code(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-state_plane_x_coordinate">state_plane_x_coordinate</label></td>
				<td><input name="mast_address[state_plane_x_coordinate]" id="mast_address-state_plane_x_coordinate" value="<?php echo $this->mast_address->getState_plane_x_coordinate(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-state_plane_y_coordinate">state_plane_y_coordinate</label></td>
				<td><input name="mast_address[state_plane_y_coordinate]" id="mast_address-state_plane_y_coordinate" value="<?php echo $this->mast_address->getState_plane_y_coordinate(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-latitude">latitude</label></td>
				<td><input name="mast_address[latitude]" id="mast_address-latitude" value="<?php echo $this->mast_address->getLatitude(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-longitude">longitude</label></td>
				<td><input name="mast_address[longitude]" id="mast_address-longitude" value="<?php echo $this->mast_address->getLongitude(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-notes">notes</label></td>
				<td><input name="mast_address[notes]" id="mast_address-notes" value="<?php echo $this->mast_address->getNotes(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="mast_address-status_code">status_code</label></td>
				<td><input name="mast_address[status_code]" id="mast_address-status_code" value="<?php echo $this->mast_address->getStatus_code(); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/mast_addresss';">
			Cancel
		</button>
	</fieldset>
</form>