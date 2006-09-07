<?php
/*
	$this->streetName
*/
?>
<h1>Edit Street Name</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Name Info</legend>
	<input name="streetName_id" type="hidden" value="<?php echo $this->streetName->getId(); ?>" />
	<div><label>Type
				<select name="streetName[streetNameType_id]" id="streetName-streetNameType_id">
				<?php
					$types = new StreetNameTypeList();
					$types->find();
					foreach($types as $type)
					{
						if ($this->streetName->getStreetNameType_id() == $type->getId())
							{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
						else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
					}
				?>
			</select>
	</label></div>
	<button type="submit">Submit</button>
</fieldset>
</form>