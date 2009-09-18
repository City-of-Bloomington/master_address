<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * 
 */
$months = array(1=>'Jan',2=>'Feb',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Aug',9=>'Sept',10=>'Oct',11=>'Nov',12=>'Dec');
$firstYear = 2009; // get this from the database
$currYear = date('Y');
?>

<h1>Activity Report</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Date Range</legend>
		<table>

			<tr><td><label>From</label></td>
                <td>Month</td><td>Day</td><td>Year</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><select name="dateFrom[mon]" id="dateFrom-month">
					<option value="">Month</option>
					<?php

					foreach($months as $key=>$value){
						echo "<option value=\"$key\">$value</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateFrom[mday]" id="dateFrom-day">
					<option value="">Day</option>
					<?php
					for($i=1;$i<32;$i++){
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateFrom[year]" id="dateFrom-year">
					<option value="">Year</option>
					<?php
					for($i=$firstYear;$i<$currYear+1;$i++){
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select>
				</td>						
			</tr>

			<tr><td><label>To</label></td>
                <td>Month</td><td>Day</td><td>Year</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td><select name="dateTo[mon]" id="dateTo-month">
					<option value="">Month</option>
					<?php
					foreach($months as $key=>$value){
						echo "<option value=\"$key\">$value</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateTo[mday]" id="dateTo-day">
					<option value="">Day</option>
					<?php
					for($i=1;$i<32;$i++){
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateTo[year]" id="dateTo-year">
					<option value="">Year</option>
					<?php
					for($i=$firstYear;$i<$currYear+1;$i++){
						echo "<option value=\"$i\">$i</option>";
					}
					?>
				</select>
				</td>						
			</tr>			
		</table>
	</fieldset>
	
	<fieldset><legend>Logs</legend>
		<table>
			<tr><td colspan="2"><label>Logs of</label></td></tr>
			<tr>
				<td><input type="checkbox" name="types[]" value="streets" checked="checked" /></td>
				<td>Streets</td>
				<td><input type="checkbox" name="types[]" value="addresses" checked="checked" /></td>
				<td>Addresses</td>
				<td><input type="checkbox" name="types[]" value="subunits" checked="checked" /></td>
				<td>Subunits</td>				
			</tr>
		</table>
	</fieldse>
			
	<fieldset><legend>Actions</legend>
		<table>
			<tr><td colspan="4"><label>Show in Report</label></td></tr>
			
			<tr><td><input type="checkbox" name="actions[]" value="added" checked="checked" /></td>
				<td>Added</td>
				<td><input type="checkbox" name="actions[]" value="assigned" checked="checked" /></td>
				<td>Assigned</td>
				<td><input type="checkbox" name="actions[]" value="created" checked="checked" /></td>
				<td>Created</td>
			</tr>
			
			<tr><td><input type="checkbox" name="actions[]" value="corrected" checked="checked" /></td>
				<td>Corrected</td>
				<td><input type="checkbox" name="actions[]" value="verified" /></td>
				<td>Verified</td>
				<td><input type="checkbox" name="actions[]" value="proposed" /></td>
				<td>Proposed</td>
			</tr>

			<tr><td><input type="checkbox" name="actions[]" value="retired" /></td>
				<td>Retired</td>
			
				<td><input type="checkbox" name="actions[]" value="readdressed" /></td>
				<td>Readdressed</td>
				<td><input type="checkbox" name="actions[]" value="reassigned" /></td>
				<td>Reassigned</td>
			</tr>

			<tr>
				<td><input type="checkbox" name="actions[]" value="unretired" /></td>
				<td>Unretired</td>
			
				<td><input type="checkbox" name="actions[]" value="moved" /></td>
				<td colspan="2">Moved to Location</td>
				
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>

	</fieldset>
</form>