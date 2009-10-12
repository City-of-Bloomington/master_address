<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$searchFields = array('street_number','direction','street_name','streetType',
						'postDirection','city','zip','subunitType','subunitIdentifier');
foreach ($searchFields as $field) {
	$$field = (isset($_GET[$field]) && $_GET[$field]) ? View::escape($_GET[$field]) : '';
}
?>
<h1>Address Search</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Address</legend>
		<table>
			<thead>
				<tr><th><label for="street_number">#</label></th>
					<th><label for="direction">Dir</label></th>
					<th><label for="street_name">Street</label></th>
					<th><label for="streetType">Type</label></th>
					<th><label for="postDirection">Dir</label></th>
					<th><label for="subunitType">Subunit</label></th>
					<th><label for="subunitIdentifier">#</label></th>
				</tr>
			</thead>
			<tbody>
			<tr><td><input name="street_number" id="street_number" size="6" value="<?php echo $street_number; ?>" />
				</td>
				<td><select name="direction" id="direction">
						<option></option>
						<?php
							$directions = new DirectionList();
							$directions->find();
							foreach ($directions as $dir) {
								$selected = $direction==$dir->getCode() ? 'selected="selected"' : '';
								echo "<option $selected>{$dir->getCode()}</option>";
							}
						?>
					</select>
				</td>
				<td><input name="street_name" id="street_name" value="<?php echo $street_name; ?>" />
				</td>
				<td><select name="streetType" id="streetType">
						<option></option>
						<?php
							$types = new StreetTypeList();
							$types->find();
							foreach ($types as $type) {
								$selected = $streetType==$type->getCode()
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$type->getCode()}\" $selected>$type</option>";
							}
						?>
					</select>
				</td>
				<td><select name="postDirection" id="postDirection">
						<option></option>
						<?php
							foreach ($directions as $dir) {
								$selected = $postDirection==$dir->getCode() ? 'selected="selected"' : '';
								echo "<option $selected>{$dir->getCode()}</option>";
							}
						?>
					</select>
				</td>
				<td><select name="subunitType" id="subunitType">
						<option></option>
						<?php
							$types = new SubunitTypeList();
							$types->find();
							foreach ($types as $type) {
								$selected = $subunitType==$type->getSudtype()
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$type->getSudtype()}\" $selected>{$type->getDescription()}</option>";
							}
						?>
					</select>
				</td>
				<td><input name="subunitIdentifier" id="subunitIdentifier" size="3" value="<?php echo $subunitIdentifier; ?>" />
				</td>
			</tr>
			</tbody>
		</table>
		<div>
			<select name="city">
			<?php
				$cities = AddressList::getCities();
				foreach ($cities as $c) {
					$default = $city ? $city : 'BLOOMINGTON';
					$selected = $c==$default ? 'selected="selected"' : '';
					echo "<option $selected>$c</option>";
				}
			?>
			</select>
			<label>
				Zip
				<input name="zip" size="5" maxlength="5" value="<?php echo $zip; ?>" />
			</label>
			<button type="submit" class="search">Search</button>
		</div>
	</fieldset>
</form>
