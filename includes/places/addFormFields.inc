<fieldset><legend>Place Info</legend>
	<table>
	<tr><td><label for="place-jurisdiction_id" class="required">Jurisdiction</label></td>
		<td><select name="place[jurisdiction_id]" id="place-jurisdiction_id">
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach($list as $jurisdiction)
				{
					if (isset($_POST['place']['jurisdiction_id']) && $_POST['place']['jurisdiction_id']==$jurisdiction->getId())
						{ echo "<option value=\"{$jurisdiction->getId()}\" selected=\"selected\">{$jurisdiction->getName()}</option>"; }
					else { echo "<option value=\"{$jurisdiction->getId()}\">{$jurisdiction->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-status_id" class="required">Status</label></td>
		<td><select name="place[status_id]" id="place-status_id">
			<?php
				$list = new StatusList('place');
				$list->find();
				foreach($list as $status)
				{
					if (isset($_POST['place']['status_id']) && $_POST['place']['status_id']==$status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-name">Name</label></td>
		<td><input name="place[name]" id="place-name" value="<?php if(isset($_POST['place']['name'])) echo $_POST['place']['name']; ?>" /></td></tr>
	<tr><td><label for="place-township_id">Township</label></td>
		<td><select name="place[township_id]" id="place-township_id">
			<?php
				$list = new TownshipList();
				$list->find();
				foreach($list as $township)
				{
					if (isset($_POST['place']['township_id']) && $_POST['place']['township_id']==$township->getId())
						{ echo "<option value=\"{$township->getId()}\" selected=\"selected\">{$township->getName()}</option>"; }
					else { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-trashPickupDay_id">Trash Pickup Day</label></td>
		<td><select name="place[trashPickupDay_id]" id="place-trashPickupDay_id"><option></option>
			<?php
					$days = new TrashPickupDayList();
					$days->find();
					foreach($days as $day)
					{
						if (isset($_POST['place']['trashPickupDay_id']) && $_POST['place']['trashPickupDay_id']==$day->getId())
						{ echo "<option value=\"{$day->getId()}\" selected=\"selected\">{$day->getDay()}</option>"; }
						else { echo "<option value=\"{$day->getId()}\">{$day->getDay()}</option>"; }
					}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-recyclingPickupWeek_id">Recycling Week</label></td>
		<td><select name="place[recyclingPickupWeek_id]" id="place-recyclingPickupWeek_id"><option></option>
			<?php
				$list = new RecyclingPickupWeekList();
				$list->find();
				foreach($list as $week)
				{
					if (isset($_POST['place']['recyclingPickupWeek_id']) && $_POST['place']['recyclingPickupWeek_id']==$week->getId())
					{ echo "<option value=\"{$week->getId()}\" selected=\"selected\">{$week->getWeek()}</option>"; }
					else { echo "<option value=\"{$week->getId()}\">{$week->getWeek()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-mailable_yes">Mailable</label></td>
		<td><label><input type="radio" name="place[mailable]" id="place-mailable_yes" value="1" <?php if(isset($_POST['place']['mailable']) && $_POST['place']['mailable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="place[mailable]" id="place-mailable_no" value="0" <?php if(isset($_POST['place']['mailable']) && $_POST['place']['mailable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="place-livable_yes">Livable</label></td>
		<td><label><input type="radio" name="place[livable]" id="place-livable_yes" value="1" <?php if(isset($_POST['place']['livable']) && $_POST['place']['livable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="place[livable]" id="place-livable_no" value="0" <?php if(isset($_POST['place']['livable']) && $_POST['place']['livable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="place-section">Section</label></td>
		<td><input name="place[section]" id="place-section" value="<?php if(isset($_POST['place']['section'])) echo $_POST['place']['section']; ?>" /></td></tr>
	<tr><td><label for="place-quarterSection">Quarter Section</label></td>
		<td><select name="place[quarterSection]" id="place-quarterSection"><option></option>
			<?php
				$list = new QuarterSectionList();
				$list->find();
				foreach($list as $section)
				{
					if (isset($_POST['place']['quarterSection']) && $_POST['place']['quarterSection']==$section)
						{ echo "<option selected=\"selected\">$section</option>"; }
					else { echo "<option>$section</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-class">Class</label></td>
		<td><input name="place[class]" id="place-class" value="<?php if(isset($_POST['place']['class'])) echo $_POST['place']['class']; ?>" /></td></tr>
	<tr><td><label for="place-placeType_id">Type</label></td>
		<td><select name="place[placeType_id]" id="place-placeType_id">
			<?php
				$list = new PlaceTypeList();
				$list->find();
				foreach($list as $type)
				{
					if (isset($_POST['place']['placeType_id']) && $_POST['place']['placeType_id']==$type->getId())
					{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
					else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-censusBlockFIPSCode">Census Block FIPS Code</label></td>
		<td><input name="place[censusBlockFIPSCode]" id="place-censusBlockFIPSCode" value="<?php if(isset($_POST['place']['censusBlockFIPSCode'])) echo $_POST['place']['censusBlockFIPSCode']; ?>" /></td></tr>
	<tr><td><label for="place-statePlaneX">State Plane X</label></td>
		<td><input name="place[statePlaneX]" id="place-statePlaneX" value="<?php if(isset($_POST['place']['statePlaneX'])) echo $_POST['place']['statePlaneX']; ?>" /></td></tr>
	<tr><td><label for="place-statePlaneY">State Plane X</label></td>
		<td><input name="place[statePlaneY]" id="place-statePlaneY" value="<?php if(isset($_POST['place']['statePlaneY'])) echo $_POST['place']['statePlaneY']; ?>" /></td></tr>
	<tr><td><label for="place-latitude">Latitude</label></td>
		<td><input name="place[latitude]" id="place-latitude" value="<?php if(isset($_POST['place']['latitude'])) echo $_POST['place']['latitude']; ?>" /></td></tr>
	<tr><td><label for="place-longitude">Longitude</label></td>
		<td><input name="place[longitude]" id="place-longitude" value="<?php if(isset($_POST['place']['longitude'])) echo $_POST['place']['longitude']; ?>" /></td></tr>
	<tr><td><label for="place-startDate-mon">Start Date</label></td>
		<td><select name="place[startDate][mon]" id="place-startDate-mon"><option></option>
			<?php
				if (isset($_POST['place']['startDate'])) { $startDate = $_POST['place']['startDate']; }
				else { $startDate = getdate(); }

				for($i=1; $i<=12; $i++)
				{
					if ($i!=$startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="place[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="place[startDate][year]" id="place-startDate-year" size="4" maxlength="4" value="<?php echo $startDate['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="place-endDate-mon">End Date</label></td>
		<td><select name="place[endDate][mon]" id="place-endDate-mon"><option></option>
				<?php
					for($i=1; $i<=12; $i++)
					{
						if (isset($_POST['place']['endDate']) && $_POST['place']['endDate']['mon']==$i)
							{ echo "<option selected=\"selected\">$i</option>"; }
						else { echo "<option>$i</option>"; }
					}
				?>
			</select>
			<select name="place[endDate][mday]"><option></option>
				<?php
					for($i=1; $i<=31; $i++)
					{
						if (isset($_POST['place']['endDate']) && $_POST['place']['endDate']['mday']==$i)
							{ echo "<option selected=\"selected\">$i</option>"; }
						else { echo "<option>$i</option>"; }
					}
				?>
			</select>
			<input name="place[endDate][year]" id="place-endDate-year" size="4" maxlength="4" value="<?php if(isset($_POST['place']['endDate'])) echo $_POST['place']['endDate']['year']; ?>" />
		</td>
	</tr>
	</table>
</fieldset>