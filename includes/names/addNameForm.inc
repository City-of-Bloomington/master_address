	<form method="post" action="<?php echo BASE_URL."/names/addName.php"; ?>">
	<fieldset><legend>Name Info</legend>
		<table>
		<tr><td><label for="name">Name</label></td>
			<td><select name="direction_id" id="direction_id"><option></option>
				<?php
					$directionList = new DirectionList();
					$directionList->find();
					foreach($directionList as $direction)
					{
						if (isset($_GET['direction_id']) && $_GET['direction_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
				<input name="name" id="name" value="<?php if(isset($_GET['name'])) echo $_GET['name']; ?>" />
				<select name="suffix_id" id="suffix_id"><option></option>
				<?php
					$list = new SuffixList();
					$list->find();
					foreach($list as $suffix)
					{
						if (isset($_GET['suffix_id']) && $_GET['suffix_id']==$suffix->getId()) { echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
						else { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
					}
				?>
				</select>
				<select name="postDirection_id" id="postDirection_id"><option></option>
				<?php
					foreach($directionList as $direction)
					{
						if (isset($_GET['postDirection_id']) && $_GET['postDirection_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="town_id">Town</label></td>
			<td><select name="town_id" id="town_id">
				<?php
					$towns = new TownList();
					$towns->find();
					foreach($towns as $town) { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="startMonth">Start Date</label></td>
			<td><select name="startMonth" id="startMonth">
				<?php
					$now = getdate();
					for($i=1; $i<=12; $i++)
					{
						if ($i != $now['mon']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<select name="startDay">
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($i != $now['mday']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<input name="startYear" size="4" maxlength="4" value="<?php echo $now['year']; ?>" />
			</td>
		</tr>
		<tr><td><label for="endMonth">End Date</label></td>
			<td><select name="endMonth" id="endMonth"><option></option>
					<?php for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<select name="endDay"><option></option>
					<?php for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; } ?>
				</select>
				<input name="endYear" size="4" maxlength="4" />
			</td>
		</tr>
		</table>
		<div><label for="notes">Notes</label></div>
		<div><textarea name="notes" id="notes" rows="3" cols="60"></textarea></div>
	</fieldset>

	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
