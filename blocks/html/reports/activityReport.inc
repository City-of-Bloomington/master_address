<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * 
 */
$months = array('Jan','Feb','March','April','May','June','July','Aug','Sept','Oct','Nov','Dec');
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
				<td><select name="dateFrom[month]" id="dateFrom-month">
					<option value="">Month</option>
					<?php

					foreach($months as $key=>$value){
						echo "<option value=\"$key\">$value</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateFrom[day]" id="dateFrom-day">
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
				<td><select name="dateTo[month]" id="dateTo-month">
					<option value="">Month</option>
					<?php
					foreach($months as $key=>$value){
						echo "<option value=\"$key\">$value</option>";
					}
					?>
				</select>
				</td>
				<td><select name="dateTo[day]" id="dateTo-day">
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
	<fieldset><legend>Actions</legend>
		<table>
			<tr><td colspan="2"><label>Show in Report</label></td></tr>
			
			<tr><td><input type="checkbox" name="show" value="added" checked="checked"></td>
				<td>Added</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="assigned" checked="checked"></td>
				<td>Assigned</td>
			</tr>			
			<tr><td><input type="checkbox" name="show" value="moved"></td>
				<td>Moved to Location</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="readdressed"></td>
				<td>Readdressed</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="reassigned"></td>
				<td>Reassigned</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="retired"></td>
				<td>Retired</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="unretired"></td>
				<td>Unretired</td>
			</tr>
			<tr><td><input type="checkbox" name="show" value="verified"></td>
				<td>Verified</td>
			</tr>			
		</table>

		<button type="submit" class="submit">Submit</button>

	</fieldset>
</form>