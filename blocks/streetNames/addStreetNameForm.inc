<?php
/*
	$this->name
	$this->street
*/
?>
<h1>New Street Name</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Street Name Info</legend>
	<input name="streetName[name_id]" type="hidden" value="<?php echo $this->name->getId(); ?>" />
	<input name="streetName[street_id]" type="hidden" value="<?php echo $this->street->getId(); ?>" />
	<div><label>Type
				<select name="streetName[streetNameType_id]" id="streetName-streetNameType_id">
				<?php
					$types = new StreetNameTypeList();
					$types->find();
					foreach($types as $type)
					{
						if (isset($_POST['streetName']['streetNameType_id']) && $_POST['streetName']['streetNameType_id']==$type->getId())
							{ echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
						else { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
					}
				?>
			</select>
	</label></div>
	<button type="submit">Submit</button>
</fieldset>
</form>