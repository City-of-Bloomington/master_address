<?php
/*
	$this->name
*/
?>
<h1>Edit Name:<?php echo $this->name->getId(); ?></h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Name</legend>
	<input name="name_id" type="hidden" value="<?php echo $this->name->getId(); ?>" />

	<table>
	<tr><td><label for="name-name">Name</label></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList();
				$directionList->find();
				foreach($directionList as $direction)
				{
					if ($this->name->getDirection_id() != $direction->getId()) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
			<input name="name[name]" id="name-name" value="<?php echo $this->name->getName(); ?>" />
			<select name="name[suffix_id]" id="name-suffix_id"><option></option>
			<?php
				$suffixList = new SuffixList();
				$suffixList->find();
				foreach($suffixList as $suffix)
				{
					if ($this->name->getSuffix_id() != $suffix->getId()) { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
					else { echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
				}
			?>
			</select>
			<select name="name[postDirection_id]" id="name-postDirection_id"><option></option>
			<?php
				# This reuses the directionList we created for the $this->name->directionCode
				foreach($directionList as $direction)
				{
					if ($this->name->getPostDirection_id() != $direction->getId()) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="name-town_id">Town</label></td>
		<td><select name="name[town_id]" id="name-town_id">
			<?php
				$townList = new TownList();
				$townList->find();
				foreach($townList as $town)
				{
					if ($this->name->getTown_id() != $town->getId()) { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
					else { echo "<option value=\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="name-startDate-mon">Start Date</label></td>
		<td><select name="name[startDate][mon]" id="name-startDate-mon"><option></option>
			<?php
				$startDate = $this->name->dateStringToArray($this->name->getStartDate());

				for($i=1; $i<=12; $i++)
				{
					if ($i!=$startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="name[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="name[startDate][year]" id="name-startDate-year" size="4" maxlength="4" value="<?php echo $startDate['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="name-endDate-mon">End Date</label></td>
		<td><select name="name[endDate][mon]" id="name-endDate-mon"><option></option>
			<?php
				$endDate = $this->name->dateStringToArray($this->name->getEndDate());

				for($i=1; $i<=12; $i++)
				{
					if ($i!=$endDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="name[endDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$endDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="name[endDate][year]" id="name-endDate-year" size="4" maxlength="4" value="<?php echo $endDate['year']; ?>" />
		</td>
	</tr>
	</table>
	<div><label for="notes">Notes</label></div>
	<textarea name="notes" id="notes" rows="3" cols="60"><?php echo $this->name->getNotes(); ?></textarea>

</fieldset>
<fieldset>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='viewName.php?id=<?php echo $this->name->getId(); ?>';">Cancel</button>
</fieldset>
</form>