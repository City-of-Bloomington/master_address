<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Find an Address</h1>
	<form method="get" action="findAddressResults.php">
	<fieldset><legend>Address Info</legend>
		<table>
		<tr><th><label for="number">Number</label></th>
			<th><label for="suffix">Suffix</label></th>
			<th><label for="name">Street Name</label></th>
		</tr>
		<tr><td><input name="number" id="number" size="5" /></td>
			<td><input name="suffix" id="suffix" size="3" /></td>
			<td><select name="direction_id" id="direction_id"><option></option>
				<?php
					$directionList = new DirectionList();
					$directionList->find();
					foreach($directionList as $direction) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				?>
				</select>
				<input name="name" id="name" />
				<select name="suffix_id" id="suffix_id"><option></option>
				<?php
					$list = new SuffixList();
					$list->find();
					foreach($list as $suffix) { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
				?>
				</select>
				<select name="postDirection_id" id="postDirection_id"><option></option>
				<?php
					foreach($directionList as $direction) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				?>
				</select>
		</tr>
		</table>

		<table>
		<tr><td><label for="addressType">Type</label></td>
			<td colspan="2">
				<select name="addressType" id="addressType"><option></option>
					<option>STREET</option>
					<option>UTILITY</option>
					<option>TEMPORARY</option>
					<option>FACILITY</option>
					<option>PROPERTY</option>
				</select>
			</td></tr>
		<tr><td><label for="city_id">City</label></td>
			<td colspan="2">
				<select name="city_id" id="city_id"><option></option>
				<?php
					$list = new CityList();
					$list->find();
					foreach($list as $city) { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				?>
				</select>
			</td></tr>
		<tr><td><label for="zip">Zip</label></td>
			<td colspan="2">
				<input name="zip" id="zip" size="5" maxlength="5" />
				<input name="zipplus4" id="zipplus4" size="4" maxlength="4" />
			</td></tr>
		<tr><td><label for="status_id">Status</label></td>
			<td colspan="2">
				<select name="status_id" id="status_id"><option></option>
				<?php
					$list = new StatusList();
					$list->find();
					foreach($list as $status) { echo "<option>{$status->getStatus()}</option>"; }
				?>
				</select>
			</td></tr>
		<tr><td><label for="active_yes">Active</label></td>
			<td colspan="2">
				<label><input type="radio" name="active" id="active_yes" value="Y" />Yes</label>
				<label><input type="radio" name="active" id="active_no" value="N" />No</label>
			</td>
		</tr>
		<tr><td><label for="startMonth">Start Date</label></td>
			<td colspan="2">
				<select name="startMonth" id="startMonth"><option></option>
				<?php
					$now = getdate();
					for($i=1; $i<=12; $i++) { echo "<option selected=\"selected\">$i</option>"; }
				?>
				</select>
				<select name="startDay"><option></option>
				<?php
					for($i=1; $i<=31; $i++) { echo "<option selected=\"selected\">$i</option>"; }
				?>
				</select>
				<input name="startYear" id="startYear" size="4" maxlength="4" />
			</td>
		</tr>
		<tr><td><label for="endMonth">End Date</label></td>
			<td colspan="2">
				<select name="endMonth" id="endMonth"><option></option>
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
		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>