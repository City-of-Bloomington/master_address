<?php
/*
	$this->address
*/
?>
<h2>Edit Address</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Address Info</legend>
	<input name="address_id" type="hidden" value="<?php echo $this->address->getId(); ?>" />
	<table>
	<tr><td><label for="address-number" class="required">Number</label></td>
		<td><input name="address[number]" id="address-number" value="<?php echo $this->address->getNumber(); ?>" />
			<input name="suffix" size="4" value="<?php echo $this->address->getSuffix(); ?>" />
		</td></tr>
	<tr><td><label for="address-addressType" class="required">Type</label></td>
		<td><select name="address[addressType]" id="address-addressType">
			<?php
				$list = new AddressTypeList();
				$list->find();
				foreach($list as $type)
				{
					if ($this->address->getAddressType() == $type)
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
					if ($this->address->getCity_id() == $city->getId())
						{ echo "<option value=\"{$city->getId()}\" selected=\"selected\">{$city->getName()}</option>"; }
					else { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-zip">Zip</label></td>
		<td><input name="address[zip]" id="address-zip" size="5" maxlength="5" value="<?php echo $this->address->getZip(); ?>" />
			<input name="address[zipplus4]" id="address-zipplus4" size="4" maxlength="4" value="<?php echo $this->address->getZipplus4(); ?>" />
		</td></tr>
	<tr><td><label for="address-addressStatus_id">Status</label></td>
		<td><select name="address[addressStatus_id]" id="address-addressStatus_id">
			<?php
				$list = new StatusList("address");
				$list->find();
				foreach($list as $status)
				{
					if ($this->address->getStatus()->getId() == $status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-active-yes">Active</label></td>
		<td><label><input type="radio" name="address[active]" id="address-active-yes" value="Y" <?php if($this->address->getActive()=="Y") echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="address[active]" id="address-active-no" value="N" <?php if($this->address->getActive()=="N") echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="address-startDate-mon">Start Date</label></td>
		<td><select name="address[startDate][mon]" id="address-startDate-mon"><option></option>
			<?php
				$startDate = $this->address->dateStringToArray($this->address->getStartDate());
				for($i=1; $i<=12; $i++)
				{
					if ($i!=$startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="address[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i!=$startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="address[startDate][year]" id="address-startDate-year" size="4" maxlength="4" value="<?php echo $startDate['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="address-endDate-mon">End Date</label></td>
		<td><select name="address[endDate][mon]" id="address-endDate-mon"><option></option>
				<?php
					$endDate = $this->address->dateStringToArray($this->address->getEndDate());
					for($i=1; $i<=12; $i++)
					{
						if ($i != $endDate['mon']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
			</select>
			<select name="address[endDate][mday]"><option></option>
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($i != $endDate['mday']) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
			</select>
			<input name="address[endDate][year]" id="address-endDate-year" size="4" maxlength="4" value="<?php echo $endDate['year']; ?>" />
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="address-notes">Notes</label></div>
			<textarea name="address[notes]" id="address-notes" rows="3" cols="60"><?php echo $this->address->getNotes(); ?></textarea>
		</td>
	</tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses/viewAddress.php?address_id=<?php echo $this->address->getId(); ?>';">Cancel</button>
</fieldset>
</form>