<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Subunit Status</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Status Info</legend>
		<input name="id" type="hidden" value="<?php echo $this->subunitStatus->getId(); ?>" />
		<table>

			<tr><td><label>Street Address ID</label></td>
                <td><?php echo $this->subunitStatus->getStreet_address_id(); ?>
				<input name="subunitStatus[street_address_id]" type="hidden" value="<?php echo $this->subunitStatus->getStreet_address_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="subunitStatus-status_code">Status</label></td>
				<td><select name="subunitStatus[status_code]" id="subunitStatus-status_code" >
  						<option></option>
						<?php
							$list = new AddressStatusList();
							$list->find();
							foreach($list as $status){
							  $selected = $status->getId() == $this->subunitStatus->getStatus_code()?"selected=\"selected\"":"";
								echo "<option $selected value=\"{$status->getId()}\">$status</option>";
							}
						?>
				    </select>

				</td>
			</tr>
				
			<tr><td><label for="subunitStatus-start_date">Start Date</label></td>
				<td><input name="subunitStatus[start_date]" id="subunitStatus-start_date" size="10" maxlength="10" value="<?php echo $this->subunitStatus->getStart_date('n/j/Y'); ?>" />
				</td>
			</tr>
			<tr><td><label for="subunitStatus-end_date">End Date</label></td>
				<td><input name="subunitStatus[end_date]" id="subunitStatus-end_date" size="10" maxlength="10" value="<?php echo $this->subunitStatus->getEnd_date('n/j/y'); ?>" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subunits';">
			Cancel
		</button>
	</fieldset>
</form>