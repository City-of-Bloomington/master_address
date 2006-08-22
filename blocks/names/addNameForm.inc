<h2>Add a new Name</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Name Info</legend>
	<table>
	<tr><td><label for="name-name">Name</label></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList();
				$directionList->find();
				foreach($directionList as $direction)
				{
					if (isset($_POST['name']['direction_id']) && $_POST['name']['direction_id']==$direction->getId())
						{ echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
			<input name="name[name]" id="name-name" value="<?php if(isset($_POST['name']['name'])) echo $_POST['name']['name']; ?>" />
			<select name="name[suffix_id]" id="name-suffix_id"><option></option>
			<?php
				$list = new SuffixList();
				$list->find();
				foreach($list as $suffix)
				{
					if (isset($_POST['name']['suffix_id']) && $_POST['name']['suffix_id']==$suffix->getId())
						{ echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
					else { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
				}
			?>
			</select>
			<select name="name[postDirection_id]" id="name-postDirection_id"><option></option>
			<?php
				foreach($directionList as $direction)
				{
					if (isset($_POST['name']['postDirection_id']) && $_POST['name']['postDirection_id']==$direction->getId())
						{ echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="name-town_id">Town</label></td>
		<td><select name="name[town_id]" id="name-town_id">
			<?php
				$towns = new TownList();
				$towns->find();
				foreach($towns as $town)
				{
					if (isset($_POST['name']['town_id']) && $_POST['name']['town_id']==$town->getId())
						{ echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
					else { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="name-startDate-mon">Start Date</label></td>
		<td><select name="name[startDate][mon]" id="name-startDate-mon">
			<?php
				$startDate = isset($_POST['name']['startDate']) ? $_POST['name']['startDate'] : getdate();
				for($i=1; $i<=12; $i++)
				{
					if ($i != $startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="name[startDate][mday]">
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i != $startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="name[startDate][year]" size="4" maxlength="4" value="<?php echo $startDate['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="name-endDate-mon">End Date</label></td>
		<td><select name="name[endDate][mon]" id="name-endDate-mon"><option></option>
				<?php
					if (isset($_POST['name']['endDate'])) { $endDate = $_POST['name']['endDate']; }
					else { $endDate = array("year"=>"","mon"=>"","mday"=>""); }
					for($i=1; $i<=12; $i++)
					{
						if ($i != $endDate['mon']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
			</select>
			<select name="name[endDate][mday]"><option></option>
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($i != $endDate['mday']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
			</select>
			<input name="name[endDate][year]" size="4" maxlength="4" />
		</td>
	</tr>
	</table>
	<div><label for="name-notes">Notes</label></div>
	<div><textarea name="name[notes]" id="name-notes" rows="3" cols="60"><?php if(isset($_POST['name']['notes'])) echo $_POST['name']['notes']; ?></textarea></div>
</fieldset>

<fieldset>
	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>