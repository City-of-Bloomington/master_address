<fieldset><legend>Address Info</legend>
	<table>
	<tr><td><label for="address-number" class="required">Number</label></td>
		<td><input name="address[number]" id="address-number" value="<?php if(isset($_POST['address']['number'])) echo $_POST['address']['number']; ?>" /><input name="suffix" size="4" /></td></tr>
	<tr><td><label for="address-addressType" class="required">Type</label></td>
		<td><select name="address[addressType]" id="address-addressType">
			<?php
				$list = new AddressTypeList();
				$list->find();
				foreach($list as $type)
				{
					if (isset($_POST['address']['addressType']) && $_POST['address']['addressType']==$type)
					{ echo "<option selected=\"selected\">$type</option>"; }
					else { echo "<option>$type</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-city_id" class="required">City</label></td>
		<td><select name="address[city_id]" id="address-city_id">
			<?php
				$list = new CityList();
				$list->find();
				foreach($list as $city)
				{
					if (isset($_POST['address']['city_id']) && $_POST['address']['city_id']==$city->getId())
					{ echo "<option value=\"{$city->getId()}\" selected=\"selected\">{$city->getName()}</option>"; }
					else { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-zip">Zip</label></td>
		<td><input name="address[zip]" id="address-zip" size="5" maxlength="5" value="<?php if(isset($_POST['address']['zip'])) echo $_POST['address']['zip']; ?>" />
			<input name="address[zipplus4]" id="address-zipplus4" size="4" maxlength="4" value="<?php if(isset($_POST['address']['zipplus4'])) echo $_POST['address']['zipplus4']; ?>" />
		</td></tr>
	<tr><td><label for="address-status_id">Status</label></td>
		<td><select name="address[status_id]" id="address-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if (isset($_POST['address']['status_id']) && $_POST['address']['status_id']==$status->getId())
					{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-active_yes">Active</label></td>
		<td><label><input type="radio" name="address[active]" id="address-active_yes" value="Y" checked="checked" />Yes</label>
			<label><input type="radio" name="address[active]" id="address-active_no" value="N" />No</label>
		</td>
	</tr>
	<tr><td><label for="address-startDate-mon">Start Date</label></td>
		<td><select name="address[startDate][mon]" id="address-startDate-mon"><option></option>
			<?php
				$now = getdate();
				for($i=1; $i<=12; $i++)
				{
					if ($i!=$now['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="address[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$now['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="address[startDate][year]" id="address-startDate-year" size="4" maxlength="4" value="<?php echo $now['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="address-endDate-mon">End Date</label></td>
		<td><select name="address[endDate][mon]" id="address-endDate-mon"><option></option>
				<?php for($i=1; $i<=12; $i++) { echo "<option>$i</option>"; } ?>
			</select>
			<select name="address[endDate][mday]"><option></option>
				<?php for($i=1; $i<=31; $i++) { echo "<option>$i</option>"; } ?>
			</select>
			<input name="address[endDate][year]" id="address-endDate-year" size="4" maxlength="4" />
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="address-notes">Notes</label></div>
			<textarea name="address[notes]" id="address-notes" rows="3" cols="60"></textarea>
		</td>
	</tr>
	</table>
</fieldset>