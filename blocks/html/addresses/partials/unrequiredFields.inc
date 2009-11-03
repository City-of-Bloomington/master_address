<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
	<tr><td><label for="state_plane_x_coordinate">State Plane X/Y</label></td>
		<td><input name="state_plane_x_coordinate" id="state_plane_x_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_x_coordinate(); ?>" />
			<input name="state_plane_y_coordinate" id="state_plane_y_coordinate"
					size="7" maxlength="7"
					value="<?php echo $this->address->getState_plane_y_coordinate(); ?>" />
		</td>
	</tr>
	<tr><td><label for="latitude">Lat/Long</label></td>
		<td><input name="latitude" id="latitude" size="10" maxlength="10"
					value="<?php echo $this->address->getLatitude(); ?>" />
			<input name="longitude" id="longitude" size="10" maxlength="10"
					value="<?php echo $this->address->getLongitude(); ?>" />
		</td>
	</tr>
	<tr><td><label for="census_block_fips_code">Census Block</label></td>
		<td><input name="census_block_fips_code" id="census_block_fips_code"
					value="<?php echo View::escape($this->address->getCensus_block_fips_code()); ?>" />
		</td>
	</tr>

	<tr><td><label for="tax_jurisdiction">Tax Jurisdiction</label></td>
		<td><select name="tax_jurisdiction" id="tax_jurisdiction">
				<option></option>
				<?php
					foreach (Address::getTaxJurisdictions() as $taxJurisdiction) {
						$selected = $this->address->getTax_jurisdiction()==$taxJurisdiction
									? 'selected="selected"'
									: '';
						echo "<option $selected>$taxJurisdiction</option>";
					}
				?>
			</select>
		</td>
	</tr>