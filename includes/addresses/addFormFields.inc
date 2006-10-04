<fieldset><legend>Address Info</legend>
	<table>
	<tr><td><label for="address-number" class="required">Number</label></td>
		<td><input name="address[number]" id="address-number" value="<?php if(isset($_POST['address']['number'])) echo $_POST['address']['number']; ?>" /><input name="suffix" size="4" /></td></tr>
	<tr><td><label for="address-addressType" class="required">Type</label></td>
		<td><select name="address[addressType]" id="address-addressType" onchange="updatePlaceForm(this);"><option>STREET</option>
			<?php
				$addressType = isset($_POST['address']['addressType']) ? $_POST['address']['addressType'] : "STREET";

				$list = new AddressTypeList();
				$list->find();
				foreach($list as $type)
				{
					if ($addressType == $type) { echo "<option selected=\"selected\">$type</option>"; }
					else { echo "<option>$type</option>"; }
				}
			?>
			</select>
			<script type="text/javascript">
			function updatePlaceForm(select)
			{
				if (document.getElementById('place-jurisdiction_id'))
				{
					var mailable_yes = document.getElementById('place-mailable_yes');
					var mailable_no = document.getElementById('place-mailable_no');
					var livable_yes = document.getElementById('place-livable_yes');
					var livable_no = document.getElementById('place-livable_no');

					if (select.options[select.selectedIndex].text == "STREET")
					{
						mailable_yes.checked = true;
						mailable_no.checked = false;
						livable_yes.checked = true;
						livable_no.checked = false;
					}
					else
					{
						mailable_yes.checked = false;
						mailable_no.checked = true;
						livable_yes.checked = false;
						livable_no.checked = true;
					}
				}
			}
			</script>
		</td></tr>
	<tr><td><label for="address-city_id" class="required">City</label></td>
		<td><select name="address[city_id]" id="address-city_id">
			<?php
				# Default city should be Bloomington
				if (isset($_POST['address']['city_id'])) { $city_id = $_POST['address']['city_id']; }
				else { $city = new City('BLOOMINGTON'); $city_id = $city->getId(); }

				$list = new CityList();
				$list->find();
				foreach($list as $city)
				{
					if ($city_id == $city->getId())
						{ echo "<option value=\"{$city->getId()}\" selected=\"selected\">{$city->getName()}</option>"; }
					else { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				}
			?>
			</select>
			<label for="address-zip">Zip</label>
			<input name="address[zip]" id="address-zip" size="5" maxlength="5" value="<?php if(isset($_POST['address']['zip'])) echo $_POST['address']['zip']; ?>" />
			<input name="address[zipplus4]" id="address-zipplus4" size="4" maxlength="4" value="<?php if(isset($_POST['address']['zipplus4'])) echo $_POST['address']['zipplus4']; ?>" />
		</td></tr>
	<tr><td><label for="address-addressStatus_id">Status</label></td>
		<td><select name="address[addressStatus_id]" id="address-addressStatus_id">
			<?php
				# Default status should be CURRENT
				if (isset($_POST['address']['addressStatus_id'])) { $status_id = $_POST['address']['addressStatus_id']; }
				else { $status = new Status('address',"CURRENT"); $status_id = $status->getId(); }

				$list = new StatusList('address');
				$list->find();
				foreach($list as $status)
				{
					if ($status_id == $status->getId())
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