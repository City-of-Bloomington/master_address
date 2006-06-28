<?php
/*
	Required variables:		return_url
							error_url
*/
?>
	<h2>Add a new Name</h2>
	<form method="post" action="<?php echo BASE_URL."/names/addName.php"; ?>">
	<fieldset><legend>Name Info</legend>
		<input name="return_url" type="hidden" value="<?php echo $return_url; ?>" />
		<input name="error_url" type="hidden" value="<?php echo $error_url; ?>" />
		<table>
		<tr><td><label for="name-name">Name</label></td>
			<td><select name="name[direction_id]" id="name-direction_id"><option></option>
				<?php
					$directionList = new DirectionList($PDO);
					$directionList->find();
					foreach($directionList as $direction)
					{
						if (isset($_GET['name']['direction_id']) && $_GET['name']['direction_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
				<input name="name[name]" id="name-name" value="<?php if(isset($_GET['name']['name'])) echo $_GET['name']['name']; ?>" />
				<select name="name[suffix_id]" id="name-suffix_id"><option></option>
				<?php
					$list = new SuffixList($PDO);
					$list->find();
					foreach($list as $suffix)
					{
						if (isset($_GET['name']['suffix_id']) && $_GET['name']['suffix_id']==$suffix->getId()) { echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
						else { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
					}
				?>
				</select>
				<select name="name[postDirection_id]" id="name-postDirection_id"><option></option>
				<?php
					foreach($directionList as $direction)
					{
						if (isset($_GET['name']['postDirection_id']) && $_GET['name']['postDirection_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="name-town_id">Town</label></td>
			<td><select name="name[town_id]" id="name-town_id">
				<?php
					$towns = new TownList($PDO);
					$towns->find();
					foreach($towns as $town)
					{
						if (isset($_GET['name']['town_id']) && $_GET['name']['town_id']==$town->getId()) { echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
						else { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
					}
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
		<div><label for="name-notes">Notes</label></div>
		<div><textarea name="name[notes]" id="name-notes" rows="3" cols="60"><?php if(isset($_GET['name']['notes'])) echo $_GET['name']['notes']; ?></textarea></div>
	</fieldset>

	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
