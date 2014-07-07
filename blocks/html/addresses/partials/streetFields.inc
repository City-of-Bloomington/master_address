<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$id = '';
$name = '';
if ($this->address->getStreet()) {
	$id = $this->address->getStreet()->getId();
	$name = View::escape($this->address->getStreet()->getStreetName());
}
?>
	<tr><td><label for="street_number_prefix">Prefix</label></td>
		<td><input name="street_number_prefix" id="street_number_prefix" size="3"
					value="<?php echo View::escape($this->address->getStreet_number_prefix()); ?>" />
		</td>
	</tr>
	<tr><td><label for="street_number" class="required">Number</label></td>
		<td><input name="street_number" id="street_number" size="8"
					value="<?php echo View::escape($this->address->getStreet_number()); ?>" />
		</td>
	</tr>
	<tr><td><label for="street_number_suffix">Suffix</label></td>
		<td><input name="street_number_suffix" id="street_number_suffix" size="3"
					value="<?php echo View::escape($this->address->getStreet_number_suffix()); ?>" />
		</td>
	</tr>
	<tr><td><label for="street_id" class="required">Street</label></td>
		<td><div>
				<span id="chosenStreetName"><?php echo $name ?></span>
				<input type="hidden" name="street_id" id="street_id" value="<?php echo $id; ?>" />
				<button type="button" onclick="openStreetSelector();">Choose Street</button>
			</div>
		</td>
	</tr>
	<tr><td><label for="zip" class="required">Zip Code</label></td>
		<td><select name="zip" id="zip"
				onchange="updateCity(this.options[this.selectedIndex].value);">
				<?php
					$zips = Address::getZipCodes();
					foreach (array_keys($zips) as $zip) {
						$selected = $zip==$this->address->getZip() ? 'selected="selected"' : '';
						echo "<option $selected>$zip</option>";
					}
				?>
			</select> -
			<input name="zipplus4" id="zipplus4" size="4" maxlength="4"
						value="<?php echo $this->address->getZipplus4(); ?>" />
			<script type="text/javascript">
				function updateCity(zip) {
					var zipcodes = new Array();
					<?php
						foreach (Address::getZipCodes() as $zip=>$city) {
							echo "zipcodes[$zip] = '$city';\n";
						}
					?>
					document.getElementById('city').innerHTML = zipcodes[zip];
				}
			</script>
		</td>
	</tr>
	<tr><td><label>City</label></td>
		<td id="city">
			<?php echo View::escape($this->address->getCity()); ?>
		</td>
	</tr>
	<tr><td><label>Status</label></td>
		<td><?php echo $this->address->getStatus(); ?></td>
	</tr>
