<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<tr>
	<td>
		<label for="street_number" class="required">Number
		</label>
	</td>
	<td>
		<input name="street_number" id="street_number"
					value="<?php echo View::escape($this->address->getStreet_number()); ?>" />
	</td>
</tr>
<tr>
	<td><label for="streetName" class="required">Street</label></td>
	<td><?php
			$block = new Block('addresses/partials/streetSelector.inc');
			if ($this->address->getStreet()) {
			$block->street = $this->address->getStreet();
			}
			echo $block->render();
		?>
	</td>
</tr>
<tr>
	<td>
		<label for="zip" class="required">Zip Code</label>
	</td>
	<td>
		<select name="zip" id="zip">
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
<tr>
	<td><label>Status</label></td>
	<td><?php echo $this->address->getStatus(); ?>
	</td>
</tr>
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
