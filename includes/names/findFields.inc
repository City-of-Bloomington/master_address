<fieldset><legend>Street Name info</legend>
	<table>
	<tr><td><label for="name-name">Name</label></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList();
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
				$list = new SuffixList();
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
		<td><select name="name[town_id]" id="name-town_id"><option></option>
			<?php
				$towns = new TownList();
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
	</table>
</fieldset>