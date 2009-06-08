<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
//$strTypes = new AddressTypeList();
//$strTypes.find();
$statuses = new AddressStatusList();
$statuses->find();
$townships = new TownshipList();
$townships->find();
$types = Address::$addressTypes;

?>
<h1>Add Address</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Info</legend>
		<table>
			<tr><td><label for="address-street_number">Street Number</label></td>
				<td><input name="address[street_number]" id="address-street_number" />
				</td>
			</tr>
				
			<tr><td><label for="address-street_id">Street ID</label></td>
				<td><input name="address[street_id]" id="address-street_id" />
				</td>
			</tr>
				
			<tr><td><label for="address-address_type">Address Type</label></td>
				<td><select name="address[address_type]" id="address-address_type" >
                <?php
                     foreach($types as $type){
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
				
			<tr><td><label for="address-jurisdiction_id">Jurisdiction ID</label></td>
				<td><input name="address[jurisdiction_id]" id="address-jurisdiction_id" />
				</td>
			</tr>
				
			<tr><td><label for="address-gov_jur_id">Gov Jur ID</label></td>
				<td><input name="address[gov_jur_id]" id="address-gov_jur_id" />
				</td>
			</tr>
				
			<tr><td><label for="address-township_id">Township</label></td>
  
				<td><select name="address[township_id]" id="address-township_id" >
                    <option></option>
                    <?php
                         foreach ($townships as $township){
				               echo "<option value='{$township->getId()}'>
                                    {$township->getName()}</option>";
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
				
			<tr><td><label for="address-notes">Notes</label></td>
				<td><input name="address[notes]" id="address-notes" />
				</td>
			</tr>
				
			<tr><td><label for="address-status_code">Status Code</label></td>
				<td><select name="address[status_code]" id="address-status_code" >
                    <option></option>
                    <?php
                         foreach($statuses as $status){
						   	  echo "<option value=\"{$status->getId()}\">{$status->getDescription()}</option>";
					     }
                     ?>
				    </select>				
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>