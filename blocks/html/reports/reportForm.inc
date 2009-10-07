<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 */
$months = array(1=>'Jan',2=>'Feb',3=>'March',4=>'April',5=>'May',6=>'June',
				7=>'July',8=>'Aug',9=>'Sept',10=>'Oct',11=>'Nov',12=>'Dec');
$firstYear = 2009; // get this from the database
$currYear = date('Y');
?>
<h1>Activity Report</h1>
<form method="get" action="<?php echo BASE_URL; ?>/reports">
	<fieldset><legend>Date Range</legend>
		<table>
			<tr><td><label>From</label></td>
				<td><select name="dateFrom[mon]" id="dateFrom-month">
						<option value="">Month</option>
						<?php
							foreach ($months as $key=>$value) {
								echo "<option value=\"$key\">$value</option>";
							}
						?>
					</select>
				</td>
				<td><select name="dateFrom[mday]" id="dateFrom-day">
						<option value="">Day</option>
						<?php
							for ($i=1;$i<32;$i++) {
								echo "<option value=\"$i\">$i</option>";
							}
						?>
					</select>
				</td>
				<td><select name="dateFrom[year]" id="dateFrom-year">
						<option value="">Year</option>
						<?php
							for ($i=$firstYear;$i<$currYear+1;$i++) {
								echo "<option value=\"$i\">$i</option>";
							}
						?>
				</select>
				</td>
			</tr>

			<tr><td><label>To</label></td>
				<td><select name="dateTo[mon]" id="dateTo-month">
						<option value="">Month</option>
						<?php
							foreach ($months as $key=>$value) {
								echo "<option value=\"$key\">$value</option>";
							}
						?>
					</select>
				</td>
				<td><select name="dateTo[mday]" id="dateTo-day">
						<option value="">Day</option>
						<?php
							for ($i=1;$i<32;$i++) {
								echo "<option value=\"$i\">$i</option>";
							}
						?>
					</select>
				</td>
				<td><select name="dateTo[year]" id="dateTo-year">
						<option value="">Year</option>
						<?php
							for ($i=$firstYear;$i<$currYear+1;$i++) {
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
			<tr><td><label>
						Streets
						<input type="checkbox" name="types[]" value="streets" checked="checked" />
					</label>
				</td>
				<td><label>
						Addresses
						<input type="checkbox" name="types[]" value="addresses" checked="checked" />
					</label>
				</td>
				<td><label>
						Subunits
						<input type="checkbox" name="types[]" value="subunits" checked="checked" />
					</label>
				</td>
			</tr>
		</table>
	</fieldse>

	<fieldset><legend>Actions</legend>
		<table>
			<tr><td><label>
						Added
						<input type="checkbox" name="actions[]" value="added" checked="checked" />
					</label>
				</td>
				<td><label>
						Assigned
						<input type="checkbox" name="actions[]" value="assigned" checked="checked" />
					</label>
				</td>
				<td><label>
						Created
						<input type="checkbox" name="actions[]" value="created" checked="checked" />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Corrected
						<input type="checkbox" name="actions[]" value="corrected" checked="checked" />
					</label>
				</td>
				<td><label>
						Verified
						<input type="checkbox" name="actions[]" value="verified" />
					</label>
				</td>
				<td><label>
						Proposed
						<input type="checkbox" name="actions[]" value="proposed" />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Retired
						<input type="checkbox" name="actions[]" value="retired" />
					</label>
				</td>
				<td><label>
						Readdressed
						<input type="checkbox" name="actions[]" value="readdressed" />
					</label>
				</td>
				<td><label>
						Reassigned
						<input type="checkbox" name="actions[]" value="reassigned" />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Unretired
						<input type="checkbox" name="actions[]" value="unretired" />
					</label>
				</td>
				<td><label>
						Moved
						<input type="checkbox" name="actions[]" value="moved" />
					</label>
				</td>
				<td></td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
	</fieldset>
</form>