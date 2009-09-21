<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */

?>
<table>
	<tr><td><label for="address_type" class="required">Address Type</label></td>
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
	<tr><td><label for="zip" class"required">Zip Code - Plus4</label></td>
		<td><select name="zip" id="zip">
			<?php
				$zips = Address::getZipCodes();
				foreach($zips as $zip){
					$selected = ($zip == $this->address->getZip())?"selected=\"selected\"":"";
					echo "<option $selected>$zip</option>";		
				}
			?>
			</select> - 
			<input name="zipplus4" id="zipplus4" size="4" maxlength="4"
					value="<?php echo $this->address->getZipplus4(); ?>" />
		</td>
	</tr>

	<tr><td><label for="jurisdiction_id" class"required">Jurisdiction</label></td>
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
	<tr><td><label for="township_id" class="required">Township</label></td>
		<td><select name="township_id" id="township_id" >
				<option></option>
				<?php
					$list = new TownshipList();
					$list->find();
					foreach ($list as $township) {
						$name = View::escape($township->getName());
						$selected = $this->address->getTownship_id() == $township->getId()
							? 'selected="selected"'
							: '';
						echo "<option $selected value=\"{$township->getId()}\">$name</option>";
					}
				?>
			</select>
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
<script type='text/javascript'>

function checkAddrRequiredFields(){
	
	var ind = document.getElementById("address_type").selectedIndex;
	var str = document.getElementById("address_type").options[ind].text;
	if(str == ""){
		alert("Address type is required");
		document.getElementById("address_type").focus();
		return false;
	}
	ind = document.getElementById("zip").selectedIndex;
	str = document.getElementById('zip').options[ind].text;
	if(str == ""){
		alert("Zip code is required");
		document.getElementById("zip").focus();		
		return false;
	}
	ind = document.getElementById("township_id").selectedIndex;
	str = document.getElementById('township_id').options[ind].value;
	if(str == ""){
		alert("Township is required");
		document.getElementById("township_id").focus();		
		return false;
	}
	ind = document.getElementById("jurisdiction_id").selectedIndex;
	str = document.getElementById('jurisdiction_id').options[ind].value;
	if(str == ""){
		alert("Jurisdiction is required");
		document.getElementById("jurisdiction_id").focus();		
		return false;
	}
	return true;

}

</script>
