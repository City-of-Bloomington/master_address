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
		<td><select name="town_id"><option></option>
			<?php
				$towns = new TownList();
				$towns->find();
				foreach($towns as $town)
				{
					if (isset($_GET['town_id']) && $_GET['town_id']==$town->getId()) { echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
					else { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	</table>
