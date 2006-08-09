<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Place</legend>
	<table>
	<tr><td><label for="place-id">Place ID</label></td>
		<td><input name="place[id]" id="place-id" value="<?php if(isset($_GET['place']['id'])) echo $_GET['place']['id']; ?>" /></td></tr>
	<tr><td><label for="place-name">Name</label></td>
		<td><input name="place[name]" id="place-name" value="<?php if(isset($_GET['place']['name'])) echo $_GET['place']['name']; ?>" /></td></tr>
	<tr><td><label for="place-township_id">Township</label></td>
		<td><select name="place[township_id]" id="place-township_id"><option></option>
			<?php
				$list = new TownshipList();
				$list->find();
				foreach($list as $township)
				{
					if (isset($_GET['place']['township_id']) && $_GET['place']['township_id']==$township->getId())
						{ echo "<option value=\"{$township->getId()}\" selected=\"selected\">{$township->getName()}</option>"; }
					else { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }

				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-jurisdiction_id">Jurisdiction</label></td>
		<td><select name="place[jurisdiction_id]" id="place-jurisdiction_id"><option></option>
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach($list as $jurisdiction)
				{
					if (isset($_GET['place']['jurisdiction_id']) && $_GET['place']['jurisdiction_id']==$jurisdiction->getId())
						{ echo "<option value=\"{$jurisdiction->getId()}\" selected=\"selected\">{$jurisdiction->getName()}</option>"; }
					else { echo "<option value=\"{$jurisdiction->getId()}\">{$jurisdiction->getName()}</option>"; }
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
						if (isset($_GET['place']['trashPickupDay_id']) && $_GET['place']['trashPickupDay_id']==$day->getId())
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
					if (isset($_GET['place']['recyclingPickupWeek_id']) && $_GET['place']['recyclingPickupWeek_id']==$week->getId())
						{ echo "<option value=\"{$week->getId()}\" selected=\"selected\">{$week->getWeek()}</option>"; }
					else { echo "<option value=\"{$week->getId()}\">{$week->getWeek()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-mailable-yes">Mailable</label></td>
		<td><label><input type="radio" name="place[mailable]" id="place-mailable-yes" value="1" <?php if(isset($_GET['place']['mailable']) && $_GET['place']['mailable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="place[mailable]" id="place-mailable-no" value="0" <?php if(isset($_GET['place']['mailable']) && $_GET['place']['mailable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="place-livable-yes">Livable</label></td>
		<td><label><input type="radio" name="place[livable]" id="place-livable-yes" value="1" <?php if(isset($_GET['place']['livable']) && $_GET['place']['livable']==1) echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="place[livable]" id="place-livable-no" value="0" <?php if(isset($_GET['place']['livable']) && $_GET['place']['livable']==0) echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="place-section">Section</label></td>
		<td><input name="place[section]" id="place-section" value="<?php if(isset($_GET['place']['section'])) echo $_GET['place']['section']; ?>" /></td></tr>
	<tr><td><label for="place-quarterSection">Quarter Section</label></td>
		<td><select name="place[quarterSection]" id="place-quarterSection"><option></option>
			<?php
				$list = new QuarterSectionList();
				$list->find();
				foreach($list as $quarterSection)
				{
					if (isset($_GET['place']['quarterSection']) && $_GET['place']['quarterSection']==$quarterSection)
						{ echo "<option selected=\"selected\">$quarterSection</option>"; }
					else { echo "<option>$quarterSection</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-placeType_id">Type</label></td>
		<td><select name="place[placeType_id]" id="place-placeType_id"><option></option>
			<?php
				$list = new PlaceTypeList();
				$list->find();
				foreach($list as $type)
				{
					if (isset($_GET['place']['placeType_id']) && $_GET['place']['placeType_id']==$type->getId())
						{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
					else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }

				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="place-plat_id">Plat ID</label></td>
		<td><input name="place[plat_id]" id="place-plat_id" value="<?php if(isset($_GET['place']['plat_id'])) echo $_GET['place']['plat_id']; ?>" /></td></tr>
	<tr><td><label for="place-lotNumber">Lot Number</label></td>
		<td><input name="place[lotNumber]" id="place-lotNumber" value="<?php if(isset($_GET['place']['lotNumber'])) echo $_GET['place']['lotNumber']; ?>" /></td></tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>