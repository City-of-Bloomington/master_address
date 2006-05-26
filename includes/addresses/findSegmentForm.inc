<?php
/*
	$_GET variables:	number
						suffix

						direction_id
						street_name
						suffix_id
						postDirection_id
*/
?>
<form method="get" action="findSegmentForm.php">
<fieldset><legend>Find a segment</legend>
	<table>
	<tr><th><label for="number">Number</label></th>
		<th><label for="suffix">Suffix</label></th>
		<th><label for="name">Street Name</label></th>
	</tr>
	<tr><td><input name="number" id="number" size="5" value="<?php if(isset($_GET['number'])) echo $_GET['number']; ?>" /></td>
		<td><input name="suffix" id="suffix" size="3" value="<?php if(isset($_GET['suffix'])) echo $_GET['suffix']; ?>" /></td>
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
	</tr>
	</table>
</fieldset>
</form>
