<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */

$street = $this->street;
$address = $this->address;
$location = $this->location;

?>

<h1>Add Address</h1>

<div id="addAddressForm" class="yui-skin-sam">
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<fieldset><legend>Address Information</legend>
	<?php
		echo "<input type=\"hidden\" id=\"street_id\" name=\"address[street_id]\" value=\"{$street->getStreet_id()}\">";
	?>
	<table>
		<tr><td>
				<table> <!-- left table -->
					<tr><td><label>Street</label></td>
						<td><div id="findStreetForm-autocomplete" style="width:15em;">
							<input name="streetName" id="streetName" value="<?php echo "{$street->getStreet_direction_code()} {$street->getStreetName()->getStreet_name()} {$street->getStreet_type_suffix_code()"; ?>" />
							<div id="container"></div>	
							</div>	
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
						<td><input name="address[zip]" id="address-zip" value="<?php echo address->getZip(); ?>"/>
						</td>
					</tr>

					<tr><td><label for="address-zipplus4">Zip Plus 4</label></td>
						<td><input name="address[zipplus4]" id="address-zipplus4" value="<?php echo $address->getZipplus4(); ?>"/>
						</td>
					</tr>

					<tr><td><label for="address-status_code">Status</label></td>
						<td><select name="address[status_code]" id="address-status_code" >
							<option></option>
							<?php
								$list = new AddressStatusList();
								$list->find();
								foreach($list as $status){
									$selected = $address->getStatus_code() == $status->getId() ? "selected=\"selected\"":"";
									echo "<option $selected value=\"{$status->getId()}\">$status</option>";
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
									$selected = $jurisdiction->getId()== $address->getJurisdiction_id() ? "selected=\"selected\"":"";
									echo "<option $selected value=\"{$jurisdiction->getId()}\">$name</option>";
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
									$selected = $address->getTownship_id() == $township->getId() ? "selected=\"selected\"":"";
									$name = View::escape($township->getName());
									echo "<option $selected value='{$township->getId()}'>$name</option>";
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
						<td><input name="location[id]" value="<?php echo $location->getId(); ?>">
						</td>
					</tr>
					
					<tr><td><label for="location-mailable">Mailable</label></td>
						<?php
							$checked = $location->getMailable_flag()?"checked=\"checked\"":"";
							echo "<td><checkbox name=\"location[mailable_flag]\" value=\"y\" id=\"location-mailable\" $checked />";
						?>
						</td>
					</tr>

					<tr><td><label for="location-livable">Livable</label></td						<?php
							$checked = $location->getLivable_flag()?"checked=\"checked\"":"";
							echo "<td><checkbox name=\"location[livable_flag]\" value=\"y\" id=\"location-livable\" $checked />";
						?>
						</td>
					</tr>					

					<tr><td><label for="location-active">Current Address</label></td>
						<?php
							$checked = $location->getActive()?"checked=\"checked\"":"";
							echo "<td><checkbox name=\"location[active]\" value=\"y\" id=\"location-active\" $checked />";
							?>
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
						<td><input name="address[latitude]" id="address-latitude" value="<?php echo $address->getLatitude(); ?>" />
							<input name="address[longitude]" id="address-longitude" value="<?php echo $address->getLongitude(); ?>" />
						</td>
					</tr>

					<tr><td><label for="address-state_plane_x_coordinate">State Plane </label></td>
						<td><input name="address[state_plane_x_coordinate]" id="address-state_plane_x_coordinate" value="<?php echo $address->getState_plane_x_coordiante(); ?>" />
							<input name="address[state_plane_y_coordinate]" id="address-state_plane_y_coordinate" value="<?php echo $address->getState_plane_y_coordiante(); ?>" />
						</td>
					</tr>
					
					<tr><td><label for="address-trash_pickup_day">Trash Collection</label></td>
						<td><select name="address[trash_pickup_day]" id="address-trash_pickup_day">
							<option></option>
							<?php
								foreach (Address::getTrashDays() as $day) {
									$selected = $day == $address->getTrash_pickup_day?"selected=\"selected\"":"";
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
									$selected = $week == $address->getRecycle_week()?"selected=\"selected\"":"";	
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
						<td><input name="address[city]" id="address-city" value="<?php echo $address->getCity(); ?>" />
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
		<checkbox name="batch_mode" id="batch_mode" value="y" /><label for="batch_mode">Submit in Batch Mode</label>
			
		</button>
	</fieldset>
</form>
</div>

<script type="text/javascript">
addAddressForm_autosuggest = function() {
	var streetService = new YAHOO.util.XHRDataSource("<?php echo BASE_URL; ?>/streets/home.php");
	streetService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	streetService.responseSchema = {
		resultsList: "streets",
		fields: ["name","id"]
	};
	streetService.maxCacheEntries = 5;

	var findStreetFormAC = new YAHOO.widget.AutoComplete("streetName",
														"container",
														streetService);
	findStreetFormAC.resultTypeList = false;
	var myHiddenField = YAHOO.util.Dom.get("street_id"); 
	var myHandler = function(sType, aArgs) { 
		var myAC = aArgs[0]; // reference back to the AC instance 
		var elLI = aArgs[1]; // reference to the selected LI element 
		var oData = aArgs[2]; // object literal of selected item's result data 
			 
			// update hidden form field with the selected item's ID 
			myHiddenField.value = oData.id; 
		}; 
	findStreetFormAC.itemSelectEvent.subscribe(myHandler); 
		 
	findStreetFormAC.generateRequest = function(query) {
		return '?format=json;streetName=' + query;
	}

	return{ 
		streetService: streetService,
		findStreetFormAC: findStreetFormAC
	};
}();
</script>