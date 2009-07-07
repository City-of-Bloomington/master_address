<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$statuses = new AddressStatusList();
$statuses->find();
?>
<h1>Update Address Status Change</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Status Change Info</legend>
		<input name="id" type="hidden" value="<?php echo $this->addressHistory->getId(); ?>" />
		<table>

			<tr><td><label>Street Address Id</label></td>
				<td><?php echo $this->addressHistory->getStreet_address_id(); ?>
                    <input name="addressHistory[street_address_id]" id="addressHistory-street_address_id" value="<?php echo $this->addressHistory->getStreet_address_id(); ?>" type="hidden" />
				</td>
			</tr>
				
			<tr><td><label for="addressHistory-status_code">Status</label></td>
				<td><select name="addressHistory[status_code]" id="addressHistory-status_code">
                     <option></option>
                     <?php
                         foreach($status in $statuses){
	                         $selected = $status->getStatus_code() == $this->addressHistory->getStatus_code()? "selected=\"selected\"":"";
	                         echo "<option $selected value=\"{$status->getStatus_code()\">$status</option>";
                         }
                  </select> 
				</td>
			</tr>
				
			<tr><td><label for="addressHistory-start_date">Start Date</label></td>
				<td><input name="addressHistory[start_date]" id="addressHistory-start_date" value="<?php echo $this->addressHistory->getStart_date('n/j/Y'); ?>" size="10" maxlength="10" />
				</td>
			</tr>
			<tr><td><label for="addressHistory-end_date">End Date</label></td>
				<td><input name="addressHistory[end_date]" id="addressHistory-end_date" value="<?php echo $this->addressHistory->getEnd_date(); ?>" size="10" maxlength="10" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>