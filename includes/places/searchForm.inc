<form method="get" action="home.php">
<fieldset><legend>Place</legend>
	<table>
	<tr><td><label for="id">Place ID</label></td>
		<td><input name="id" id="id" /></td></tr>
	<tr><td><label for="name">Name</label></td>
		<td><input name="name" id="name" /></td></tr>
	<tr><td><label for="township_id">Township</label></td>
		<td><select name="township_id" id="township_id"><option></option>
			<?php
				$list = new TownshipList();
				$list->find();
				foreach($list as $township) { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="jurisdiction_id">Jurisdiction</label></td>
		<td><select name="jurisdiction_id" id="jurisdiction_id"><option></option>
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach($list as $jurisdiction) { echo "<option value=\"{$jurisdiction->getId()}\">{$jurisdiction->getName()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="trashPickupDay_id">Trash Pickup Day</label></td>
		<td><select name="trashPickupDay_id" id="trashPickupDay_id"><option></option>
			<?php
					$days = new TrashPickupDayList();
					$days->find();
					foreach($days as $day) { echo "<option value=\"{$day->getId()}\">{$day->getDay()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="trashLargeItemPickupDay_id">Large Item Pickup</label></td>
		<td><select name="trashLargeItemPickupDay_id" id="trashLargeItemPickupDay_id"><option></option>
			<?php
					foreach($days as $day) { echo "<option value=\"{$day->getId()}\">{$day->getDay()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="recyclingPickupWeek_id">Recycling Week</label></td>
		<td><select name="recyclingPickupWeek_id" id="recyclingPickupWeek_id"><option></option>
			<?php
				$list = new RecyclingPickupWeekList();
				$list->find();
				foreach($list as $week) { echo "<option value=\"{$week->getId()}\">{$week->getWeek()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="mailable_yes">Mailable</label></td>
		<td><label><input type="radio" name="mailable" id="mailable_yes" value="1" />Yes</label>
			<label><input type="radio" name="mailable" id="mailable_no" value="0" />No</label>
		</td>
	</tr>
	<tr><td><label for="livable_yes">Livable</label></td>
		<td><label><input type="radio" name="livable" id="livable_yes" value="1" />Yes</label>
			<label><input type="radio" name="livable" id="livable_no" value="0" />No</label>
		</td>
	</tr>
	<tr><td><label for="section">Section</label></td>
		<td><input name="section" id="section" /></td></tr>
	<tr><td><label for="quarterSection">Quarter Section</label></td>
		<td><select name="quarterSection" id="quarterSection"><option></option>
				<option>NE</option>
				<option>NW</option>
				<option>SE</option>
				<option>SW</option>
			</select>
		</td>
	</tr>
	<tr><td><label for="placeType_id">Type</label></td>
		<td><select name="placeType_id"><option></option>
			<?php
				$list = new PlaceTypeList();
				$list->find();
				foreach($list as $type) { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>
<?php include(APPLICATION_HOME."/includes/places/searchResults.inc"); ?>