<?php
/**
 * @copyright 2009-2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 */
$months = array(1=>'Jan',2=>'Feb',3=>'March',4=>'April',5=>'May',6=>'June',
				7=>'July',8=>'Aug',9=>'Sept',10=>'Oct',11=>'Nov',12=>'Dec');
$firstYear = ChangeLog::getFirstYear(); // get this from the database
$currentYear = date('Y');

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

// Go ahead and load the jurisdictions, since we're going to use them to
// display the form anyway
$jurisdictionList = new JurisdictionList();
$jurisdictionList->find();
if (isset($_REQUEST['jurisdictions'])) {
	$jurisdictions = $_REQUEST['jurisdictions'];
}
else {
	foreach ($jurisdictionList as $jurisdiction) {
		$jurisdictions[] = $jurisdiction->getId();
	}
}
?>
<h1>Activity Report</h1>
<p>
	Using this tool, you can gather data from the change logs in the system.
</p>
<form method="get" action="<?php echo BASE_URL; ?>/reports" id="reportform">
	<fieldset>
		<legend>Presets</legend>
		<ul>
			<li><button type="button" onclick="preset(['Address','Subunit'],['assigned','readdressed','reassigned','corrected','verified']);">
					Address Activity
				</button>
			</li>
			<li><button type="button" onclick="preset(['Street'],['aliased','updated','unretired','changed']);">
					Street Activity
				</button>
			</li>
		</ul>
	</fieldset>

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
							for ($i=$currentYear; $i>=$firstYear; $i--) {
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
							for ($i=$currentYear; $i>=$firstYear; $i--) {
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
							<input type=\"checkbox\" name=\"types[]\" id=\"type_$type\" value=\"$type\" $checked />
						</label>
					</td>
					";
				}
			?>
			</tr>
		</table>
	</fieldset>
	<fieldset><legend>Jurisdictions</legend>
	<ul>
		<?php
			foreach ($jurisdictionList as $jurisdiction) {
				$checked = in_array($jurisdiction->getId(),$jurisdictions) ? 'checked="checked"' : '';
				echo "
				<li>
				<label>
					$jurisdiction
					<input type=\"checkbox\" name=\"jurisdictions[]\"
							id=\"jurisdiction_{$jurisdiction->getId()}\"
							value=\"{$jurisdiction->getId()}\" $checked />
				</label>
				</li>
				";
			}
		?>
	</ul>
	</fieldset>
	<fieldset><legend>Street-only Actions</legend>
		<table>
			<tr>
				<td><label>
						Created
						<input type="checkbox" name="actions[]" id="action_created" value="created"
							<?php echo in_array('created',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Proposed
						<input type="checkbox" name="actions[]" id="action_proposed" value="proposed"
							<?php echo in_array('proposed',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Aliased
						<input type="checkbox" name="actions[]" id="action_aliased" value="aliased"
							<?php echo in_array('aliased',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>
			<tr><td><label>
						Changed
						<input type="checkbox" name="actions[]" id="action_changed" value="changed street name"
							<?php echo in_array('changed street name',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</fieldset>
	<fieldset><legend>Address-only Actions</legend>
		<table>
			<tr><td><label>
						Added
						<input type="checkbox" name="actions[]" id="action_added" value="added"
							<?php echo in_array('added',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Assigned
						<input type="checkbox" name="actions[]" id="action_assigned" value="assigned"
							<?php echo in_array('assigned',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Readdressed
						<input type="checkbox" name="actions[]" id="action_readdressed" value="readdressed"
							<?php echo in_array('readdressed',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>
			<tr><td><label>
						Reassigned
						<input type="checkbox" name="actions[]" id="action_reassigned" value="reassigned"
							<?php echo in_array('reassigned',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Moved
						<input type="checkbox" name="actions[]" id="action_moved" value="moved"
							<?php echo in_array('moved',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td></td>
			</tr>
		</table>
	</fieldset>

	<fieldset><legend>Generic Actions</legend>
		<table>
			<tr><td><label>
						Corrected
						<input type="checkbox" name="actions[]" id="action_corrected" value="corrected"
							<?php echo in_array('corrected',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Updated
						<input type="checkbox" name="actions[]" id="action_updated" value="updated"
							<?php echo in_array('updated',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Verified
						<input type="checkbox" name="actions[]"  id="action_verified" value="verified"
							<?php echo in_array('verified',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
			</tr>
			<tr><td><label>
						Retired
						<input type="checkbox" name="actions[]" id="action_retired" value="retired"
							<?php echo in_array('retired',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td><label>
						Unretired
						<input type="checkbox" name="actions[]" id="action_unretired" value="unretired"
							<?php echo in_array('unretired',$actions) ? 'checked="checked"' : ''; ?> />
					</label>
				</td>
				<td></td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
	</fieldset>
</form>
<script type="text/javascript">
function preset(types,actions) {
	var t = document.getElementsByName('types[]');
	var a = document.getElementsByName('actions[]');
	for (i in t) {
		t[i].checked = false;
	}
	for (i in a) {
		a[i].checked = false;
	}
	for (i in types) {
		id = 'type_' + types[i];
		document.getElementById(id).checked = true;
	}
	for (i in actions) {
		id = 'action_' + actions[i];
		document.getElementById(id).checked = true;
	}
}
</script>
