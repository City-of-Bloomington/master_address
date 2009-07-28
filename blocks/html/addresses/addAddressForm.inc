<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
$street_id="";
if(isset($_GET['street_id'])){
	$street_id=$_GET['street_id'];
	$street = new Street($street_id);
}

?>

<h1>Streets/Add Address</h1>

<?php

$addresses = new AddressList(array('street_id'=>$street_id));
if(count($addresses)){
	echo "<table>";
	foreach($addresses as $address){
		$editButton = '';
		if (userIsAllowed('Address')) {
			$url = new URL(BASE_URL.'/addresses/updateAddress.php');
			$url->street_address_id = $address->getId();
			$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
		}
		echo "<tr><td>{$address->getStreet_number()} {$street->getStreet_direction_code()} {$street->getStreetName()->getStreet_name()}</td><td> $editButton </td></tr>";
	  
	}
	echo "</table>"
}
?>

<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<fieldset><legend>Address Information</legend>
	<?php
		if($street_id){
			echo "<input type=\"hidden\" value=\"$street_id\">";
		}
	?>
	<table>
		<tr><td>
				<table> <!-- left table -->
					<tr><td><label>Street</label></td>
						<td><?php echo "{$street->getStreet_direction_code()} {$street->getStreetName()->getStreet_name()} {$street->getStreet_type_suffix_code()"; ?>
						<!-- search option here -->
						</td>
					</tr>
					<tr><td><label for="address-street_number">Number</label></td>
						<td><input name="address[street_number]" id="address-street_number" />
						</td>
					</tr>
					<tr><td><label for="address-address_type" class="required">Type</label></td>
						<td><select name="address[address_type]" id="address-address_type" 							<option></option>	
							<?php
								foreach(Address::getAddressTypes() as $type){
									echo "<option>$type</option>";
								}
							?>
							</select>
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
				</table>
			</td>
			<td>
				<table> <!-- middle table -->
					<tr><td><label>Location ID</label></td>
						<td><?php echo $location->getId(); ?>
						</td>
					</tr>
					
					<tr><td><label for="location-mailable">Mailable</label></td>
						<td><checkbox name="location[mailable_flag]" value="y" id="location-mailable" />
						</td>
					</tr>

					<tr><td><label for="location-livable">Livable</label></td>
						<td><checkbox name="location[livable_flag]" value="y" id="location-livable" />
						</td>
					</tr>					

					<tr><td><label for="location-active">Current Address</label></td>
						<td><checkbox name="location[active]" value="y" id="location-active" />
						</td>
					</tr>
					
					<tr><td><label for="address-plat_id">Plat</label></td>
						<td><input name="address[plat_id]" id="address-plat_id" />
						</td>
					</tr>

					<tr><td><label for="address-plat_lot_number">Plat Lot Number</label></td>
						<td><input name="address[plat_lot_number]" id="address-plat_lot_number" />
						</td>
					</tr>
					
					
					<tr><td><label for="address-tax_jurisdiction">Tax Jurisdiction</label></td>
						<td><input name="address[tax_jurisdiction]" id="address-tax_jurisdiction" />
						</td>
					</tr>

					<tr><td><label for="address-quarter_section">Quarter Section</label></td>
						<td><input name="address[quarter_section]" id="address-quarter_section" />
						</td>
					</tr>
					
					<tr><td><label for="address-census_block_fips_code">Census Block </label></td>
						<td><input name="address[census_block_fips_code]" id="address-census_block_fips_code" />
						</td>
					</tr>
				</table>
			</td>
			<td>
				
				<table><!-- right table -->
				
					<tr><td><label for="address-latitude">Coordinates </label></td>
						<td><input name="address[latitude]" id="address-latitude" />
							<input name="address[longitude]" id="address-longitude" />
						</td>
					</tr>

					<tr><td><label for="address-state_plane_x_coordinate">State Plane </label></td>
						<td><input name="address[state_plane_x_coordinate]" id="address-state_plane_x_coordinate" />
							<input name="address[state_plane_y_coordinate]" id="address-state_plane_y_coordinate" />
						</td>
					</tr>
					
					<tr><td><label for="address-trash_pickup_day">Trash Collection</label></td>
						<td><select name="address[trash_pickup_day]" id="address-trash_pickup_day">
							<option></option>
							<?php
								foreach (Address::getTrashDays() as $day) {
									echo "<option>$day</option>";
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
									echo "<option>$week</option>";
								}	
							?>
							</select>
						</td>
					</tr>

					<tr><td><label for="address-subdivision_id">Subdivision ID</label></td>
						<td><input name="address[subdivision_id]" id="address-subdivision_id" />
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

				</table>
			</td></tr>
		
			<tr><td>
					<label for="address-notes">Notes</label>
					<div><textarea name="address[notes]" id="address-notes" rows="3" cols="50"></textarea>
					</div>
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit Address</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>