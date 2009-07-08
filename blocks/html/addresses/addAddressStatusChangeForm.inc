<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$statuses = new AddressStatusList();
$statuses->find();

?>
<h1>Add Address Status Change</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Status Change Info</legend>
		<table>

			<tr><td><label>Street Address ID</label></td>
   
                 <td><?php  echo $this->street_address_id; ?>
   				     <input name="addressStatusChange[street_address_id]" type="hidden" value="<?php echo $this->street_address_id; ?>" />
				</td>
			</tr>
				
			<tr><td><label for="addressStatusChange-status_code">Status</label></td>
				<td><select name="addressStatusChange[status_code]" id="addressStatusChange-status_code" />
                     <option></option>
                     <?php
                         foreach($status in $statuses){
	                           echo "<option value=\"{$status->getStatus_code()\">$status</option>";
                         }
                  </select> 
				</td>
			</tr>
				
			<tr><td><label for="addressStatusChange-start_date">Start Date</label></td>
				<td><input name="addressStatusChange[start_date]" id="addressStatusChange-start_date" size="10" maxlength="10" />
				</td>
			</tr>
			<tr><td><label for="addressStatusChange-end_date">End Date</label></td>
				<td><input name="addressStatusChange[end_date]" id="addressStatusChange-end_date" size="10" maxlength="10" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>