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

// Set up all the default values or read from what the user requested
$dateFrom = isset($_REQUEST['dateFrom'])
			? $_REQUEST['dateFrom']
			: array('mon'=>'','mday'=>'','year'=>'');
$dateTo = isset($_REQUEST['dateTo'])
			? $_REQUEST['dateTo']
			: array('mon'=>'','mday'=>'','year'=>'');
$types = isset($_REQUEST['types'])
		? $_REQUEST['types']
		: array_keys(ChangeLog::$logTables);
$actions = isset($_REQUEST['actions'])
			? $_REQUEST['actions']
			: array('added','assigned','created','corrected');
?>
<h1>Activity Report</h1>
<form method="get" action="<?php echo BASE_URL; ?>/reports">
	<fieldset><legend>Date Range</legend>
		<table>
			<tr><td><label>From</label></td>
				<td><select name="dateFrom[mon]" id="dateFrom-month">
						<option value="">Month</option>
						<?php
							foreach ($months as $monthNumber=>$monthName) {
								$selected = $dateFrom['mon']==$monthNumber ? 'selected="selected"' : '';
								echo "<option value=\"$monthNumber\" $selected>$monthName</option>";
							}
						?>
					</select>
					<select name="dateFrom[mday]" id="dateFrom-day">
						<option value="">Day</option>
						<?php
							for ($i=1;$i<32;$i++) {
								$selected = $dateFrom['mday']==$i ? 'selected="selected"' : '';
								echo "<option value=\"$i\" $selected>$i</option>";
							}
						?>
					</select>
					<select name="dateFrom[year]" id="dateFrom-year">
						<option value="">Year</option>
						<?php
							for ($i=$firstYear; $i<$currYear+1; $i++) {
								$selected = $dateFrom['year']==$i ? 'selected="selected"' : '';
								echo "<option value=\"$i\" $selected>$i</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label>To</label></td>
				<td><select name="dateTo[mon]" id="dateTo-month">
						<option value="">Month</option>
						<?php
							foreach ($months as $monthNumber=>$monthName) {
								$selected = $dateTo['mon']==$monthNumber ? 'selected="selected"' : '';
								echo "<option value=\"$monthNumber\" $selected>$monthName</option>";
							}
						?>
					</select>
					<select name="dateTo[mday]" id="dateTo-day">
						<option value="">Day</option>
						<?php
							for ($i=1; $i<32; $i++) {
								$selected = $dateTo['mday']==$i ? 'selected="selected"' : '';
								echo "<option value=\"$i\" $selected>$i</option>";
							}
						?>
					</select>
					<select name="dateTo[year]" id="dateTo-year">
						<option value="">Year</option>
						<?php
							for ($i=$firstYear; $i<$currYear+1; $i++) {
								$selected = $dateTo['year']==$i ? 'selected="selected"' : '';
								echo "<option value=\"$i\" $selected>$i</option>";
							}
						?>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset><legend>Logs</legend>
		<table>
			<tr>
			<?php
				foreach (ChangeLog::$logTables as $type=>$data) {
					$checked = in_array($type,$types) ? 'checked="checked"' : '';
					echo "
					<td><label>
							$type
							<input type=\"checkbox\" name=\"types[]\" value=\"$type\" $checked />
						</label>
					</td>
					";
				}
			?>
			</tr>
		</table>
	</fieldse>

	<fieldset><legend>Actions</legend>
		<table>
			<tr><td><label>
						Added
						<input type="checkbox" name="actions[]" value="added"
							<?php echo in_array('added',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Assigned
						<input type="checkbox" name="actions[]" value="assigned"
							<?php echo in_array('assigned',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Created
						<input type="checkbox" name="actions[]" value="created"
							<?php echo in_array('created',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Corrected
						<input type="checkbox" name="actions[]" value="corrected"
							<?php echo in_array('corrected',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Verified
						<input type="checkbox" name="actions[]" value="verified"
							<?php echo in_array('verified',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Proposed
						<input type="checkbox" name="actions[]" value="proposed"
							<?php echo in_array('proposed',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Retired
						<input type="checkbox" name="actions[]" value="retired"
							<?php echo in_array('retired',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Readdressed
						<input type="checkbox" name="actions[]" value="readdressed"
							<?php echo in_array('readdressed',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Reassigned
						<input type="checkbox" name="actions[]" value="reassigned"
							<?php echo in_array('reassigned',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>

			<tr><td><label>
						Unretired
						<input type="checkbox" name="actions[]" value="unretired"
							<?php echo in_array('unretired',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Moved
						<input type="checkbox" name="actions[]" value="moved"
							<?php echo in_array('moved',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td></td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
	</fieldset>
</form>