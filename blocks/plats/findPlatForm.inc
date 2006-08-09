<h2>Find a Plat</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Plat Info</legend>
	<table>
	<tr><td><label for="plat-id">ID</label></td>
		<td><input name="plat[id]" id="plat-id" value="<?php if(isset($_GET['plat']['id'])) echo $_GET['plat']['id']; ?>" /></td></tr>
	<tr><td><label for="plat-name">Name</label></td>
		<td><input name="plat[name]" id="plat-name" value="<?php if (isset($_GET['plat']['name'])) echo $_GET['plat']['name']; ?>" /></td></tr>
	<tr><td><label for="plat-township_id">Township</label></td>
		<td><select name="plat[township_id]" id="plat-township_id"><option></option>
			<?php
				$townships = new TownshipList();
				$townships->find();
				foreach($townships as $township)
				{
					if (isset($_GET['plat']['township_id']) && $_GET['plat']['township_id']==$township->getId())
						{ echo "<option value=\"{$township->getId()}\" selected=\"selected\">{$township->getName()}</option>"; }
					else { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-platType_id">Plat Type</label></td>
		<td><select name="plat[platType_id]" id="plat-platType_id"><option></option>
			<?php
				$platTypes = new PlatTypeList();
				$platTypes->find();
				foreach($platTypes as $platType)
				{
					if (isset($_GET['plat']['platType_id']) && $_GET['plat']['platType_id']==$platType->getId())
						{ echo "<option value=\"{$platType->getId()}\" selected=\"selected\">{$platType->getType()}</option>"; }
					else { echo "<option value=\"{$platType->getId()}\">{$platType->getType()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat-cabinet">Cabinet</label></td>
		<td><input name="plat[cabinet]" id="plat-cabinet" value="<?php if(isset($_GET['plat']['cabinet'])) echo $_GET['plat']['cabinet']; ?>" /></td></tr>
	<tr><td><label for="plat-envelope">Envelope</label></td>
		<td><input name="plat[envelope]" id="plat-envelope" value="<?php if(isset($_GET['plat']['envelope'])) echo $_GET['plat']['envelope']; ?>" /></td></tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>