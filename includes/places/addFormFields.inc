<fieldset><legend>Place Info</legend>
	<table>
	<tr><td><label for="place-jurisdiction_id" class="required">Jurisdiction</label></td>
		<td><select name="place[jurisdiction_id]" id="place-jurisdiction_id">
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach($list as $jurisdiction) { echo "<option value=\"{$jurisdiction->getId()}\">{$jurisdiction->getName()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-status_id" class="required">Status</label></td>
		<td><select name="place[status_id]" id="place-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status) { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-name">Name</label></td>
		<td><input name="place[name]" id="place-name" /></td></tr>
	<tr><td><label for="place-township_id">Township</label></td>
		<td><select name="place[township_id]" id="place-township_id">
			<?php
				$list = new TownshipList();
				$list->find();
				foreach($list as $township) { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-trashPickupDay_id">Trash Pickup Day</label></td>
		<td><select name="place[trashPickupDay_id]" id="place-trashPickupDay_id"><option></option>
			<?php
					$days = new TrashPickupDayList();
					$days->find();
					foreach($days as $day) { echo "<option value=\"{$day->getId()}\">{$day->getDay()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-trashLargeItemPickupDay_id">Large Item Pickup</label></td>
		<td><select name="place[trashLargeItemPickupDay_id]" id="place-trashLargeItemPickupDay_id"><option></option>
			<?php
					foreach($days as $day) { echo "<option value=\"{$day->getId()}\">{$day->getDay()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-recyclingPickupWeek_id">Recycling Week</label></td>
		<td><select name="place[recyclingPickupWeek_id]" id="place-recyclingPickupWeek_id"><option></option>
			<?php
				$list = new RecyclingPickupWeekList();
				$list->find();
				foreach($list as $week) { echo "<option value=\"{$week->getId()}\">{$week->getWeek()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-mailable_yes">Mailable</label></td>
		<td><label><input type="radio" name="place[mailable]" id="place-mailable_yes" value="1" />Yes</label>
			<label><input type="radio" name="place[mailable]" id="place-mailable_no" value="0" />No</label>
		</td>
	</tr>
	<tr><td><label for="place-livable_yes">Livable</label></td>
		<td><label><input type="radio" name="place[livable]" id="place-livable_yes" value="1" />Yes</label>
			<label><input type="radio" name="place[livable]" id="place-livable_no" value="0" />No</label>
		</td>
	</tr>
	<tr><td><label for="place-section">Section</label></td>
		<td><input name="place[section]" id="place-section" /></td></tr>
	<tr><td><label for="place-quarterSection">Quarter Section</label></td>
		<td><select name="place[quarterSection]" id="place-quarterSection"><option></option>
				<option>NE</option>
				<option>NW</option>
				<option>SE</option>
				<option>SW</option>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-class">Class</label></td>
		<td><input name="place[class]" id="place-class" /></td></tr>
	<tr><td><label for="place-placeType_id">Type</label></td>
		<td><select name="place[placeType_id]" id="place-placeType_id">
			<?php
				$list = new PlaceTypeList();
				$list->find();
				foreach($list as $type) { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-censusBlockFIPSCode">Census Block FIPS Code</label></td>
		<td><input name="place[censusBlockFIPSCode]" id="place-censusBlockFIPSCode" /></td></tr>
	<tr><td><label for="place-statePlaneX">State Plane X</label></td>
		<td><input name="place[statePlaneX]" id="place-statePlaneX" /></td></tr>
	<tr><td><label for="place-statePlaneY">State Plane X</label></td>
		<td><input name="place[statePlaneY]" id="place-statePlaneY" /></td></tr>
	<tr><td><label for="place-latitude">Latitude</label></td>
		<td><input name="place[latitude]" id="place-latitude" /></td></tr>
	<tr><td><label for="place-longitude">Longitude</label></td>
		<td><input name="place[longitude]" id="place-longitude" /></td></tr>
	<tr><td><label for="place-startDate-mon">Start Date</label></td>
		<td><select name="place[startDate][mon]" id="place-startDate-mon"><option></option>
			<?php
				$now = getdate();
				for($i=1; $i<=12; $i++)
				{
					if ($i!=$now['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="place[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$now['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="place[startDate][year]" id="place-startDate-year" size="4" maxlength="4" value="<?php echo $now['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="place-endDate-mon">End Date</label></td>
		<td><select name="place[endDate][mon]" id="place-endDate-mon"><option></option>
				<?php for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; } ?>
			</select>
			<select name="place[endDate][mday]"><option></option>
				<?php for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; } ?>
			</select>
			<input name="place[endDate][year]" id="place-endDate-year" size="4" maxlength="4" />
		</td>
	</tr>
	</table>
</fieldset>