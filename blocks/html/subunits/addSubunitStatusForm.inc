<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$subunit_id = $this->subunit->getId();
$street_address_id = $this->subunit->getStreet_address_id();


?>
<h1>Add Subunit Status</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Status Info</legend>
		<table>
			<tr><td><label>Subunit ID</label></td>
				<td><?php echo $subunit_id ?>
                    <input name="subunit_id" type="hidden" value="<?php echo $subunit_id; ?>" />
                    <input name="subunitStatus[subunit_id]" type="hidden" value="<?php echo $subunit_id; ?>" />
				</td>
			</tr>
			<tr><td><label>Address ID</label></td>
                <td><?php echo $street_address_id; ?>
                    <input name="subunitStatus[street_address_id]" value="<?php echo $street_address_id ?>" type="hidden" />
				</td>
			</tr>
				
			<tr><td><label for="subunitStatus-status_code">Status </label></td>
				<td><select name="subunitStatus[status_code]" id="subunitStatus-status_code" >
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
				
			<tr><td><label for="subunitStatus-start_date">Start Date</label></td>
				<td><input name="subunitStatus[start_date]" size="10" maxlength="10" id="subunitStatus-start_date" />
				</td>
			</tr>
			<tr><td><label for="subunitStatus-end_date">End Date</label></td>
				<td><input name="subunitStatus[end_date]" size="10" maxlength="10" id="subunitStatus-end_date" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subunits';">
			Cancel
		</button>
	</fieldset>
</form>