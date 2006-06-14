	<table>
	<tr><td><select name="direction_id" id="direction_id"><option></option>
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
	</table>
