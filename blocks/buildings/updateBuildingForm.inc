<h1>Edit Building</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Building Info</legend>
	<input name="building_id" type="hidden" value="<?php echo $this->building->getId(); ?>" />
	<table>
	<tr><td><label for="building-GISTag">Tag</label></td>
		<td><input name="building[GISTag]" id="building-GISTag" value="<?php echo $this->building->getGISTag(); ?>" /></td></tr>
	<tr><td><label for="building-name">Name</label></td>
		<td><input name="building[name]" id="building-name" value="<?php echo $this->building->getName(); ?>" /></td></tr>
	<tr><td><label for="building-startDate-mon">Start Date</label></td>
		<td><select name="building[startDate][mon]" id="building-startDate-mon"><option></option>
			<?php
				$startDate = $this->building->dateStringToArray($this->building->getStartDate());
				for($i=1; $i<=12; $i++)
				{
					if ($i != $startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="building[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i != $startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="building[startDate][year]" value="<?php echo $startDate['year']; ?>" />
		</td></tr>
	<tr><td><label for="building-endDate-mon">End Date</label></td>
		<td><select name="building[endDate][mon]" id="building-endDate-mon"><option></option>
			<?php
				$endDate = $this->building->dateStringToArray($this->building->getEndDate());
				for($i=1; $i<=12; $i++)
				{
					if ($i != $endDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="building[endDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i != $endDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="building[endDate][year]" value="<?php echo $endDate['year']; ?>" />
	</td></tr>
	<tr><td><label for="building-status_id">Status</label></td>
		<td><select name="building[status_id]" id="building-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if ($this->building->getStatus_id() != $status->getId()) { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/buildings/viewBuilding.php?building_id=<?php echo $this->building->getId(); ?>';">Cancel</button>
</fieldset>
</form>