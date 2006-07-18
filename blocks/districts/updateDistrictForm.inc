<h1>Edit District</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>District</legend>
	<input name="id" type="hidden" value="<?php echo $this->district->getId(); ?>" />

	<table>
	<tr><td><label for="district-name">Name</label></td>
		<td><input name="district[name]" id="district-name" value="<?php echo $this->district->getName(); ?>" /></td></tr>
	<tr><td><label for="district-districtType_id">Type</label></td>
		<td><select name="district[districtType_id]" id="district-districtType_id">
			<?php
				$list = new DistrictTypeList();
				$list->find();
				foreach($list as $type)
				{
					if ($type->getId() != $this->district->getDistrictType_id())
						{ echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
					else { echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
				}
			?>
			</select></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/districts/viewDistrict.php?id=<?php echo $this->district->getId(); ?>';">Cancel</button>
</fieldset>
</form>
