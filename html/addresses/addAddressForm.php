<?php
/*
	$_GET variables:	street_id
						segment_id
						place_id

						return_url
*/
	verifyUser(array("Administrator","ADDRESS COORDINATOR"));

	# Check for required parameters.  This form willl not work without them
	if (!$_GET['street_id'] || !$_GET['place_id'])
	{
		$_SESSION['errorMessages'][] = new Exception("missingRequiredFields");
		Header("Location: $_GET[return_url]");
		exit();
	}

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$segment = new Segment($PDO,$_GET['segment_id']);
?>
<div id="mainContent">
	<h1>Add Address</h1>
	<h2><?php echo "$_GET[number] $_GET[suffix] {$segment->getFullStreetName()}"; ?></h2>
	<form method="post" action="addAddress.php">
	<fieldset><legend>Address Info</legend>
		<input name="street_id" type="hidden" value="<?php echo $_GET['street_id']; ?>" />
		<input name="place_id" type="hidden" value="<?php echO $_GET['place_id']; ?>" />
		<input name="segment_id" type="hidden" value="<?php if (isset($_GET['segment_id'])) echo $_GET['segment_id']; ?>" />

		<table>
		<tr><td><label for="number">Number</label></td>
			<td><input name="number" id="number" /><input name="suffix" size="4" /></td></tr>
		<tr><td><label for="addressType">Type</label></td>
			<td><select name="addressType" id="addressType">
					<option>STREET</option>
					<option>UTILITY</option>
					<option>TEMPORARY</option>
					<option>FACILITY</option>
					<option>PROPERTY</option>
				</select>
			</td></tr>
		<tr><td><label for="city_id">City</label></td>
			<td><select name="city_id" id="city_id">
				<?php
					$list = new CityList($PDO);
					$list->find();
					foreach($list as $city) { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				?>
				</select>
			</td></tr>
		<tr><td><label for="zip">Zip</label></td>
			<td><input name="zip" id="zip" size="5" maxlength="5" />
				<input name="zipplus4" id="zipplus4" size="4" maxlength="4" />
			</td></tr>
		<tr><td><label for="status_id">Status</label></td>
			<td><select name="status_id" id="status_id">
				<?php
					$list = new StatusList($PDO);
					$list->find();
					foreach($list as $status) { echo "<option>{$status->getStatus()}</option>"; }
				?>
				</select>
			</td></tr>
		<tr><td><label for="active_yes">Active</label></td>
			<td><label><input type="radio" name="active" id="active_yes" value="Y" checked="checked" />Yes</label>
				<label><input type="radio" name="active" id="active_no" value="N" />No</label>
			</td>
		</tr>
		<tr><td><label for="startMonth">Start Date</label></td>
			<td><select name="startMonth" id="startMonth"><option></option>
				<?php
					$now = getdate();
					for($i=1; $i<=12; $i++)
					{
						if ($i!=$now['mon']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<select name="startDay"><option></option>
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($i!=$now['mday']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<input name="startYear" id="startYear" size="4" maxlength="4" value="<?php echo $now['year']; ?>" />
			</td>
		</tr>
		<tr><td><label for="endMonth">End Date</label></td>
			<td><select name="endMonth" id="endMonth"><option></option>
				<?php
					for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; }
				?>
				</select>
				<select name="endDay"><option></option>
				<?php
					for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; }
				?>
				</select>
				<input name="endYear" id="endYear" size="4" maxlength="4" />
			</td>
		</tr>
		<tr><td colspan="2">
				<div><label for="notes">Notes</label></div>
				<textarea name="notes" id="notes" rows="3" cols="60"></textarea>
			</td>
		</tr>
		</table>
	</fieldset>

	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>